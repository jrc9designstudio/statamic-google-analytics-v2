/* global Vue, Chart, Statamic */

Chart.defaults.global.defaultFontColor = '#32325d';
Chart.defaults.global.responsive = true;
Chart.defaults.global.maintainAspectRatio = true;
Chart.defaults.global.responsiveAnimationDuration = 480;
Chart.defaults.global.defaultFontFamily = '-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen-Sans,Ubuntu,Cantarell,Helvetica Neue,sans-serif';
Chart.defaults.global.tooltips.backgroundColor = 'rgba(255,255,255,.85)';
Chart.defaults.global.tooltips.borderColor = 'rgba(50,50,93,.1)';
Chart.defaults.global.tooltips.borderWidth = 1;
Chart.defaults.global.tooltips.titleFontColor = '#32325d';
Chart.defaults.global.tooltips.bodyFontColor = '#32325d';
Chart.defaults.global.tooltips.displayColors = false;
Chart.defaults.global.tooltips.caretSize = 0;
Chart.defaults.global.tooltips.mode = 'index';
Chart.defaults.global.tooltips.xPadding = 10;
Chart.defaults.global.tooltips.yPadding = 10;

const EventBus = new Vue();

const ga = {
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
    url: {
      type: String,
    },
  },
  data: () => ({
    startDate: '',
    endDate: '',
    period: '',
    loading: true,
  }),
  computed: {
    showDatePicker() {
      return this.datePicker === 'show';
    },
    baseElClass() {
      return this.url ? 'ga-chart-standalone' : 'card flush';
    },
    dateQuery() {
      return '?startDate=' + this.startDate + '&endDate=' + this.endDate + '&period=' + this.period;
    },
    legends() {
      return Statamic.translations['addons.GoogleAnalytics::cp'].legends || {
        browser: 'Browser',
        country: 'Country',
        dom_interactive_time: 'DOM Interactive Time',
        domain_lookup_time: 'Domain Lookup Time',
        ms: 'ms',
        page_download_time: 'Page Download Time',
        page_load_time: 'Page Load Time',
        page_title: 'Page Title',
        page_views: 'Page Views',
        server_connection_time: 'Server Connection Time',
        server_response_time: 'Server Response Time',
        sessions: 'Sessions',
        url: 'URL',
        visitors: 'Visitors',
      };
    },
  },
  watch: {
    'startDate'(val, oldVal) {
      if (val !== oldVal && this.endDate !== '') {
        this.getData();
      }
    },
    'endDate'(val, oldVal) {
      if (val !== oldVal && this.startDate !== '') {
        this.getData();
      }
    },
    'period'(val, oldVal) {
      if (val !== oldVal && this.period !== '') {
        this.getData();
      }
    },
  },
  ready() {
    EventBus.$on('set-start-date', (startDate) => {
      this.startDate = startDate;
      this.period = '';
    });

    EventBus.$on('set-end-date', (endDate) => {
      this.endDate = endDate;
      this.period = '';
    });

    EventBus.$on('set-period', (period) => {
      this.period = period;
      this.startDate = '';
      this.endDate = '';
    });
  },
};

const gaChart = {
  data: () => ({
    points: [],
    labels: [],
    colours: [],
    chart: null,
    ctx: null,
  }),
  watch: {
    'labels'(val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.labels = val;
        this.chart.update();
      }
    },
    'colours'(val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.datasets[0].backgroundColor = val;
        this.chart.update();
      }
    },
  },
  computed: {
    query() {
      return this.dateQuery + '&url=' + (this.url ? this.url : '');
    },
  },
  methods: {
    getData() {
      this.$http.get(Statamic.cpRoot + '/addons/google-analytics/ajax/' + this.endpoint + this.query).success((data) => {
        this.loading = false;
        this.labels = data.labels;
        this.points = data.points;

        if (data.colours) {
          this.colours = data.colours;
        }
      });
    },
  },
  ready() {
    this.makeChart();

    this.getData();
  },
};

const gaTable = {
  data: () => ({
    rows: [],
    labels: [],
  }),
};

