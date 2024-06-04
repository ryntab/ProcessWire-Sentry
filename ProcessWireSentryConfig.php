<?php

namespace ProcessWire;

class ProcessWireSentryConfig extends ModuleConfig {

    public function __construct() {

        $this->add(array(
            array(
                'name' => 'dsn', // name of field
                'type' => 'text', // type of field (any Inputfield module name)
                'label' => $this->_('Sentry DSN'), // field label
                'description' => $this->_('Enter your Sentry DSN here'), 
                'required' => true,
                'value' => ''
            ),
            array(
                'name' => 'traces_sample_rate', // name of field
                'type' => 'float', // type of field (float for decimal numbers)
                'label' => $this->_('Traces Sample Rate'), // field label
                'description' => $this->_('Enter the traces sample rate for Sentry (e.g., 1.0 for 100% sampling, 0.5 for 50% sampling)'), 
                'required' => true,
                'value' => 0 // default value
            ),
        ));
    }
}
