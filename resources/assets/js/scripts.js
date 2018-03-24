/* global Vue, Chart, $, Statamic */

var EventBus = new Vue();

var ga = {
  props: {
    title: {
      type: String,
      required: true,
    },
    endpoint: {
      type: String,
      required: true,
    },
    labelPosition: {
      type: String,
    },
    datePicker: {
      type: String,
    },
  },
  data: function() {
    return {
      startDate: '',
      endDate: '',
    };
  },
  computed: {
    showDatePicker: function() {
      return this.datePicker === 'show';
    },
  },
  watch: {
    'startDate': function(val, oldVal) {
      if (val !== oldVal && this.endDate !== '') {
        this.getData();
      }
    },
    'endDate': function(val, oldVal) {
      if (val !== oldVal && this.startDate !== '') {
        this.getData();
      }
    },
  },
  ready: function() {
    var that = this;

    EventBus.$on('set-start-date', function(startDate) {
      that.startDate = startDate;
    });

    EventBus.$on('set-end-date', function(endDate) {
      that.endDate = endDate;
    });
  },
};

var ga_chart = {
  data: function() {
    return {
      points: [],
      labels: [],
      colours: [],
      chart: undefined,
      ctx: undefined,
    };
  },
  watch: {
    'labels': function(val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.labels = val;
        this.chart.update();
      }
    },
    'colours': function(val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.datasets[0].backgroundColor = val;
        this.chart.update();
      }
    },
  },
  methods: {
    getData: function() {
      this.$http.get(Statamic.cpRoot + '/addons/google-analytics/ajax/' + this.endpoint + '?startDate=' + this.startDate + '&endDate=' + this.endDate).success(function(data) {
        this.labels = data.labels;
        this.points = data.points;

        if (data.colours) {
          this.colours = data.colours;
        }
      });
    },
  },
  ready: function() {
    this.makeChart();

    this.getData();
  },
};

var ga_table = {
  data: function() {
    return {
      rows: [],
      labels: [],
    };
  },
};

Vue.component('google-analytics-date-picker', {
  props: {
    scope: {
      type: String,
    },
  },
  data: function() {
    return {
      startDate: '',
      endDate: '',
    };
  },
  watch: {
    'startDate': function(val, oldVal) {
      if (this.scope === 'page') {
        EventBus.$emit('set-start-date', this.startDate);
      } else {
        this.$parent.$data.startDate = this.startDate;
      }
    },
    'endDate': function(val, oldVal) {
      if (this.scope === 'page') {
        EventBus.$emit('set-end-date', this.endDate);
      } else {
        this.$parent.$data.endDate = this.endDate;
      }
    },
  },
  template: '<div class="controls flexy">' +
              '<label class="sr-only">Start Date:&nbsp;&nbsp;</label>' +
              '<input type="date" v-model="startDate" />' +
              '&nbsp;-&nbsp;<label class="sr-only">&nbsp;&nbsp;End Date:&nbsp;&nbsp;</label>' +
              '<input type="date" v-model="endDate" />' +
            '</div>',
});

Vue.component('google-analytics-line-chart', {
  mixins: [ga, ga_chart],
  template: '<div class="card flush">' +
              '<div class="head">' +
                '<h1 class="fill">{{ title }}</h1>' +
                '<google-analytics-date-picker v-if="showDatePicker"></google-analytics-date-picker>' +
              '</div>' +
              '<div class="body">' +
                '<canvas v-el:chart width="200" height="50"></canvas>' +
              '</div>' +
            '</div>',
  watch: {
    'points': function (val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.datasets[0].data = val[0];
        this.chart.data.datasets[1].data = val[1];
        this.chart.update();
      }
    },
  },
  methods: {
    makeChart: function() {
      this.ctx = this.$els.chart.getContext('2d');
      this.chart = new Chart(this.ctx, {
        type: 'line',
        labels: [],
        data: {
          datasets: [
            {
              label: 'Page Views',
              data: [],
              backgroundColor: 'rgba(53, 168, 227, 0.2)',
              borderColor: 'rgba(53, 168, 227, 0.4)',
              borderWidth: 1,
            },
            {
              label: 'Visitors',
              data: [],
              backgroundColor: 'rgba(255, 38, 158, 0.2)',
              borderColor: 'rgba(255, 38, 158, 0.4)',
              borderWidth: 1
            }
          ]
        },
        options: {
          scales: {
            yAxes: [
              {
                gridLines: {
                  display: false,
                },
              },
            ],
            xAxes: [
              {
                gridLines: {
                  display: false,
                },
              },
            ],
          },
          layout: {
            padding: {
              top: 30,
              right: 15,
              bottom: 20,
              left: 15,
            },
          },
          legend: {
            position: 'bottom',
            labels: {
              padding: 15,
            }
          },
        },
      });
    },
  },
});

