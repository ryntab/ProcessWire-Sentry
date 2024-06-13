![ProcessWire Sentry](/Banner.jpg)
# ProcessWire Sentry Integration Module 
![example workflow](https://github.com/github/docs/actions/workflows/generate-event-viewer.yml/badge.svg)


This ProcessWire module integrates Sentry error tracking into your ProcessWire site, with additional support for viewing live Sentry events.

## 🚧 This module is not production ready

## Features

- Configure Sentry DSN, Project ID, Organization ID, and Auth Token.
- Set the trace sample rate for Sentry.
- View the latest events sent to Sentry in an embedded Nuxt.js application.

## Configure

Go to the module settings in the ProcessWire admin and configure the following fields:

- Sentry DSN: Your Sentry DSN.
- Project ID: Your Sentry project ID.
- Organization ID: Your Sentry organization ID.
- Auth Token: Your Sentry auth token.
- Trace Sample Rate: The trace sample rate for Sentry (e.g., 1.0 for 100% sampling, 0.5 for 50% sampling).
Usage

Once the module is installed and configured, you can view the latest events sent to Sentry directly in the ProcessWire admin interface. Navigate to the module configuration page, and the Nuxt.js application will display the events.
