<?php
namespace ProcessWire;

class ProcessWireSentryConfig extends ModuleConfig
{

    private static $nuxtInjected = false;

    public function __construct()
    {
        $dsn = $this->wire('modules')->getConfig('ProcessWireSentry', 'dsn');
        $projectID = $this->wire('modules')->getConfig('ProcessWireSentry', 'project_id');
        $organizationID = $this->wire('modules')->getConfig('ProcessWireSentry', 'organization_id');
        $authToken = $this->wire('modules')->getConfig('ProcessWireSentry', 'auth_token');
        $debugMode = $this->wire('modules')->getConfig('ProcessWireSentry', 'debug_mode');
        $localLog = $this->wire('modules')->getConfig('ProcessWireSentry', 'local_log');

        $this->addConfigFields($dsn, $projectID, $organizationID, $authToken, $debugMode, $localLog);

        if (!self::$nuxtInjected) {
            $this->add(
                array(
                    array(
                        'type' => 'markup',
                        'label' => '',
                        'value' => $this->renderNuxtContent($dsn, $projectID, $organizationID, $authToken, $debugMode)
                    )
                )
            );
            self::$nuxtInjected = true;
        }
    }

    private function addConfigFields($dsn, $projectID, $organizationID, $authToken, $debugMode, $localLog)
    {
        $this->add(
            array(
                array(
                    'type' => 'fieldset',
                    'label' => $this->_('Sentry Configuration'),
                    'collapsed' => Inputfield::collapsedYes,
                    'children' => array(
                        array(
                            'name' => 'dsn',
                            'type' => 'text',
                            'label' => $this->_('Sentry DSN'),
                            'description' => $this->_('Enter your Sentry DSN here'),
                            'required' => true,
                            'value' => $dsn
                        ),
                        array(
                            'name' => 'project_id',
                            'type' => 'text',
                            'label' => $this->_('Project ID'),
                            'description' => $this->_('Enter your Sentry project ID here'),
                            'required' => true,
                            'value' => $projectID
                        ),
                        array(
                            'name' => 'organization_id',
                            'type' => 'text',
                            'label' => $this->_('Organization ID'),
                            'description' => $this->_('Enter your Sentry organization ID here'),
                            'required' => true,
                            'value' => $organizationID
                        ),
                        array(
                            'name' => 'auth_token',
                            'type' => 'text',
                            'label' => $this->_('Auth Token'),
                            'description' => $this->_('Enter your Sentry auth token here'),
                            'required' => true,
                            'value' => $authToken
                        ),
                    )
                ),
                array(
                    'type' => 'fieldset',
                    'label' => $this->_('Advanced Settings'),
                    'collapsed' => Inputfield::collapsedYes,
                    'children' => array(
                        array(
                            'name' => 'traces_sample_rate',
                            'type' => 'float',
                            'label' => $this->_('Traces Sample Rate'),
                            'description' => $this->_('Enter the traces sample rate for Sentry (e.g., 1.0 for 100% sampling, 0.5 for 50% sampling)'),
                            'required' => true,
                            'value' => 1.0
                        ),
                        array(
                            'name' => 'debug_mode',
                            'type' => 'checkbox',
                            'label' => $this->_('Debug Mode'),
                            'description' => $this->_('Enable debug mode for additional logging'),
                            'value' => $debugMode
                        ),
                        array(
                            'name' => 'local_log',
                            'type' => 'checkbox',
                            'label' => $this->_('Log Events Locally'),
                            'description' => $this->_('Enable local event logging, in most cases this should be disabled.'),
                            'value' => $localLog
                        ),
                        // Add more fields within the collapsible section as needed
                    ),
                ),
                array(
                    'type' => 'markup',
                    'label' => '',
                    'value' => $this->renderNuxtContent($dsn, $projectID, $organizationID, $authToken, $debugMode)
                )
            )
        );
    }

    private function renderNuxtContent($dsn, $projectID, $organizationID, $authToken, $debugMode)
    {
        // Path to the Nuxt build index.html
        $indexHtmlPath = $this->wire('config')->paths->siteModules . 'ProcessWireSentry/event-viewer/dist/index.html';
        $indexHtmlContent = file_get_contents($indexHtmlPath);

        // Get the module directory URL
        $baseUrl = $this->wire('config')->urls->siteModules . 'ProcessWireSentry/event-viewer/dist/';

        // Prepend base URL to all href and src attributes
        $indexHtmlContent = preg_replace('/(href|src)="\/_nuxt\//', '$1="' . $baseUrl . '_nuxt/', $indexHtmlContent);
        $indexHtmlContent = preg_replace('/(href|src)="\/ProcessWireSentry\/event-viewer\/dist\//', '$1="' . $baseUrl, $indexHtmlContent);

        // Extract the head and body content
        preg_match('/<head>(.*?)<\/head>/s', $indexHtmlContent, $headMatches);
        preg_match('/<body>(.*?)<\/body>/s', $indexHtmlContent, $bodyMatches);

        $headContent = $headMatches[1] ?? '';
        $bodyContent = $bodyMatches[1] ?? '';

        // Add a mount point for the Nuxt application
        $mountPoint = '<div id="__nuxt"></div>';

        // Combine the content to be injected
        $injectedContent = $headContent . $mountPoint . $bodyContent . '
            <script>
                window.__NUXT__ = {};
                window.__NUXT__.config = {
                    public: {
                        SENTRY_DSN: "' . $dsn . '",
                        SENTRY_PROJECT_ID: "' . $projectID . '",
                        SENTRY_ORGANIZATION_ID: "' . $organizationID . '",
                        SENTRY_AUTH_TOKEN: "' . $authToken . '",
                        DEBUG_MODE: ' . ($debugMode ? 'true' : 'false') . '
                    },
                    app: {
                      
                        buildAssetsDir: "/event-viewer/dist/",  
                    }
                };
            </script>
        ';

        return $injectedContent;
    }
}
