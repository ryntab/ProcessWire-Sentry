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
        case "Firefox":
          return "logos:firefox";
        case "Brave":
          return "logos:brave";
        case "Microsoft Edge":
          return "logos:microsoft-edge";
        case "Safari":
          return "logos:safari";
        default:
          return "logos:firefox";
      }
    },
  },
};
</script>
