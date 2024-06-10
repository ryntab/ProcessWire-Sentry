<template>
  <div class="flex flex-col space-y-4 p-2 animate-pulse">
    <div class="flex flex-col border-2 align-middle p-4 relative space-y-2">
      <div
        id="issue-toolbar"
        class="flex text-sm justify-between border-b pb-2"
      >
        <div class="flex flex-row flex-grow space-x-2 py-1 text-sm w-auto">
          <span class="px-2 bg-gray-100 rounded-md capitalize w-24"> </span>
          <span class="font-semibold truncate max-w-xs w-auto"></span>
        </div>
        <div class="flex flex-row justify-center align-middle space-x-1">
          <div
            class="px-2 py-1 bg-gray-100 rounded-md text-gray-400 space-x-1 align-middle w-16 h-5"
          ></div>
          <span
            class="px-2 py-1 bg-gray-100 rounded-md text-gray-400 space-x-1 align-middle w-16"
          ></span>
          <span
            class="px-2 py-1 bg-gray-100 rounded-md text-gray-400 space-x-1 align-middle w-16"
          ></span>
          <span
            class="px-2 py-1 bg-gray-100 rounded-md text-gray-400 space-x-1 align-middle w-16"
          ></span>
        </div>
      </div>
      <div class="px-2 py-4 relative">
        <div class="flex flex-col">
          <pre class="text-sm font-bold text-gray-700"><br></pre>
          <pre class="text-xs bg-gray-100 text-gray-400 p-2 rounded-sm w-full">
<br></pre>
        </div>
        <div class="border-b py-1 my-4">
          <div class="vue-apexcharts bg-orange-100" style="min-height: 40px">
          </div>
        </div>
      </div>
      <div
        class="flex flex-row align-middle absolute bottom-0 right-0 m-4 space-x-2 p-2 bg-gray-500/10 rounded-sm w-16"
      >
        <br />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  // Vue component logic here
};
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
