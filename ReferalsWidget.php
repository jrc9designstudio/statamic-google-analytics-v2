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

    return $this->view('widget-referals', compact('chart'));
  }
}
