<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\API;

class GoogleAnalyticsAPI extends API {
  /**
   * Accessed by $this->api('GoogleAnalytics')->analyticsConfig() from other addons
   */
  public function analyticsConfig() {
    return [
      /*
       * The view id of which you want to display data.
       */
      'view_id' => $this->getConfigInt('view_id', null),

      /*
       * Path to the json file with service account credentials. Take a look at the README of this package
       * to learn how to get this file.
       */
      'service_account_credentials_json' => storage_path($this->getConfig('service_account_credentials_json', '../../site/settings/addons/google-analytics-credentials.json')),

      /*
       * The amount of minutes the Google API responses will be cached.
       * If you set this to zero, the responses won't be cached at all.
       */
      'cache_lifetime_in_minutes' => $this->getConfigInt('cache_lifetime_in_minutes', 24) * 60,

      /*
       * The directory where the underlying Google_Client will store it's cache files.
       */
      'cache_location' => storage_path($this->getConfig('cache_location', '../cache/laravel-google-analytics/google-cache/')),
    ];
  }
}
