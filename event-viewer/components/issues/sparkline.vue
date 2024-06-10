<template>
  <apexchart type="area" :options="chartOptions" :series="series" height="40" />
</template>

<script>
export default {
  props: {
    stats: {
      type: Array,
      required: true,
    },
  },
  computed: {
    series() {
      return [
        {
          name: "Issue Count",
          data: this.stats.map((point) => point[1]),
          color: '#FFB833' // Explicitly set the color here if needed
        },
      ];
    },
    chartOptions() {
      return {
        chart: {
          type: "area",
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          width: 2,
          colors: ["#FFB833"], // Change the line color here
        },
        fill: {
          type: "gradient",
          gradient: {
            shade: "light",
            type: "vertical",
            shadeIntensity: 1,
            gradientToColors: ["#FFB833"],
            inverseColors: false,
            opacityFrom: 0.8,
            opacityTo: 0.1,
            stops: [0, 100],
          },
        },
        tooltip: {
          enabled: true,
          theme: "light",
        },
        yaxis: {
          show: false,
        },
        xaxis: {
          labels: {
            show: true,
            formatter: (value) => {
              const date = new Date(value * 1000);
              return date.toLocaleDateString();
            },
          },
          tooltip: {
            enabled: false,
          },
          type: "datetime",
          categories: this.stats.map((point) => point[0] * 1000),
        },
      };
    },
  },
};
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
