
import VueApexCharts from "vue3-apexcharts";
export default defineNuxtPlugin((nuxtApp) => {
  // If client side

  nuxtApp.vueApp.use(VueApexCharts);
  window.Apex.chart = { fontFamily: "Circular-Book, Arial, sans-serif" };

});

