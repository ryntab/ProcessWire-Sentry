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
      series: [
        {
          name: "Event Count",
          data: this.fillMissingTimes(this.data),
          color: "#e69138",
        },
      ],
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
          height: 350,
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
  methods: {
  fillMissingTimes(data) {
    // Parse the data to keep the original precision of timestamps and group by hour
    const groupedData = data.reduce((acc, item) => {
      const date = moment(item.dateCreated).startOf('hour').toISOString();
      const count = 1; // Each item represents one event

      if (!acc[date]) {
        acc[date] = 0;
      }
      acc[date] += count; // Sum up the events for each hour

      return acc;
    }, {});

    // Convert the grouped data into an array of objects
    const formattedData = Object.keys(groupedData).map((key) => ({
      x: key,
      y: groupedData[key],
    }));

    if (formattedData.length === 0) return [];

    // Determine the start and end dates
    const startDate = moment.min(formattedData.map((d) => moment(d.x)));
    const endDate = moment.max(formattedData.map((d) => moment(d.x)));
    const filledData = [];

    console.log("Start date:", startDate.toISOString());
    console.log("End date:", endDate.toISOString());

    let currentDate = startDate.clone();
    while (currentDate.isSameOrBefore(endDate)) {
      const dateString = currentDate.toISOString();
      const existingData = formattedData.find((d) => d.x === dateString);

      if (existingData) {
        filledData.push(existingData);
      } else {
        filledData.push({ x: dateString, y: 0 });
      }

      currentDate = currentDate.add(1, 'hour'); // Adjust the interval as needed
    }

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
