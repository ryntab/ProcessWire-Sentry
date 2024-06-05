<template>
  <div class="flex flex-col border-2 align-middle p-4 relative" >
    <div class="flex flex-row space-x-2 border-b py-1 text-sm ">
      <span class="px-2 bg-orange-100 rounded-md text-orange-600"><pre>{{ readableDate }}</pre></span>
      <span class="font-semibold">..{{ event.title }}</span>
      <span class="px-2 bg-gray-100 rounded-md text-gray-400">ID: {{ event.eventID }}</span>
    </div>
    <div class="px-2 py-4 relative">
      <div class="flex flex-col" v-if="event.culprit">
        <pre class="text-sm font-bold text-gray-700 text-orange-500'">
Culprit:</pre
        >
        <pre class="text-xs bg-gray-100 text-gray-400 p-2 rounded-sm w-full">{{
          event.culprit
        }}</pre>
      </div>
      <div class="flex flex-col" v-if="event.location">
        <pre class="text-sm font-bold text-gray-700 text-orange-500'">
Location:</pre
        >
        <pre class="text-xs bg-gray-100 text-gray-400 p-2 rounded-sm w-full">{{
          event.location
        }}</pre>
      </div>
      <div class="flex flex-row my-2" v-if="event.message">
        <pre class="text-xs bg-gray-100 text-gray-400 p-2 rounded-sm w-full">{{
          event.message
        }}</pre>
      </div>
    </div>
    <div class="flex flex-row align-middle absolute bottom-0 right-0 m-4 space-x-2 p-2 bg-gray-500/10 rounded-sm">
      <UserBrowser :browser="browserName" />
      <UserPlatform :platform="event.platform" />
    </div>
  </div>
</template>
<script>
export default {
  props: {
    event: {
      type: Object,
      required: true,
    },
  },
  computed: {
    browserName() {
      if (!this.event) return null;
      return this.event.tags.find((tag) => tag.key === "browser.name").value;
    },
    readableDate() {
      if (!this.event) return null;
      return new Date(this.event.dateCreated).toDateString();
    },
  },
};
</script>
./userAgent.vue