Vue.component('google-analytics-date-picker', {
  props: {
    scope: {
      type: String,
    },
  },
  data: () => ({
    startDate: '',
    endDate: '',
    period: '1month',
  }),
  computed: {
    isDateSupported() {
      var input = document.createElement('input');
      var value = 'a';
      input.setAttribute('type', 'date');
      input.setAttribute('value', value);
      return (input.value !== value);
    },
  },
  watch: {
    'startDate'(val, oldVal) {
      if (val !== oldVal && this.scope === 'page') {
        EventBus.$emit('set-start-date', this.startDate);
      } else {
        this.$parent.$data.startDate = this.startDate;
        this.$parent.$data.period = '';
      }
    },
    'endDate'(val, oldVal) {
      if (val !== oldVal && this.scope === 'page') {
        EventBus.$emit('set-end-date', this.endDate);
      } else {
        this.$parent.$data.endDate = this.endDate;
        this.$parent.$data.period = '';
      }
    },
    'period'(val, oldVal) {
      if (val !== oldVal && this.scope === 'page') {
        EventBus.$emit('set-period', this.period);
      } else {
        this.$parent.$data.period = this.period;
        this.$parent.$data.startDate = '';
        this.$parent.$data.endDate = '';
      }
    },
  },
  template: `<div class="controls flexy">
              <label v-if='period === "custom"' class="sr-only">Start Date:&nbsp;&nbsp;</label>
              <input v-if='period === "custom"' type="date" v-model="startDate" />
              <span v-if='period === "custom"'>&nbsp;-&nbsp;</span>
              <label v-if='period === "custom"' class="sr-only">&nbsp;&nbsp;End Date:&nbsp;&nbsp;</label>
              <input v-if='period === "custom"' type="date" v-model="endDate" />
              <label class='period-label' v-if='period !== "custom"'>
                <input type='radio' value='1week' name='period' v-model='period' />
                <span class='text'>1w</span>
              </label>
              <label class='period-label' v-if='period !== "custom"'>
                <input type='radio' value='2weeks' name='period' v-model='period' />
                <span class='text'>2w</span>
              </label>
              <label class='period-label' v-if='period !== "custom"'>
                <input type='radio' value='1month' name='period' v-model='period' />
                <span class='text'>1m</span>
              </label>
              <label class='period-label' v-if='period !== "custom"'>
                <input type='radio' value='3months' name='period' v-model='period' />
                <span class='text'>3m</span>
              </label>
              <label class='period-label' v-if='period !== "custom"'>
                <input type='radio' value='6months' name='period' v-model='period' />
                <span class='text'>6m</span>
              </label>
              <label class='period-label' v-if='period !== "custom"'>
                <input type='radio' value='1year' name='period' v-model='period' />
                <span class='text'>1y</span>
              </label>
              <label class='period-label' v-if='isDateSupported && period !== "custom"'>
                <input type='radio' value='custom' name='period' v-model='period' />
                <span  class='icon icon-calendar'></span>
              </label>
              <label class='period-label' v-if='isDateSupported && period === "custom"'>
                <input type='radio' value='1week' name='period' v-model='period' />
                <span  class='icon icon-circle-with-cross'></span>
              </label>
            </div>`,
});

Vue.component('google-analytics-line-chart', {
  mixins: [
    ga,
    gaChart,
  ],
  template: `<div :class="baseElClass">
              <div class="head">
                <h1 class="fill">{{ title }}</h1>
                <google-analytics-date-picker v-if="showDatePicker" />
              </div>
              <div class="ga-canvas-container body">
                <canvas v-el:chart width="200" height="100%"></canvas>
              </div>
            </div>`,
  watch: {
    'points'(val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.datasets[0].data = val[0];
        this.chart.data.datasets[1].data = val[1];
        this.chart.update();
      }
    },
  },
  methods: {
    makeChart() {
      this.ctx = this.$els.chart.getContext('2d');
      this.chart = new Chart(this.ctx, {
        type: 'line',
        labels: [],
        data: {
          datasets: [
            {
              label: this.legends.page_views,
              data: [],
              backgroundColor: 'rgba(53, 168, 227, 0.2)',
              borderColor: 'rgba(53, 168, 227, 0.2)',
              borderWidth: 1,
              pointRadius: 0,
            },
            {
              label: this.legends.visitors,
              data: [],
              backgroundColor: 'rgba(255, 38, 158, 0.2)',
              borderColor: 'rgba(255, 38, 158, 0.2)',
              borderWidth: 1,
              pointRadius: 0,
            },
          ],
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            intersect: false,
          },
          scales: {
            yAxes: [
              {
                gridLines: {
                  display: false,
                },
                ticks: {
                  min: 0,
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
            position: this.labelPosition,
            labels: {
              padding: 15,
            },
          },
        },
      });
    },
  },
});

Vue.component('google-analytics-doughnut-chart', {
  mixins: [
    ga,
    gaChart,
  ],
  template: `<div class="card flush">
              <div class="head">
                <h1 class="fill">{{ title }}</h1>
                <google-analytics-date-picker v-if="showDatePicker"></google-analytics-date-picker>
              </div>
              <div class="body">
                <canvas v-el:chart width="200" height="150"></canvas>
              </div>
            </div>`,
  watch: {
    'points'(val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.datasets[0].data = val;
        this.chart.update();
      }
    },
  },
  methods: {
    makeChart() {
      this.ctx = this.$els.chart.getContext('2d');
      this.chart = new Chart(this.ctx, {
        type: 'doughnut',
        labels: [],
        data: {
          datasets: [
            {
              data: [],
              backgroundColor: [
                'rgb(36, 191, 90)',
                '#ff9400',
                'rgb(90, 200, 250)',
              ],
            },
          ],
          labels: [],
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
        },
      });
    },
  },
});

