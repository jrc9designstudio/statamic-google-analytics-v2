<?php

namespace Statamic\Addons\GoogleAnalytics;

use Carbon;
use Analytics;
use Statamic\Extend\Filter;
use JRC9DS\Analytics\Period;

class GoogleAnalyticsFilter extends Filter {
  /**
   * Perform filtering on a collection
   *
   * @return \Illuminate\Support\Collection
   */
  public function filter($collection) {
    $requestPeriod = $this->getInt('period', null);
    // This is the date that Google Analytics was started
    $period = $requestPeriod ? Period::days($requestPeriod) : Period::create(new Carbon('2005-01-01'), new Carbon());
      
    return $collection->sortByDesc(function($entry) use ($requestPeriod, $period) {
      $key = $entry->url();
      if ($requestPeriod) {
        $key .= '_p_' . $requestPeriod;
      }
      $hits = (int) $this->cache->get($key);
      
      if ($hits == null) {
        $hits = (int)Analytics::performQuery(
          $period,
          'ga:pageviews',
          [
            'filters' => 'ga:pagePath==' . $entry->url()
          ]
        )->totalsForAllResults['ga:pageviews'];
        $this->cache->put($key, $hits, $this->getConfig('page_hits_cache_time', 1440));
      }
      
      return $hits;
    });
  }
}
