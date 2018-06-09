<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\Widget;

class GoogleAnalyticsWidget extends Widget {
  private $googleanalytics;

  public function __construct(GoogleAnalytics $googleanalytics) {
    $this->googleanalytics = $googleanalytics;
  }

  public function html() {
    if ($this->googleanalytics->accessCheck()) {
      $dates = $this->getParam('dates', 'show');
      $labels = $this->getParam('labels', 'bottom');

      return $this->view('widget', compact('dates', 'labels'));
    }
  }
}
