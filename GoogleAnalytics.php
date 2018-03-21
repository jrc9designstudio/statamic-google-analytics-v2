<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\Extensible;

class GoogleAnalytics {
  public function getViewID() {
    return $this->getConfigInt('view_id');
  }
}
