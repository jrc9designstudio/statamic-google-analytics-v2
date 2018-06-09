/* global Vue, GoogleAnalyticsMap, Flags, Statamic, document, ga */

Vue.component('google-analytics-map', {
  mixins: [GoogleAnalyticsMap, Flags, ga],
  data: () => ({
    x: -10000,
    y: -10000,
    width: 0,
    visible: false,
    country: '',
    countryData: {},
  }),
  computed: {
    infoStyle() {
      if (this.x > (this.width / 2)) {
        return `left: ${this.x - 240}px; top: ${this.y}px;`;
      } else {
        return `left: ${this.x}px; top: ${this.y}px;`;
      }
    },
    infoClass() {
      return 'google-maps-map-tooltip' + (this.visible ? ' open' : '');
    },
    stats() {
      return this.countryData[this.country] ? this.countryData[this.country] : {};
    },
    flag() {
      const flags = this.flags.filter((flag) => {
        return flag.name === this.country;
      });

      return (flags.length > 0) ? flags[0] : '';
    },
  },
  methods: {
    showInfo(event) {
      if (event.target.dataset.name) {
        this.visible = true;
        this.country = event.target.dataset.name;
      } else {
        this.hideInfo();
      }
    },
    hideInfo() {
      this.visible = false;
    },
    mouseTrack(event) {
      this.width = document.documentElement.clientWidth;
      this.x = event.clientX;
      this.y = event.clientY;
    },
    style(country) {
      const sessions = this.countryData[country] ? Number(this.countryData[country].sessions) : 0;
      let fill = '#f1f5f9';

      if (sessions > 9999) {
        fill = '#085b37';
      } else if (sessions > 999) {
        fill = '#37966d';
      } else if (sessions > 99) {
        fill = '#28cc85';
      } else if (sessions > 49) {
        fill = '#b5ffdd';
      } else if (sessions > 0) {
        fill = '#b9ead6';
      }

      return `fill:${fill};stroke:#32325d;fill-rule:evenodd;`;
    },
    getData() {
      this.$http.get(Statamic.cpRoot + '/addons/google-analytics/ajax/location' + this.dateQuery).success((data) => {
        this.countryData = data.rows;
      });
    },
  },
  ready() {
    this.hideInfo();

    this.getData();
  },
});
