<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\Fieldtype;

class GoogleAnalyticsFieldtype extends Fieldtype {
  private $googleanalytics;

  public function __construct(GoogleAnalytics $googleanalytics) {
    $this->googleanalytics = $googleanalytics;
  }

  /**
   * The blank/default value
   *
   * @return array
   */
  public function blank() {
      return null;
  }

  /**
   * Pre-process the data before it gets sent to the publish page
   *
   * @param mixed $data
   * @return array|mixed
   */
  public function preProcess($data) {
      return [
        'access' => $this->googleanalytics->accessCheck(),
      ];
  }

  /**
   * Process the data before it gets saved
   *
   * @param mixed $data
   * @return array|mixed
   */
  public function process($data) {
      return null;
  }
}
