<?php

namespace ProcessWire;

// Include Composer's autoload file
require_once (__DIR__ . '/vendor/autoload.php');

// Initialize Sentry
\Sentry\init([
    'dsn' => 'https://686d19dc56af9764a3f5e1d59c5ee496@o4505273794166784.ingest.us.sentry.io/4507376269197312',
    // Specify a fixed sample rate
    'traces_sample_rate' => 1.0,
    // Set a sampling rate for profiling - this is relative to traces_sample_rate
    'profiles_sample_rate' => 1.0,
  ]);

// Add a check to ensure Sentry is initialized
// if (\Sentry\SentrySdk::getCurrentHub()->getClient()) {
//     echo "Sentry initialized successfully.\n";
// } else {
//     echo "Sentry initialization failed.\n";
// }

// Capture a message
try {
    \Sentry\captureMessage('Something went wrong');
    // echo "Message captured successfully.\n";
} catch (Exception $e) {
    echo "Failed to capture message: " . $e->getMessage() . "\n";
}


try {
    $this->functionFailsForSure();
} catch (\Throwable $exception) {
    \Sentry\captureException($exception);
}

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
            'author' => 'Your Name',
            'icon' => 'bug',
            'requires' => ['PHP>=7.2', 'ProcessWire>=3.0.0'],
            'singular' => true,
            'autoload' => true
        ];
    }

    public function init()
    {
        // Include Composer's autoload file


        // Get the DSN and traces_sample_rate from the module configuration
        $dsn = $this->wire('modules')->getConfig('ProcessWireSentry', 'dsn');
        $tracesSampleRate = $this->wire('modules')->getConfig('ProcessWireSentry', 'traces_sample_rate');

        // Debug: Check if DSN and traces_sample_rate are being set
        if (empty($dsn)) {
            wire('log')->save('sentry', 'DSN is not set in the configuration');
            return;
        }

        if (empty($tracesSampleRate)) {
            wire('log')->save('sentry', 'tDSN is is not set in the configuration');
            return;
        }

        // Sentry initialization
        try {
            throw new \Exception('Sentry test exception');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            wire('log')->save('sentry', 'Test exception sent to Sentry.');
        }

        wire('log')->save('sentry', 'DSN: ' . $dsn);
        wire('log')->save('sentry', 'Traces Sample Rate: ' . $tracesSampleRate);

        try {
            \Sentry\captureMessage('Sentry test message');
            wire('log')->save('sentry', 'Test message sent to Sentry.');
        } catch (\Exception $e) {
            wire('log')->save('sentry', 'Failed to send test message: ' . $e->getMessage());
        }

        // Store previous error and exception handlers
        $this->previousErrorHandler = set_error_handler([$this, 'handleError']);
        $this->previousExceptionHandler = set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);

        // Hook into ProcessPageView::finished to capture notices after page render
        $this->addHookAfter('ProcessPageView::finished', $this, 'captureNotices');

        // Hook into WireLog::save to capture error logs
        $this->addHookAfter('WireLog::save', $this, 'captureLogErrors');

        // Debug: Log initialization success
        // wire('log')->save('sentry', 'Sentry initialized and handlers set.');
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
        wire('log')->save('sentry', 'Handling exception: ' . $exception->getMessage());

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
        wire('log')->save('sentry', 'Capturing notices: ' . count($notices));
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
            wire('log')->save('sentry', 'Capturing log error: ' . $message);
            // Capture the log message as an exception in Sentry
            \Sentry\captureMessage($message);
        }
    }
}
