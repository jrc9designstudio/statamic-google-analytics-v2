<?php

namespace Statamic\Addons\GoogleAnalytics;

use Carbon;
use Analytics;
use JRC9DS\Analytics\Period;
use Statamic\Extend\Tags;
use Statamic\API\User;
use Statamic\API\Cache;

class GoogleAnalyticsTags extends Tags {
  private $googleanalytics;

  public function __construct(GoogleAnalytics $googleanalytics) {
    $this->googleanalytics = $googleanalytics;
  }

  /**
   * The {{ google_analytics }} tag
   *
   * @return string|array
   */
  public function index() {
    $tracking_id = str_replace(' ', '', $this->getConfig('tracking_id', ''), $value);

    if (!empty($tracking_id)) {
      $enhanced_link_attribution_settings = $this->getConfig('enhanced_link_attribution_settings', false);

      if ($enhanced_link_attribution_settings) {
        $enhanced_link_attribution_settings = array_merge([
          'cookie_name' => '_ela',
          'duration' => 30,
          'levels' => 3
        ], $enhanced_link_attribution_settings[0]);
      }

      return $this->view(
        'tracking-code',
        [
          'tracking_id' => $tracking_id,
          'anonymize_ip' => $this->getConfig('anonymize_ip', false),
          'async' => $this->getConfig('async', false),
          'display_features' => $this->getConfig('display_features', false),
          'link_id' => $this->getConfig('link_id', false),
          'beacon' => $this->getConfig('beacon', false),
          'track_uid' => $this->getConfig('track_uid', false),
          'ignore_admins' => $this->getConfig('ignore_admins', false),
          'user' => $this->getConfig('track_uid', false) ? User::getCurrent() : false,
          'debug' => $this->getConfig('debug', false),
          'trace_debugging' => $this->getConfig('trace_debugging', false),
          'disable_sending' => $this->getConfig('disable_sending', false),
          'enhanced_link_attribution_settings' => $enhanced_link_attribution_settings,
        ]
      )->render();
    }

    return '<!-- Google Analytics Tracking code is not setup yet! -->';
  }

  /**
   * The {{ google_analytics:hits }} tag
   *
   * @return integer
   */
  public function hits() {
    $key = $this->context['url'];

    $data = $this->cache->get($key);

    if ($data == null) {
      $data = (int)Analytics::performQuery(
        // This is the date that Google Analytics was started
        Period::create(new Carbon('2005-01-01'), new Carbon()),
        'ga:pageviews',
        [
          'filters' => 'ga:pagePath==' . $key,
        ]
      )->totalsForAllResults['ga:pageviews'];
      $this->cache->put($key, $data, $this->getConfig('page_hits_cache_time', 1440));
    }

    return $data;
  }
}
