<template>
  <Icon :name="browserIcon" color="black" size="20" />
</template>
<script>
export default {
  props: {
    browser: {
      type: String,
      required: true,
    },
  },
  computed: {
    browserIcon() {
      switch (this.browser) {
        case "Google Chrome":
          return "logos:chrome";
        case "Chrome":
          return "logos:chrome";
        default:
          return "logos:firefox";
      }
    },
  },
};
</script>
