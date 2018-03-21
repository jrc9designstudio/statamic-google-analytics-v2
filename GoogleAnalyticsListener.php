<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\API\Nav;
use Statamic\API\User;
use Statamic\Extend\Listener;

class GoogleAnalyticsListener extends Listener {
  public $events = [
    'cp.nav.created' => 'addNavItems',
    'cp.add_to_head' => 'addAssets',
  ];

  /**
   * @param \Statamic\CP\Navigation\Nav $nav
   */
  public function addNavItems($nav) {
    $user = User::getCurrent();

    if ($user && $user->isSuper()) {
      // Create the first level navigation item
      $store = Nav::item('Google Analytics')->route('index')->icon('line-graph');

      // Add second level navigation items to it
      $store->add(function ($item) {
        $item->add(Nav::item('Browsers')->route('google-analytics.browsers'));
        $item->add(Nav::item('Referals')->route('google-analytics.referals'));
        $item->add(Nav::item('Settings')->route('addon.settings', 'google-analytics'));
      });

      // Finally, add our first level navigation item
      // to the navigation under the 'tools' section.
      $nav->addTo('tools', $store);
    }
  }

  /**
   * Initialize Aggregator assets
   * @return string css & js link
   */
  public function addAssets() {
    $html = $this->js->tag('Chart.min');
    $html .= $this->css->tag('styles');
    return $html;
  }
}
