<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\Widget;

class TopBrowsersWidget extends Widget {
  /**
   * The HTML that should be shown in the widget
   *
   * @return string
   */
  public function html() {
    return $this->view('widget-top-browsers');
  }
}
