<?php
namespace ProcessWire;

class ProcessWireSentryConfig extends ModuleConfig {

    private static $nuxtInjected = false;

    public function __construct() {
        $dsn = $this->wire('modules')->getConfig('ProcessWireSentry', 'dsn');
        $projectID = $this->wire('modules')->getConfig('ProcessWireSentry', 'project_id');
        $organizationID = $this->wire('modules')->getConfig('ProcessWireSentry', 'organization_id');
        $authToken = $this->wire('modules')->getConfig('ProcessWireSentry', 'auth_token');

        $this->addConfigFields();

        if (!self::$nuxtInjected) {
            $this->injectNuxtContent();
            self::$nuxtInjected = true;
        }
    }

    private function addConfigFields() {
        $this->add(array(
            array(
                'name' => 'dsn',
                'type' => 'text',
                'label' => $this->_('Sentry DSN'),
                'description' => $this->_('Enter your Sentry DSN here'),
                'required' => true,
                'value' => $this->wire('modules')->getConfig('ProcessWireSentry', 'dsn')
            ),
            array(
                'name' => 'project_id',
                'type' => 'text',
                'label' => $this->_('Project ID'),
                'description' => $this->_('Enter your Sentry project ID here'),
                'required' => true,
                'value' => $this->wire('modules')->getConfig('ProcessWireSentry', 'project_id')
            ),
            array(
                'name' => 'organization_id',
                'type' => 'text',
                'label' => $this->_('Organization ID'),
                'description' => $this->_('Enter your Sentry organization ID here'),
                'required' => true,
                'value' => $this->wire('modules')->getConfig('ProcessWireSentry', 'organization_id')
            ),
            array(
                'name' => 'auth_token',
                'type' => 'text',
                'label' => $this->_('Auth Token'),
                'description' => $this->_('Enter your Sentry auth token here'),
                'required' => true,
                'value' => $this->wire('modules')->getConfig('ProcessWireSentry', 'auth_token')
            ),
            array(
                'name' => 'traces_sample_rate',
                'type' => 'float',
                'label' => $this->_('Traces Sample Rate'),
                'description' => $this->_('Enter the traces sample rate for Sentry (e.g., 1.0 for 100% sampling, 0.5 for 50% sampling)'),
                'required' => true,
                'value' => 1.0
            ),
            array(
                'name' => 'view_events',
                'type' => 'markup',
                'label' => $this->_('View Sentry Events'),
                'description' => $this->_('The latest events sent to Sentry are displayed below.'),
                'collapsed' => Inputfield::collapsedNever,
                'value' => '<div id="sentry-events-container" data-dsn="' . $this->wire('modules')->getConfig('ProcessWireSentry', 'dsn') . '" data-project-id="' . $this->wire('modules')->getConfig('ProcessWireSentry', 'project_id') . '" data-organization-id="' . $this->wire('modules')->getConfig('ProcessWireSentry', 'organization_id') . '" data-auth-token="' . $this->wire('modules')->getConfig('ProcessWireSentry', 'auth_token') . '"></div>',
            )
        ));
    }

    private function injectNuxtContent() {
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

        $this->add(array(
            array(
                'type' => 'markup',
                'label' => '',
                'value' => $headContent . $mountPoint . $bodyContent . '
                    <script>window.__NUXT__={};window.__NUXT__.config={public:{SENTRY_DSN:"' . $this->wire('modules')->getConfig('ProcessWireSentry', 'dsn') . '",SENTRY_PROJECT_ID:"' . $this->wire('modules')->getConfig('ProcessWireSentry', 'project_id') . '",SENTRY_ORGANIZATION_ID:"' . $this->wire('modules')->getConfig('ProcessWireSentry', 'organization_id') . '",SENTRY_AUTH_TOKEN:"' . $this->wire('modules')->getConfig('ProcessWireSentry', 'auth_token') . '"},app:{baseURL:"' . $baseUrl . '",buildAssetsDir:"/_nuxt/",cdnURL:""}};</script>
                '
            )
        ));
    }
}
