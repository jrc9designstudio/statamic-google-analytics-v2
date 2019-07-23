<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\API\Nav;
use Statamic\API\User;
use Statamic\Extend\Listener;

class GoogleAnalyticsListener extends Listener {
  private $googleanalytics;

  public function __construct(GoogleAnalytics $googleanalytics) {
    $this->googleanalytics = $googleanalytics;
  }

  public $events = [
    'cp.nav.created' => 'addNavItems',
    'cp.add_to_head' => 'addAssets',
  ];

  /**
   * @param \Statamic\CP\Navigation\Nav $nav
   */
  public function addNavItems($nav) {
    if ($this->googleanalytics->accessCheck()) {
      // Create the first level navigation item
      $store = Nav::item($this->trans('cp.nav.google_analytics'))->route('index')->icon('line-graph');

      // Add second level navigation items to it
      $store->add(function ($item) {
        $item->add(Nav::item($this->trans('cp.nav.browsers'))->route('google-analytics.browsers'));
        /* $item->add(Nav::item('Demographics')->route('google-analytics.demographics')); */
        $item->add(Nav::item($this->trans('cp.nav.location'))->route('google-analytics.location'));
        $item->add(Nav::item($this->trans('cp.nav.page_views'))->route('google-analytics.page-views'));
        $item->add(Nav::item($this->trans('cp.nav.referrals'))->route('google-analytics.referrals'));


        $user = User::getCurrent();

        if ($user && $user->isSuper()) {
          $item->add(Nav::item($this->trans('cp.nav.settings'))->route('addon.settings', 'google-analytics'));
        }
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
    $html = $this->js->tag('chart.js');
    $html .= $this->css->tag('styles.css');
    return $html;
  }
}
