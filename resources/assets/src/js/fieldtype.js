/* global Vue */

Vue.component('google_analytics-fieldtype', {
  template: `<div class="ga-standalone">
              <google-analytics-line-chart
                title="Visitors & Page Views"
                endpoint="total-visitors-and-page-views"
                date-picker="show"
                label-position="bottom"
                :url="url" />
            </div>`,
  computed: {
    url() {
      let url = '';

      if (this.$parent.$parent.$parent.$parent.isPage) {
        const slug = this.$parent.$parent.$parent.$parent.formData.slug || 'new-page';
        url = this.$parent.$parent.$parent.$parent.extra.parent_url + '/' + slug;
        url = url.replace('//', '/');
      } else {
        url = this.$parent.$parent.$parent.$parent.entryUrl();
      }

      return encodeURI(url);
    },
  },
});