Vue.component('google-analytics-doughnut-chart', {
  mixins: [ga, ga_chart],
  template: '<div class="card flush">' +
              '<div class="head">' +
                '<h1 class="fill">{{ title }}</h1>' +
                '<google-analytics-date-picker v-if="showDatePicker"></google-analytics-date-picker>' +
              '</div>' +
              '<div class="body">' +
                '<canvas v-el:chart width="200" height="150"></canvas>' +
              '</div>' +
            '</div>',
  watch: {
    'points': function (val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.datasets[0].data = val;
        this.chart.update();
      }
    },
  },
  methods: {
    makeChart: function() {
      this.ctx = this.$els.chart.getContext('2d');
      this.chart = new Chart(this.ctx, {
        type: 'doughnut',
        labels: [],
        data: {
          datasets: [{
            data: [],
            backgroundColor: [
              'rgb(36, 191, 90)',
              '#ff9400',
              'rgb(90, 200, 250)',
            ]
          }],
          labels: []
        },
        options: {
          layout: {
            padding: {
              top: 30,
              right: 15,
              bottom: 20,
              left: 15,
            },
          },
          legend: {
            position: this.labelPosition,
          },
        }
      });
    },
  },
});

Vue.component('google-analytics-bar-chart', {
  mixins: [ga, ga_chart],
  template: '<div class="card flush">' +
              '<div class="head">' +
                '<h1 class="fill">{{ title }}</h1>' +
                '<google-analytics-date-picker v-if="showDatePicker"></google-analytics-date-picker>' +
              '</div>' +
              '<div class="body">' +
                '<canvas v-el:chart width="200" height="100"></canvas>' +
              '</div>' +
            '</div>',
  watch: {
    'points': function (val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.datasets[0].data = val;
        this.chart.update();
      }
    },
  },
  methods: {
    makeChart: function() {
      this.ctx = this.$els.chart.getContext('2d');
      this.chart = new Chart(this.ctx, {
        type: 'bar',
        labels: [],
        data: {
          datasets: [{
            data: [],
            backgroundColor: [
              'rgb(36, 191, 90)',
              '#ff9400',
              'rgb(90, 200, 250)',
            ]
          }],
          labels: []
        },
        options: {
          scales: {
            yAxes: [
              {
                gridLines: {
                  display: false,
                },
              },
            ],
            xAxes: [
              {
                gridLines: {
                  display: false,
                },
              },
            ],
          },
          layout: {
            padding: {
              top: 30,
              right: 15,
              bottom: 20,
              left: 15,
            },
          },
          legend: {
            display: false,
          },
        }
      });
    },
  },
});

Vue.component('google-analytics-horizontal-bar-chart', {
  mixins: [ga, ga_chart],
  template: '<div class="card flush">' +
              '<div class="head">' +
                '<h1 class="fill">{{ title }}</h1>' +
                '<google-analytics-date-picker v-if="showDatePicker"></google-analytics-date-picker>' +
              '</div>' +
              '<div class="body">' +
                '<canvas v-el:chart width="200" height="100"></canvas>' +
              '</div>' +
            '</div>',
  watch: {
    'points': function (val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.datasets[0].data = val;
        this.chart.update();
      }
    },
  },
  methods: {
    makeChart: function() {
      this.ctx = this.$els.chart.getContext('2d');
      this.chart = new Chart(this.ctx, {
        type: 'horizontalBar',
        labels: [],
        data: {
          datasets: [{
            data: [],
            backgroundColor: [
              'rgb(36, 191, 90)',
              '#ff9400',
              'rgb(90, 200, 250)',
            ]
          }],
          labels: []
        },
        options: {
          scales: {
            yAxes: [
              {
                gridLines: {
                  display: false,
                },
              },
            ],
            xAxes: [
              {
                gridLines: {
                  display: false,
                },
              },
            ],
          },
          layout: {
            padding: {
              top: 30,
              right: 15,
              bottom: 20,
              left: 15,
            },
          },
          legend: {
            display: false,
          },
        }
      });
    },
  },
});

Vue.component('google-analytics-table', {
  mixins: [ga, ga_table],
  template: '<div class="card flush">' +
              '<div class="head">' +
                '<h1 class="fill">{{ title }}</h1>' +
                '<google-analytics-date-picker v-if="showDatePicker"></google-analytics-date-picker>' +
              '</div>' +
              '<div class="body">' +
                '<table class="ga-table">' +
                  '<thead>' +
                    '<tr>' +
                      '<td v-for="label in labels">{{ label }}</td>' +
                    '<tr>' +
                  '</thead>' +
                  '<tbody>' +
                    '<tr v-for="row in rows">' +
                      '<td v-for="data in row">{{ data }}</td>' +
                    '</tr>' +
                  '</tbody>' +
                '</table>' +
              '</div>' +
            '</div>',
  methods: {
    getData: function() {
      this.$http.get(Statamic.cpRoot + '/addons/google-analytics/ajax/' + this.endpoint +'?startDate=' + this.startDate + '&endDate=' + this.endDate).success(function(data) {
        this.labels = data.labels;
        this.rows = data.rows;
      });
    },
  },
  ready: function() {
    this.getData();
  },
});
