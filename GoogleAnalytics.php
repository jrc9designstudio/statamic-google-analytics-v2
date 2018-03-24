<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\API\Role;
use Statamic\API\User;
use Statamic\Extend\Extensible;

class GoogleAnalytics {
  public function getViewID() {
    return $this->getConfigInt('view_id');
  }
  
  public function accessCheck($role_handels) {
    $user = User::getCurrent();
      
    if (sizeof($role_handels) < 1 || ($user && $user->isSuper())) {
      return true;
    }
    
    foreach($role_handels as $role_handel) {
      $role = Role::whereHandle($role_handel);
      
      if ($user->hasRole($role)) {
        return true;
      }
    }
    
    return false;
  }
}
