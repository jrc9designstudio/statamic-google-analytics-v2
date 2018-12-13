<?php

namespace Statamic\Addons\GoogleAnalytics;

use Carbon;
use Analytics;
use Statamic\Extend\Filter;
use JRC9DS\Analytics\Period;

class GoogleAnalyticsFilter extends Filter
{
    /**
     * Perform filtering on a collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function filter()
    {
        return $this->collection->sortByDesc(function($entry) {
            // This is the date that Google Analytics was started
            $period = Period::create(new Carbon('2005-01-01'), new Carbon());
            $hits = (int)Analytics::performQuery(
              $period,
              'ga:pageviews',
              [
                'filters' => 'ga:pagePath==' . $path = $entry->url()
              ]
            )->totalsForAllResults['ga:pageviews'];
            return $hits;
        });
    }
}
