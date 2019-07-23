/* global Vue, Fieldtype, Statamic */

Vue.component('google_analytics-fieldtype', {
  mixins: [
    Fieldtype,
  ],
  template: `<div class="ga-standalone">
              <google-analytics-line-chart
                v-if="data.access"
                :title="title"
                endpoint="total-visitors-and-page-views"
                date-picker="show"
                label-position="bottom"
                :url="url" />
                <div v-if="!data.access">
                  <p>{{ noAccess }}</p>
                </div>
            </div>`,
  computed: {
    url() {
      return encodeURI(this.$parent.$parent.$parent.$parent.uri);
    },
    title() {
      return Statamic.translations['addons.GoogleAnalytics::cp'].titles.page_views;
    },
    noAccess() {
      return Statamic.translations['addons.GoogleAnalytics::cp'].errors.no_access;
    },
  },
});
