<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\Widget;

class ReferralsWidget extends Widget {
  /**
   * The HTML that should be shown in the widget
   *
   * @return string
   */
  public function html() {
    return $this->view('widget-referrals', [
      'chart' => $this->getParam('chart', 'doughnut'),
      'labels' => $this->getParam('labels', 'right'),
      'dates' => $this->getParam('dates', 'show'),
      'top_referrers' => $this->trans('cp.titles.top_referrers'),
    ]);
  }
}
