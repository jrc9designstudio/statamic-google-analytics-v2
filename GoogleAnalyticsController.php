<?php

namespace Statamic\Addons\GoogleAnalytics;

use Log;
use Analytics;
use Carbon;
use Statamic\API\User;
use JRC9DS\Analytics\Period;
use Statamic\Extend\Controller;

class GoogleAnalyticsController extends Controller {
  private $colours = [
    '#54151a',
    '#585b57',
    '#3e5050',
    '#1a2d3a',
    '#0b1420',
    '#93252d',
    '#9aa098',
    '#6b8b8c',
    '#2c4f64',
    '#132238',
    '#d23440',
    '#dce4d9',
    '#99c7c8',
    '#3f708f',
    '#1b3150',
    '#ea5d67',
    '#f3faf1',
    '#b7e0e2',
    '#6693ae',
    '#465975'
  ];

  public function index() {
    return $this->view('index');
  }

  public function pageViews() {
    return $this->view('page-views');
  }

  public function browsers() {
    return $this->view('browsers');
  }

  public function referals() {
    return $this->view('referals');
  }

  public function totalVisitorsAndPageViews() {
    try {
      $period = $this->getPeriod();

      $totalVisitorsAndPageViews = Analytics::fetchTotalVisitorsAndPageViews($period)->toArray();

      $visitors = array_column($totalVisitorsAndPageViews, 'visitors');
      $pageViews = array_column($totalVisitorsAndPageViews, 'pageViews');
      $labels = array();

      // Find out if the dates span years, if so include the year in the labels
      if (reset($totalVisitorsAndPageViews)['date']->formatLocalized('%Y') == end($totalVisitorsAndPageViews)['date']->formatLocalized('%Y')) {
        foreach($totalVisitorsAndPageViews as $data) {
          $labels[] = $data['date']->formatLocalized('%b %d');
        }
      } else {
        foreach($totalVisitorsAndPageViews as $data) {
          $labels[] = $data['date']->formatLocalized('%b %d, %Y');
        }
      }

      return [
        'labels' => $labels,
        'points' => [$visitors, $pageViews],
      ];
    } catch (\Exception $e) {
        return $e;
    }
  }

  public function topBrowsers() {
    try {
      $period = $this->getPeriod();

      $topBrowsers = Analytics::fetchTopBrowsers($period)->toArray();

      $labels = array_column($topBrowsers, 'browser');
      $sessions = array_column($topBrowsers, 'sessions');

      return [
        'labels' => $labels,
        'points' => $sessions,
        'colours' => $this->getColours($labels)
      ];
    } catch (\Exception $e) {
        return $e;
    }
  }

  public function topBrowsersTable() {
    try {
      $period = $this->getPeriod();

      $topBrowsers = Analytics::fetchTopBrowsers($period)->toArray();

      $labels = array_map(function($label) {
        return $this->camelToTitle($label);
      }, array_keys($topBrowsers[0]));

      return [
        'rows' => $topBrowsers,
        'labels' => $labels,
      ];
    } catch (\Exception $e) {
        return $e;
    }
  }

  public function topReferrers() {
    try {
      $period = $this->getPeriod();

      $topReferrers = Analytics::fetchTopReferrers($period)->toArray();

      $labels = array_map(function($label) {
        return strlen($label) > 20 ? substr($label,0,20)."..." : $label;
      }, array_column($topReferrers, 'url'));

      return [
        'labels' => $labels,
        'points' => array_column($topReferrers, 'pageViews'),
        'colours' => $this->getColours($labels)
      ];
    } catch (\Exception $e) {
        return $e;
    }
  }

  public function topReferrersTable() {
    try {
      $period = $this->getPeriod();

      $topReferrers = Analytics::fetchTopReferrers($period)->toArray();

      $labels = array_map(function($label) {
        return $this->camelToTitle($label);
      }, array_keys($topReferrers[0]));

      return [
        'rows' => $topReferrers,
        'labels' => $labels,
      ];
    } catch (\Exception $e) {
        return $e;
    }
  }

  public function mostVisitedPages() {
    try {
      $period = $this->getPeriod();

      $mostVisitedPages = Analytics::fetchMostVisitedPages($period)->toArray();

      $labels = array_map(function($label) {
        return $this->camelToTitle($label);
      }, array_keys($mostVisitedPages[0]));

      return [
        'rows' => $mostVisitedPages,
        'labels' => $labels,
      ];
    } catch (\Exception $e) {
        return $e;
    }
  }

  private function getPeriod() {
    $request = request();

    $startDate = $request->get('startDate', null);
    $endDate = $request->get('endDate', null);

    if ($startDate != null && $endDate != null) {
      $startDate = new Carbon($startDate);
      $endDate = new Carbon($endDate);

      try {
        $period = Period::create($startDate, $endDate);
      } catch (\Exception $e) {
        $period = Period::days(7);
      }
    } else {
      $period = Period::days(7);
    }

    return $period;
  }

  private function camelToTitle($camelStr) {
    $intermediate = preg_replace('/(?!^)([[:upper:]][[:lower:]]+)/',
                          ' $0',
                          $camelStr);
    $titleStr = preg_replace('/(?!^)([[:lower:]])([[:upper:]])/',
                          '$1 $2',
                          $intermediate);

    return ucwords($titleStr);
  }

  private function getColours($labels) {
    return array_map(function($label, $key) {
      return $this->getColour($label, $key);
    }, $labels, array_keys($labels));
  }

  private function getColour($label, $key) {
    if (preg_match('/chrome/i', $label)) {
      return '#24BF5A';
    } else if (preg_match('/firefox|mozilla/i', $label)) {
      return '#FF9400';
    } else if (preg_match('/safari/i', $label)) {
      return '#5AC8FA';
    } else if (preg_match('/edge|internet\sexplorer/i', $label)) {
      return '#0078D7';
    } else if (preg_match('/opera/i', $label)) {
      return '#FF1B2D';
    } else if (preg_match('/android/i', $label)) {
      return '#A4C439';
    } else if (preg_match('/^(www|m|l|lm\.)?facebook/i', $label)) {
      return '#3b5998';
    } else if (preg_match('/^(www\.)?twitter|t\.co\//i', $label)) {
      return '#1da1f2';
    } else if (preg_match('/^(www\.)?youtube|youtu\.be/i', $label)) {
      return '#ff0000';
    } else if (preg_match('/^(www\.)?instagram/i', $label)) {
      return '#c32aa3';
    } else if (preg_match('/^(www\.)?pinterest/i', $label)) {
      return '#bd081c';
    } else if (preg_match('/^(www\.)?linkedin/i', $label)) {
      return '#007bb5';
    } else if (preg_match('/^plus\.google/i', $label)) {
      return '#db4437';
    } else if (preg_match('/^(www\.)?google/i', $label)) {
      return '#4285f4';
    } else if (preg_match('/^(www\.)?snapchat/i', $label)) {
      return '#fffc00';
    } else if (preg_match('/^(www\.)?whatsapp/i', $label)) {
      return '#25d366';
    } else if (preg_match('/^(www\.)?tumblr/i', $label)) {
      return '#35465d';
    } else if (preg_match('/^(www\.)?reddit/i', $label)) {
      return '#ff4500';
    } else if (preg_match('/^(www\.)?medium/i', $label)) {
      return '#02b875';
    } else {
      return $this->colours[$key];
    }
  }
}
