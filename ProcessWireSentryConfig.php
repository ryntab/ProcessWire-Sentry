<?php

namespace ProcessWire;

class ProcessWireSentryConfig extends ModuleConfig
{

    public function __construct()
    {
        $dsn = $this->wire('modules')->getConfig('ProcessWireSentry', 'dsn');
        $projectID = $this->wire('modules')->getConfig('ProcessWireSentry', 'project_id');
        $organizationID = $this->wire('modules')->getConfig('ProcessWireSentry', 'organization_id');
        $authToken = $this->wire('modules')->getConfig('ProcessWireSentry', 'auth_token');

        // Path to the Nuxt build index.html
        $indexHtmlPath = $this->wire('config')->paths->siteModules . 'ProcessWireSentry/event-viewer/dist/index.html';
        $indexHtmlContent = file_get_contents($indexHtmlPath);

        // Full base URL to prepend to assets
        $baseUrl = $this->wire('config')->urls->siteModule . 'site/modules/ProcessWireSentry/event-viewer/dist/';

        // Adjust paths in the head and body content
        $indexHtmlContent = preg_replace('/(href|src)="\/_nuxt\//', '$1="' . $baseUrl . '_nuxt/', $indexHtmlContent);
        $indexHtmlContent = preg_replace('/(href|src)="\/ProcessWireSentry\/event-viewer\/dist\//', '$1="' . $baseUrl, $indexHtmlContent);

        // Extract the head and body content
        preg_match('/<head>(.*?)<\/head>/s', $indexHtmlContent, $headMatches);
        preg_match('/<body>(.*?)<\/body>/s', $indexHtmlContent, $bodyMatches);

        $headContent = $headMatches[1] ?? '';
        $bodyContent = $bodyMatches[1] ?? '';


        // Add a mount point for the Nuxt application
        $mountPoint = '<div id="__nuxt"></div>';

        $this->add(
            array(
                array(
                    'name' => 'dsn', // name of field
                    'type' => 'text', // type of field (any Inputfield module name)
                    'label' => $this->_('Sentry DSN'), // field label
                    'description' => $this->_('Enter your Sentry DSN here'),
                    'required' => true,
                    'value' => $dsn
                ),
                array(
                    'name' => 'project_id', // name of field
                    'type' => 'text', // type of field
                    'label' => $this->_('Project ID'), // field label
                    'description' => $this->_('Enter your Sentry project ID here'),
                    'required' => true,
                    'value' => $projectID
                ),
                array(
                    'name' => 'organization_id', // name of field
                    'type' => 'text', // type of field
                    'label' => $this->_('Organization ID'), // field label
                    'description' => $this->_('Enter your Sentry organization ID here'),
                    'required' => true,
                    'value' => $organizationID
                ),
                array(
                    'name' => 'auth_token', // name of field
                    'type' => 'text', // type of field
                    'label' => $this->_('Auth Token'), // field label
                    'description' => $this->_('Enter your Sentry auth token here'),
                    'required' => true,
                    'value' => $authToken
                ),
                array(
                    'name' => 'traces_sample_rate', // name of field
                    'type' => 'float', // type of field (float for decimal numbers)
                    'label' => $this->_('Traces Sample Rate'), // field label
                    'description' => $this->_('Enter the traces sample rate for Sentry (e.g., 1.0 for 100% sampling, 0.5 for 50% sampling)'),
                    'required' => true,
                    'value' => 1.0 // default value
                ),
                array(
                    'name' => 'view_events', // name of field
                    'type' => 'markup', // use markup to create a custom button
                    'label' => $this->_('View Sentry Events'), // field label
                    'description' => $this->_('The latest events sent to Sentry are displayed below.'),
                    'collapsed' => Inputfield::collapsedNever,
                    'value' => '<div id="sentry-events-container" data-dsn="' . $dsn . '" data-project-id="' . $projectID . '" data-organization-id="' . $organizationID . '" data-auth-token="' . $authToken . '"></div>',
                ),
                array(
                    'name' => 'view_events', 
                    'type' => 'markup', 
                    'label' => $this->_('View Sentry Events'), 
                    'description' => $this->_('The latest events sent to Sentry are displayed below.'),
                    'collapsed' => Inputfield::collapsedNever,
                    'value' => '<div id="sentry-events-container" data-dsn="' . $dsn . '" data-project-id="' . $projectID . '" data-organization-id="' . $organizationID . '" data-auth-token="' . $authToken . '"></div>',
                ),
                array(
                    'type' => 'markup',
                    'label' => '',
                    'value' => $headContent . $mountPoint . $bodyContent . '
                        <script>window.__NUXT__={};window.__NUXT__.config={public:{SENTRY_DSN:"' . $dsn . '",SENTRY_PROJECT_ID:"' . $projectID . '",SENTRY_ORGANIZATION_ID:"' . $organizationID . '",SENTRY_AUTH_TOKEN:"' . $authToken . '"},app:{baseURL:"' . $baseUrl . '",buildAssetsDir:"/_nuxt/",cdnURL:""}};</script>
                    '
                )
            )
        );
    }
}
