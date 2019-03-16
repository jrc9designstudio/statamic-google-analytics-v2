<?php

namespace Statamic\Addons\GoogleAnalytics;

use Carbon;
use Analytics;
use JRC9DS\Analytics\Period;
use Statamic\Extend\Tags;
use Statamic\API\User;

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
      $anonymize_ip = $this->getConfig('anonymize_ip', false);
      $display_features = $this->getConfig('display_features', false);
      $async = $this->getConfig('async', false);
      $link_id = $this->getConfig('link_id', false);
      $beacon = $this->getConfig('beacon', false);

      $track_uid = $this->getConfig('track_uid', false);
      $ignore_admins = $this->getConfig('ignore_admins', false);
      $user = $track_uid ? User::getCurrent() : false;

      $debug = $this->getConfig('debug', false);
      $trace_debugging = $this->getConfig('trace_debugging', false);
      $disable_sending = $this->getConfig('disable_sending', false);

      return $this->view('tracking-code', compact(
        'tracking_id',
        'anonymize_ip',
        'async',
        'display_features',
        'link_id',
        'beacon',
        'track_uid',
        'ignore_admins',
        'user',
        'debug',
        'trace_debugging',
        'disable_sending')
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
    return (int)Analytics::performQuery(
      // This is the date that Google Analytics was started
      Period::create(new Carbon('2005-01-01'), new Carbon()),
      'ga:pageviews',
      [
        'filters' => 'ga:pagePath==' . $this->context['uri']
      ]
    )->totalsForAllResults['ga:pageviews'];
  }
}
