<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\Widget;

class TopBrowsersWidget extends Widget {
  private $googleanalytics;

  public function __construct(GoogleAnalytics $googleanalytics) {
    $this->googleanalytics = $googleanalytics;
  }
  
  public function html() {
    $role_handels = $this->getConfig('roles_with_access');
    
    if ($this->googleanalytics->accessCheck($role_handels)) {
      return $this->view('widget-top-browsers');
    }
  }
}
