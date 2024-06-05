<template>
  <div>
    <!-- <div class="flex space-x-4 mb-4">
      <button @click="setTimeRange('-1h')">1 Hour</button>
      <button @click="setTimeRange('-6h')">6 Hours</button>
      <button @click="setTimeRange('-24h')">24 Hours</button>
      <button @click="setTimeRange('-48h')">48 Hours</button>
      <button @click="setTimeRange('-7d')">1 Week</button>
    </div> -->
    <Chart v-if="events.length > 0" :data="events" />
    <div class="h-[800px] overflow-scroll">
      <div
        class="flex flex-col space-y-4 p-2"
        v-for="event in events"
        :key="event.id"
      >
        <Event :event="event" />
      </div>
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
      timeRange: "-24h", // Default time range
    };
  },
  mounted() {
    const runtime = useRuntimeConfig();
    console.log(runtime.public);
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

    console.log("Test");
    console.log(this.dsn, this.projectID, this.organizationID, this.authToken);

    if (this.dsn && this.projectID && this.organizationID && this.authToken) {
      this.init();
    }
  },
  methods: {
    init() {
      this.getEvents();
    },
    setTimeRange(range) {
      this.timeRange = range;
      this.getEvents();
    },
    async getEvents() {
      const endTimestamp = Math.floor(Date.now() / 1000); // Current timestamp in seconds
      let startTimestamp;

      switch (this.timeRange) {
        case "-1h":
          startTimestamp = endTimestamp - 3600; // 1 hour ago
          break;
        case "-6h":
          startTimestamp = endTimestamp - 21600; // 6 hours ago
          break;
        case "-24h":
          startTimestamp = endTimestamp - 86400; // 24 hours ago
          break;
        case "-48h":
          startTimestamp = endTimestamp - 172800; // 48 hours ago
          break;
        case "-7d":
          startTimestamp = endTimestamp - 604800; // 7 days ago
          break;
        default:
          startTimestamp = endTimestamp - 86400; // Default to 24 hours ago
      }

      const url = `https://sentry.io/api/0/projects/${this.organizationID}/${this.projectID}/events/?start=${startTimestamp}&end=${endTimestamp}`;
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
};
</script>
