<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\Widget;

class MostVisitedWidget extends Widget {
  private $googleanalytics;

  public function __construct(GoogleAnalytics $googleanalytics) {
    $this->googleanalytics = $googleanalytics;
  }

  public function html() {
    if ($this->googleanalytics->accessCheck()) {
      return $this->view('widget-most-visited', [
        'dates' => $this->getParam('dates', 'show'),
        'most_visited_pages' => $this->trans('cp.titles.most_visited_pages'),
      ]);
    }
  }
}
