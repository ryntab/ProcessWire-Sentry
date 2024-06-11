<template>
  <div class="overflow-hidden">
    <div class="flex justify-between absolute right-0 m-2 z-20 align-middle w-full px-4">
      <div>
        <span class="font-medium">Issues in the last {{ timeRange }}</span>
      </div>
      <div class="space-x-2">
        <span class="text-sm">Last</span>
        <span :class="buttonClass" @click="setTimeRange('24h')">
          24 Hours
        </span>
        <span :class="buttonClass" @click="setTimeRange('14d')">14 Days</span>
      </div>
    </div>
    <div class="relative h-64 w-full">
      <!-- <Chart v-if="events.length > 0" :data="events" /> -->
      <IssuesChart v-if="issues.length > 0" :data="issues" :loading="loading" />
    </div>
    <div class="h-[800px] overflow-scroll">
      <div
        v-if="!loading"
        class="flex flex-col space-y-4 p-2"
        v-for="issue in issues"
        :key="issue.id"
      >
        <IssuesCard :issue="issue" :loading="loading" />
      </div>
      <IssuesSkeleton v-else v-for="index of 20" :loading="true" />
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dsn: null,
      projectID: null,
      organizationID: null,
      authToken: null,
      events: [],
      issues: [],
      timeRange: "14d", // Default time range
      loading: false,
    };
  },
  mounted() {
    const runtime = useRuntimeConfig();
    const container = document.getElementById("sentry-events-container");

    if (container) {
      this.dsn = container.dataset.dsn;
      this.projectID = container.dataset.projectId;
      this.organizationID = container.dataset.organizationId;
      this.authToken = container.dataset.authToken;
    } else {
      this.dsn = runtime.public.SENTRY_DSN;
      this.projectID = runtime.public.SENTRY_PROJECT_ID;
      this.organizationID = runtime.public.SENTRY_ORGANIZATION_ID;
      this.authToken = runtime.public.SENTRY_AUTH_TOKEN;
    }

    if (this.dsn && this.projectID && this.organizationID && this.authToken) {
      this.init();
    }
  },
  methods: {
    init() {
      this.getIssues();
    },
    setTimeRange(range) {
      this.timeRange = range;
      this.getIssues();
    },
    async getIssues() {
      this.loading = true;
      const url = `https://sentry.io/api/0/projects/${this.organizationID}/${this.projectID}/issues/?statsPeriod=${this.timeRange}`;
      try {
        const response = await fetch(url, {
          headers: {
            Authorization: `Bearer ${this.authToken}`,
          },
        });
        if (response.ok) {
          const data = await response.json();
          this.issues = data;
        } else {
          console.error("Failed to fetch events:", response.statusText);
        }
      } catch (error) {
        console.error("Error fetching events:", error);
      } finally {
        this.loading = false;
      }
    },
    async getEvents() {
      const url = `https://sentry.io/api/0/projects/${this.organizationID}/${this.projectID}/issues/?statsPeriod=${this.timeRange}`;
      console.log("Fetching events with URL:", url);

      try {
        const response = await fetch(url, {
          headers: {
            Authorization: `Bearer ${this.authToken}`,
          },
        });
        console.log("Response status:", response.status);
        if (response.ok) {
          const data = await response.json();
          console.log("Fetched events data:", data);
          this.events = data;
          console.log("Fetched events:", this.events);
        } else {
          console.error("Failed to fetch events:", response.statusText);
        }
      } catch (error) {
        console.error("Error fetching events:", error);
      }
    },
  },
  computed: {
    buttonClass() {
      return "bg-gray-200 text-gray-800 px-2 py-1 rounded-md text-xs cursor-pointer";
    },
  },
};
</script>
