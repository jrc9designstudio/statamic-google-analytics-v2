<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\Widget;

class BrowsersWidget extends Widget {
  /**
   * The HTML that should be shown in the widget
   *
   * @return string
   */
  public function html() {
    $chart = $this->getParam('chart', 'doughnut');
    $labels = $this->getParam('labels', 'right');
    $dates = $this->getParam('dates', 'show');

    return $this->view('widget-browsers', compact('chart', 'labels', 'dates'));
  }
}
