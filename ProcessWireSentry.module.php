<?php

namespace ProcessWire;

use Sentry;

class ProcessWireSentry extends WireData implements Module {

    public static function getModuleInfo() {
        return [
            'title' => 'Process Wire Sentry',
            'version' => '1.0',
            'summary' => 'Sentry integration for error logging in ProcessWire',
            'author' => 'SFA Marketing',
            'icon' => 'bug',
            'requires' => ['PHP>=7.2', 'ProcessWire>=3.0.0'],
            'singular' => true,
            'autoload' => true
        ];
    }

    public function init() {
        // Include Composer's autoload file
        require_once(__DIR__ . '/vendor/autoload.php');

        // Get the DSN and traces_sample_rate from the module configuration
        $dsn = $this->wire('modules')->getConfig('ProcessWireSentry', 'dsn');
        $tracesSampleRate = $this->wire('modules')->getConfig('ProcessWireSentry', 'traces_sample_rate');
        
        // Debug: Check if DSN and traces_sample_rate are being set
        if (empty($dsn)) {
            wire('log')->save('sentry', 'DSN is not set in the configuration');
        } else {
            wire('log')->save('sentry', 'DSN is set: ' . $dsn);
        }
        
        if (empty($tracesSampleRate)) {
            wire('log')->save('sentry', 'traces_sample_rate is not set in the configuration');
        } else {
            wire('log')->save('sentry', 'traces_sample_rate is set: ' . $tracesSampleRate);
        }
        
        // Sentry initialization
        Sentry\init([
            'dsn' => $dsn,
            'traces_sample_rate' => (float) $tracesSampleRate,
        ]);

        // Hook into ProcessWire error handling
        $this->addHookAfter('ProcessPageView::execute', $this, 'logErrors');
    }

    public function logErrors(HookEvent $event) {
        // Log an error to Sentry
        try {
            // Your code that might throw an exception
        } catch (\Exception $e) {
            Sentry\captureException($e);
        }
    }
}
