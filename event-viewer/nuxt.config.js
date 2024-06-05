export default defineNuxtConfig({
  devtools: { enabled: true },
  ssr: false,
  target: "static",
  modules: ["@nuxtjs/tailwindcss", "nuxt-icon"],
  runtimeConfig: {
    public: {
      SENTRY_DSN: process.env.SENTRY_DSN,
      SENTRY_PROJECT_ID: process.env.SENTRY_PROJECT_ID,
      SENTRY_ORGANIZATION_ID: process.env.SENTRY_ORGANIZATION_ID,
      SENTRY_AUTH_TOKEN: process.env.SENTRY_AUTH_TOKEN
    }
  },
  app: {
    baseURL: '/ProcessWireSentry/event-viewer/dist/', // Base URL for the application
    buildAssetsDir: '/_nuxt/', // Directory for build assets
  },
  build: {
    publicPath: '/ProcessWireSentry/event-viewer/dist/',
    splitChunks: {
      layouts: false,
      pages: false,
      commons: false
    },
    extractCSS: true,
    optimization: {
      splitChunks: {
        cacheGroups: {
          styles: {
            name: 'styles',
            test: /\.(css|vue)$/,
            chunks: 'all',
            enforce: true
          }
        }
      }
    }

  },
  router: {
    base: '/ProcessWireSentry/event-viewer/dist/'
  },
  // build: {
  //   transpile: ['@vitejs/plugin-vue']
  // },
  // vite: true
})