Vue.component('google-analytics-bar-chart', {
  mixins: [
    ga,
    gaChart,
  ],
  template: `<div class="card flush">
              <div class="head">
                <h1 class="fill">{{ title }}</h1>
                <google-analytics-date-picker v-if="showDatePicker"></google-analytics-date-picker>
              </div>
              <div class="body">
                <canvas v-el:chart width="200" height="100"></canvas>
              </div>
            </div>`,
  watch: {
    'points'(val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.datasets[0].data = val;
        this.chart.update();
      }
    },
  },
  methods: {
    makeChart() {
      this.ctx = this.$els.chart.getContext('2d');
      this.chart = new Chart(this.ctx, {
        type: 'bar',
        labels: [],
        data: {
          datasets: [
            {
              data: [],
              backgroundColor: [
                'rgb(36, 191, 90)',
                '#ff9400',
                'rgb(90, 200, 250)',
              ],
            },
          ],
          labels: [],
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
        },
      });
    },
  },
});

Vue.component('google-analytics-horizontal-bar-chart', {
  mixins: [
    ga,
    gaChart,
  ],
  template: `<div class="card flush">
              <div class="head">
                <h1 class="fill">{{ title }}</h1>
                <google-analytics-date-picker v-if="showDatePicker"></google-analytics-date-picker>
              </div>
              <div class="body">
                <canvas v-el:chart width="200" height="100"></canvas>
              </div>
            </div>`,
  watch: {
    'points'(val, oldVal) {
      if (val !== oldVal) {
        this.chart.data.datasets[0].data = val;
        this.chart.update();
      }
    },
  },
  methods: {
    makeChart() {
      this.ctx = this.$els.chart.getContext('2d');
      this.chart = new Chart(this.ctx, {
        type: 'horizontalBar',
        labels: [],
        data: {
          datasets: [
            {
              data: [],
              backgroundColor: [
                'rgb(36, 191, 90)',
                '#ff9400',
                'rgb(90, 200, 250)',
              ],
            },
          ],
          labels: [],
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
        },
      });
    },
  },
});

Vue.component('google-analytics-table', {
  mixins: [
    ga,
    gaTable,
  ],
  template: `<div class="card flush">
              <div class="head">
                <h1 class="fill">{{ title }}</h1>
                <google-analytics-date-picker v-if="showDatePicker"></google-analytics-date-picker>
              </div>
              <div class="body ga-table-scrollable">
                <table class="ga-table">
                  <thead>
                    <tr>
                      <td v-for="label in labels"><strong>{{ label }}</strong></td>
                    <tr>
                  </thead>
                  <tbody>
                    <tr v-for="row in rows">
                      <td v-for="data in row">{{ data }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>`,
  computed: {
    query() {
      return '?startDate=' + this.startDate + '&endDate=' + this.endDate;
    },
  },
  methods: {
    getData() {
      this.$http.get(Statamic.cpRoot + '/addons/google-analytics/ajax/' + this.endpoint + this.query).success(function(data) {
        this.loading = false;
        this.labels = data.labels;
        this.rows = data.rows;
      });
    },
  },
  ready() {
    this.getData();
  },
});
