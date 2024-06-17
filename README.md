
# ProcessWire Sentry Integration Module

![example workflow](https://github.com/ryntab/ProcessWire-Sentry/actions/workflows/build.yml/badge.svg)

This ProcessWire module integrates Sentry error tracking into your ProcessWire back-end, with additional support for front-end error tracing through the Sentry JS SDK.

## 🚧 This module is not production ready

## Table of Contents
- [Features](#features)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Event Viewer](#event-viewer)
- [Troubleshooting](#troubleshooting)
- [Development](#development)
  - [Build Module Locally](#build-module-locally)
  - [Modify Event Viewer Locally](#modify-event-viewer-locally)
- [Contributing](#contributing)
- [License](#license)

## Features
- Backend ProcessWire error tracing with Sentry
- Front-end ProcessWire error tracing with Sentry JS SDK
- A simple event viewer in the ProcessWire admin dashboard

## Prerequisites
- ProcessWire 3.x
- PHP 8.2 or higher
- Composer
- Nuxt

## Installation
1. Download the latest release of the module from the [releases page](https://github.com/ryntab/ProcessWire-Sentry/releases).
2. Extract the downloaded archive and place the `ProcessWireSentry` directory in your ProcessWire project's `site/modules` directory.
3. Log in to the ProcessWire admin and go to `Modules > Refresh` to refresh the module list.
4. Install the `ProcessWire Sentry Integration` module.

## Configuration
Go to the module settings in the ProcessWire admin and configure the following fields:
- Sentry DSN: Your Sentry DSN.
- Project ID: Your Sentry project ID.
- Organization ID: Your Sentry organization ID.
- Auth Token: Your Sentry auth token.
- Trace Sample Rate: The trace sample rate for Sentry (e.g., 1.0 for 100% sampling, 0.5 for 50% sampling).

Once the module is installed and configured, you can view the latest events sent to Sentry directly in the ProcessWire admin interface. Navigate to the module configuration page, and the Nuxt.js application will display the events.

## Troubleshooting
- If you encounter any issues with the module, please check the [issues](https://github.com/ryntab/ProcessWire-Sentry/issues) page to see if it has already been reported. If not, feel free to open a new issue with a detailed description of the problem.
- 
## Event Viewer

![ProcessWire Sentry](/docs/event-viewer-demo.gif)

## Development

### Build Module Locally
To set up this project locally or run without using a release version. Follow the instructions below. If you do not want the event-viewer, and simply want error tracing through Sentry you can safely remove the event-viewer directory.

 1. Clone repository
    
	 ```bash
	 gh repo clone ryntab/ProcessWire-Sentry || https://github.com/ryntab/ProcessWire-Sentry.git 
	 ```
  
 3. Install Composer packages
    
	```bash
	composer update
	```
	
 4. Setup Nuxt event-viewer
    
	```bash
	cd event-viewer
	npm run install && npm run generate
	```
 
### Modify Event Viewer Locally.
To make changes to the event-viewer Nuxt app. Follow the instructions below.
  1. Clone repository

      ```
      gh repo clone ryntab/ProcessWire-Sentry || https://github.com/ryntab/ProcessWire-Sentry.git
      ```
3. Navigate to event-viewer directory
   
   ```
   cd event-viewer
   npm run install && npm run dev
   ```
5. In order to fetch issues from the Sentry API, an app integration token needs to be generated and your current working local environment URL needs to be whitelisted for the app integration. You will also need to set your environment variables for `SENTRY_DSN`, `SENTRY_PROJECT_ID`, `SENTRY_ORGANIZATION_ID` and `SENTRY_AUTH_TOKEN` which are loaded in the Nuxt config.
   

    ```js
    runtimeConfig: {
      public: {
        SENTRY_DSN: process.env.SENTRY_DSN,
        SENTRY_PROJECT_ID: process.env.SENTRY_PROJECT_ID,
        SENTRY_ORGANIZATION_ID: process.env.SENTRY_ORGANIZATION_ID,
        SENTRY_AUTH_TOKEN: process.env.SENTRY_AUTH_TOKEN
      }
    }
    ```
    
## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request. 
