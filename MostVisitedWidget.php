<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\Widget;

class MostVisitedWidget extends Widget {
  private $googleanalytics;

  public function __construct(GoogleAnalytics $googleanalytics) {
    $this->googleanalytics = $googleanalytics;
  }

  public function html() {
    $role_handels = $this->getConfig('roles_with_access');
    
    if ($this->googleanalytics->accessCheck($role_handels)) {
      $dates = $this->getParam('dates', 'show');
  
      return $this->view('widget-most-visited', compact('dates'));
    }
  }
}
