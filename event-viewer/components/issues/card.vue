<template>
  <div
    v-if="!loading"
    class="flex flex-col border-2 align-middle p-4 relative space-y-2"
  >
    <div id="issue-toolbar" class="flex text-sm justify-between border-b pb-2">
      <div class="flex flex-row flex-grow space-x-2 py-1 text-sm w-auto">
        <span
          class="px-2 bg-purple-100 rounded-md capitalize"
          :class="priorityColor"
        >
          {{ issue.priority }}
        </span>
        <span class="font-semibold truncate max-w-xs w-auto">{{
          issue.title
        }}</span>
      </div>
      <div class="justify-center flex-shrink align-middle space-x-1">
        <span
          class="px-2 py-1 bg-gray-100 rounded-md text-gray-400 space-x-1 align-middle"
        >
          Count: {{ issue.count }}
        </span>
        <span
          class="px-2 py-1 bg-gray-100 rounded-md text-gray-400 space-x-1 align-middle"
        >
          {{ issue.status }}
        </span>
        <span
          class="px-2 py-1 bg-gray-100 rounded-md text-gray-400 space-x-1 align-middle"
        >
          {{ issue.shortId }}
        </span>
        <span
          class="px-2 py-1 bg-gray-100 rounded-md text-gray-400 space-x-1 align-middle"
        >
          {{ issue.priority }}
        </span>
        <a target="_blank" @click="openInNewTab(issue.permalink)">
          <button
            class="px-2 py-1 bg-gray-100 rounded-md text-gray-400 space-x-1 align-middle"
          >
            <span class="-ml-1">Open Issue</span>
            <icon name="majesticons:open" />
          </button>
        </a>
      </div>
    </div>
    <div class="px-2 py-4 relative">
      <div class="flex flex-col" v-if="issue.culprit">
        <pre class="text-sm font-bold text-gray-700 text-purple-500'">
Culprit:</pre
        >
        <pre class="text-xs bg-gray-100 text-gray-400 p-2 rounded-sm w-full">{{
          issue.culprit
        }}</pre>
      </div>
      <div class="flex flex-col" v-if="issue.location">
        <pre class="text-sm font-bold text-gray-700 text-purple-500'">
Location:</pre
        >
        <pre class="text-xs bg-gray-100 text-gray-400 p-2 rounded-sm w-full">{{
          issue.location
        }}</pre>
      </div>
      <div class="flex flex-row my-2" v-if="issue.message">
        <pre class="text-xs bg-gray-100 text-gray-400 p-2 rounded-sm w-full">{{
          issue.message
        }}</pre>
      </div>
      <div class="border-b py-1 my-4">
        <IssuesSparkline :stats="firstStatValue" />
      </div>
    </div>
    <div
      class="flex flex-row align-middle absolute bottom-0 right-0 m-4 space-x-2 p-2 bg-gray-500/10 rounded-sm"
    >
      <UserBrowser :browser="browserName" />
      <UserPlatform :platform="issue.platform" />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    issue: {
      type: Object,
      required: true,
    },
  },
  methods: {
    readableDate(date) {
      return new Date(date).toDateString();
    },
    openInNewTab(url) {
      const win = window.open(url, "_blank");
      win.focus();
    },
  },
  computed: {
    priorityColor() {
      if (!this.issue) return null;
      switch (this.issue.priority) {
        case "high":
          return "bg-red-100 text-red-500";
        case "medium":
          return "bg-yellow-100 text-yellow-500";
        case "low":
          return "bg-green-100 text-green-500";
        default:
          return "bg-gray-100 text-gray-500";
      }
    },
    firstStatKey() {
        const keys = Object.keys(this.issue.stats);
        return keys.length > 0 ? keys[0] : null;
      },
      firstStatValue() {
        return this.issue.stats[this.firstStatKey] || null;
      },
  },
};
</script>
