<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\Widget;

class ReferalsWidget extends Widget {
  /**
   * The HTML that should be shown in the widget
   *
   * @return string
   */
  public function html() {
    $chart = $this->getParam('chart', 'doughnut');
    $labels = $this->getParam('labels', 'right');
    $dates = $this->getParam('dates', 'show');

    return $this->view('widget-referals', compact('chart', 'labels', 'dates'));
  }
}
