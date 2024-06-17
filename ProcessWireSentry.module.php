<?php

namespace ProcessWire;

// Include Composer's autoload file
require_once (__DIR__ . '/vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ProcessWireSentry extends WireData implements Module
{
    private $previousErrorHandler;
    private $previousExceptionHandler;

    public static function getModuleInfo()
    {
        return [
            'title' => 'Sentry Integration',
            'version' => 1,
            'summary' => 'Sentry integration for error logging in ProcessWire',
            'author' => 'https://github.com/ryntab',
            'icon' => 'bug',
            'requires' => ['PHP>=8.2', 'ProcessWire>=3.0.0'],
            'singular' => true,
            'autoload' => true
        ];
    }

    public function init()
    {
        // Include Composer's autoload file
        require_once (__DIR__ . '/vendor/autoload.php');

        // Create a logger instance
        $logger = new Logger('sentry');
        $logger->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));

        // Get the DSN and traces_sample_rate from the module configuration
        $dsn = $this->wire('modules')->getConfig('ProcessWireSentry', 'dsn');
        $debug = $this->wire('modules')->getConfig('ProcessWireSentry', 'debug_mode');
        $localLog = $this->wire('modules')->getConfig('ProcessWireSentry', 'local_log');
        $tracesSampleRate = $this->wire('modules')->getConfig('ProcessWireSentry', 'traces_sample_rate');
        $jsCDN = $this->wire('modules')->getConfig('ProcessWireSentry', 'js_cdn');

        // Sentry initialization with enhanced logging
        \Sentry\init([
            'dsn' => $dsn,
            'traces_sample_rate' => (float) $tracesSampleRate,
            'logger' => $logger,
        ]);

        if ($jsCDN) {
            // Check if the current request is not in the admin backend
            if (!$this->wire('config')->ajax && !$this->wire('config')->admin) {
                $this->wire('config')->scripts->add($jsCDN);
            }
        }

        // Debug: Check if DSN and traces_sample_rate are being set
        if (empty($dsn)) {
            $this->logLocally('DSN is not set in the configuration');
            return;
        }

        if (empty($tracesSampleRate)) {
            $this->logLocally('traces_sample_rate is not set in the configuration');
            return;
        }

        if ($debug) {
            try {
                throw new \Exception('Test exception from ProcessWireSentry module');
            } catch (\Exception $e) {
                \Sentry\captureException($e);
                $this->logLocally('Debug mode enabled, exception captured');
            }
        }

        // Store previous error and exception handlers
        $this->previousErrorHandler = set_error_handler([$this, 'handleError']);
        $this->previousExceptionHandler = set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);

        // Hook into ProcessPageView::finished to capture notices after page render
        $this->addHookAfter('ProcessPageView::finished', $this, 'captureNotices');

        // Hook into WireLog::save to capture error logs
        $this->addHookAfter('WireLog::save', $this, 'captureLogErrors');
    }

    public function handleError($errno, $errstr, $errfile, $errline)
    {
        // Check if error reporting is turned off
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $errorTypes = [
            E_ERROR => 'Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Strict Notice',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'User Deprecated',
        ];

        $errorType = isset($errorTypes[$errno]) ? $errorTypes[$errno] : 'Unknown Error';
        $message = "$errorType: $errstr in $errfile on line $errline";

        // Debug: Log the error message
        wire('log')->save('sentry', 'Handling error: ' . $message);

        // Send error to Sentry
        \Sentry\captureMessage($message);

        // Call previous error handler
        if ($this->previousErrorHandler) {
            return call_user_func($this->previousErrorHandler, $errno, $errstr, $errfile, $errline);
        }

        // Ensure default error handling
        return false;
    }

    public function handleException($exception)
    {
        // Debug: Log the exception message
        $this->logLocally('Handling exception: ' . $exception->getMessage());

        // Send exception to Sentry
        \Sentry\captureException($exception);

        // Call previous exception handler
        if ($this->previousExceptionHandler) {
            call_user_func($this->previousExceptionHandler, $exception);
        } else {
            // If no previous handler, use default handler
            echo $exception;
        }
    }

    public function handleShutdown()
    {
        $error = error_get_last();
        if ($error !== null) {
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    public function captureNotices(HookEvent $event)
    {
        $notices = wire('notices');
        $this->logLocally('Capturing notices: ' . count($notices));
        foreach ($notices as $notice) {
            $text = $notice instanceof NoticeError ? "Error" : "Message";
            if ($notice instanceof NoticeError) {
                \Sentry\captureMessage("{$text}: {$notice->text}");
            } else {
                \Sentry\captureMessage("{$text}: {$notice->text}");
            }
        }
    }

    public function captureLogErrors(HookEvent $event)
    {
        $message = $event->arguments(0); // Log message
        $name = $event->arguments(1); // Log type (name)

        if ($name === 'errors' || $name === 'exceptions') {
            $this->logLocally('Capturing log error: ' . $message);
            \Sentry\captureMessage($message);
        }
    }

    public function logLocally($message)
    {
        if ($this->wire('modules')->getConfig('ProcessWireSentry', 'local_log')) {
            $this->wire('log')->save('sentry', $message);
        }
    }
}

