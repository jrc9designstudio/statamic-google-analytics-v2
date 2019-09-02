<?php

namespace Statamic\Addons\GoogleAnalytics\Commands;

use Carbon;
use Analytics;
use JRC9DS\Analytics\Period;
use Statamic\API\Cache;
use Statamic\API\Page;
use Statamic\API\Taxonomy;
use Statamic\Extend\Command;
use Statamic\API\Collection;

class PrecacheHitsCommand extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'googleanalytics:precache-hits';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Precache hit values for the hits tag.';

  /**
   * Create a new command instance.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    // Hold urls to pre-cache
    $urls = [];

    // Get urls for pages
    $pages = Page::all()->removeUnpublished();
    foreach ($pages as $page) {
      $urls[] = $page->url();
    }

    // Get urls for collections
    $handles = Collection::handles();
    foreach($handles as $handle) {
      $urls = array_merge($urls, $this->collectionUrls($handle));
    }

    // Get urls for taxonomies
    $handles = Taxonomy::handles();
    foreach($handles as $handle) {
      $urls = array_merge($urls, $this->taxonomyUrls($handle));
    }

    $index = 1;
    $size = sizeof($urls);

    foreach ($urls as $url) {
      $data = (int)Analytics::performQuery(
        // This is the date that Google Analytics was started
        Period::create(new Carbon('2005-01-01'), new Carbon()),
        'ga:pageviews',
        [
          'filters' => 'ga:pagePath==' . $url,
        ]
      )->totalsForAllResults['ga:pageviews'];
      $this->cache->put($url, $data, $this->getConfig('page_hits_cache_time', 1440));
      $this->info($index . "/" . $size . " -- Cached URL: " . $url . " Page Views: " . $data);
      $index++;
    }
  }

  /**
   * Gets the collection urls for every entry in a given collection
   *
   * @return array(string)
   */
  private function collectionUrls($handle) {
    $urls = [];
    $collection = Collection::whereHandle($handle);
    $entries = $collection->entries()->removeUnpublished();
    foreach ($entries as $entry) {
      $url = $entry->url();
      if ($url !== '/') {
        $urls[] = $url;
      }
    }
    return $urls;
  }

  /**
   * Gets the taxonomy urls for every entry in a given taxonomy
   *
   * @return array(string)
   */
  private function taxonomyUrls($handle) {
    $urls = [];
    $taxonomy = Taxonomy::whereHandle($handle);
    $terms = $taxonomy->terms()->removeUnpublished();
    foreach ($terms as $term) {
      $url = $term->url();
      if ($url !== '/') {
        $urls[] = $url;
      }
    }
    return $urls;
  }
}
