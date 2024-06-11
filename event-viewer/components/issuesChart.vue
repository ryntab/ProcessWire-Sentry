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
import moment from "moment";

export default {
  props: {
    data: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      series: [],
      options: {
        markers: {
          size: 0,
          hover: {
            size: 5,
            sizeOffset: 0,
          },
        },
        theme: {
          mode: "light",
        },
        chart: {
          fontFamily: "Helvetica, Arial, sans-serif",
          type: "area",
          height: 250,
          sparkline: {
            enabled: false,
          },
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
              return value + "";
            },
          },
        },
        fill: {
          type: "gradient",
          gradient: {
            shade: "light",
            type: "vertical",
            shadeIntensity: 1,
            inverseColors: false,
            opacityFrom: 1,
            opacityTo: 0,
            stops: [0, 100],
          },
        },
        dataLabels: {
          enabled: false,
          colors: ["#F44336", "#E91E63", "#9C27B0"],
        },
        stroke: {
          width: 2,
          curve: "straight",
        },
        grid: {
          show: false,
        },
        title: {
          show: false,
        },
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
  mounted() {
    this.updateSeries();
  },
  watch: {
    data: {
      handler() {
        this.updateSeries();
      },
      deep: true,
    },
  },
  methods: {
    updateSeries() {
      this.series = this.fillMissingTimes(this.data);
    },
    fillMissingTimes(data) {
      const colorMapping = {
        fatal: "#f44336",
        error: "#ffc43a",
        warning: "#FFC300",
        info: "#3498DB",
        debug: "#2ECC71",
      };

      console.log("Original data:", data);

      const groupedData = data.reduce((acc, item) => {
        const date = moment(item.firstSeen).startOf("day").toISOString();
        const count = 1; // Each item represents one event
        const level = item.level; // Group by level

        if (!acc[level]) {
          acc[level] = {};
        }
        if (!acc[level][date]) {
          acc[level][date] = 0;
        }
        acc[level][date] += count; // Sum up the events for each day and level

        return acc;
      }, {});

      // Determine the start and end dates
      const allDates = Object.values(groupedData).flatMap((dates) =>
        Object.keys(dates)
      );
      const startDate = moment.min(allDates.map((d) => moment(d)));
      const endDate = moment.max(allDates.map((d) => moment(d)));
      const filledData = [];

      Object.keys(groupedData).forEach((level) => {
        let currentDate = startDate.clone();
        const seriesData = [];
        while (currentDate.isSameOrBefore(endDate)) {
          const dateString = currentDate.toISOString();
          const count = groupedData[level][dateString] || 0;
          seriesData.push({ x: dateString, y: count });

          currentDate = currentDate.add(1, "day"); // Adjust the interval as needed
        }
        filledData.push({
          name: level,
          data: seriesData,
          color: colorMapping[level],
        });
      });

      return filledData;
    },
  },
};
</script>

<style>
.apexcharts-svg {
  background-color: transparent !important;
}
.apexcharts-text apexcharts-xaxis-label {
  color: #dadada !important;
}
</style>
