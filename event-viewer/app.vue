<template>
  <div class="event-viewer overflow-hidden w-full relative">
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
<style>
/* WebKit browsers (Chrome, Safari) */
.event-viewer ::-webkit-scrollbar {
  width: 5px; /* Width of the scrollbar */
  height: 5px; /* Height of the scrollbar */
}

.event-viewer ::-webkit-scrollbar-track {
  background: transparent; /* Background of the scrollbar track */
}

.event-viewer ::-webkit-scrollbar-thumb {
  background: #e6e6e6; /* Color of the scrollbar thumb */
  border-radius: 10px; /* Rounded corners for the scrollbar thumb */
}

.event-viewer ::-webkit-scrollbar-thumb:hover {
  background: #8b8b8b; /* Darker color when hovering over the scrollbar thumb */
}

/* Firefox */
.event-viewer * {
  scrollbar-width: thin; /* Thin scrollbar */
  scrollbar-color: #e6e6e6 transparent; /* Thumb color and track color */
}

.event-viewer *::-webkit-scrollbar-track {
  background: transparent; /* Background of the scrollbar track */
}

.event-viewer *::-webkit-scrollbar-thumb {
  background-color: #888; /* Color of the scrollbar thumb */
  border-radius: 10px; /* Rounded corners for the scrollbar thumb */
  border: 3px solid transparent; /* Extra space around the thumb */
}

.event-viewer *::-webkit-scrollbar-thumb:hover {
  background-color: #969696; /* Darker color when hovering over the scrollbar thumb */
}

</style>