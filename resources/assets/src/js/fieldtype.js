/* global Vue, Fieldtype */

Vue.component('google_analytics-fieldtype', {
  mixins: [Fieldtype],
  template: `<div class="ga-standalone">
              <google-analytics-line-chart
                v-if="data.access"
                title="Visitors & Page Views"
                endpoint="total-visitors-and-page-views"
                date-picker="show"
                label-position="bottom"
                :url="url" />
                <div v-if="!data.access">
                  <p>You do not have access to Google Analytics Stats.</p>
                </div>
            </div>`,
  computed: {
    url() {
      return encodeURI(this.$parent.$parent.$parent.$parent.uri);
    },
  },
});
