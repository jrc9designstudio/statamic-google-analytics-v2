<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\Widget;

class BrowsersWidget extends Widget {
  private $googleanalytics;

  public function __construct(GoogleAnalytics $googleanalytics) {
    $this->googleanalytics = $googleanalytics;
  }

  public function html() {
    if ($this->googleanalytics->accessCheck()) {
      return $this->view('widget-browsers', [
        'chart' => $this->getParam('chart', 'doughnut'),
        'labels' => $this->getParam('labels', 'right'),
        'dates' => $this->getParam('dates', 'show'),
        'top_browsers' => $this->trans('cp.titles.top_browsers'),
      ]);
    }
  }
}
