<template>
    <div class="w-full">
      <apexchart
        :key="series"
        height="250"
        width="100%"
        :options="options"
        :series="series"
      ></apexchart>
    </div>
</template>
<script>
export default {
  props: {
    data: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      series: [
        {
          name: "Event Count",
          data: this.data.map((item) => ({
            x: item.dateCreated,
            y: item.groupID,
          })),
          color: "#e69138",
        },
      ],
      options: {
        markers: {
          size: 0, // Adjust the size to your preference
          hover: {
            size: 5, // Size of the marker when hovered
            sizeOffset: 0,
          },
        },
        theme: {
          mode: "light",
        },
        chart: {
          fontFamily: "Helvetica, Arial, sans-serif",
          type: "area",
          height: 350,
          fontFamily: "Inter, sans-serif",
          dropShadow: {
            enabled: false,
          },
          toolbar: {
            show: false,
          },
          zoom: {
            enabled: false,
          },
        },
        tooltip: {
          shared: true,
          intersect: false,
          enabled: true,
          y: {
            formatter: function (value) {
              return value + "°F";
            },
          },
        },
        fill: {
          type: "gradient",
          gradient: {
            opacityFrom: 0.85,
            opacityTo: 0.05,
            shade: "#e69138",
            gradientToColors: ["#e69138"],
          },
        },
        dataLabels: {
          enabled: false,
          colors: ["#F44336", "#E91E63", "#9C27B0"],
        },
        stroke: {
          width: 4,
          color: "#6fa8dc",
        },
        grid: {
          show: false,
        },
        title: {
          text: "Error Events",
          align: "left",
        },
        subtitle: {
          text: "Events sent to Sentry.io",
          align: "left",
        },
        labels: [
          ...this.data.map((item) => item.time),
        ],
        xaxis: {
          show: false,
          type: "datetime",
        },
        yaxis: {
          show: false,
          opposite: true,
        },
        legend: {
          horizontalAlign: "left",
        },
      },
    };
  },
};
</script>
<style >
.apexcharts-svg {
  background-color: transparent !important;
}
.apexcharts-text apexcharts-xaxis-label {
  color: #dadada !important;
}
</style>
