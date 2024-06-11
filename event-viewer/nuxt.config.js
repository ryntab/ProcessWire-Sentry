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
})
