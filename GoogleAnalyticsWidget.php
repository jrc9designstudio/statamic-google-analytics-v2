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
      return $this->view('widget', [
        'dates' => $this->getParam('dates', 'show'),
        'labels' => $this->getParam('labels', 'bottom'),
        'page_views' => $this->trans('cp.titles.page_views'),
      ]);
    }
  }
}
