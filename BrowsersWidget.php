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

    return $this->view('widget-browsers', compact('chart'));
  }
}
