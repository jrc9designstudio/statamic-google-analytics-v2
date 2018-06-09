@extends('layout')

@section('content')
  <div class="dashboard">
    <div class="flexy mb-24">
      <h1 class="fill">Google Analytics</h1>

      <google-analytics-date-picker scope="page"></google-analytics-date-picker>
    </div>

    <div class="widgets">
      <div class="ga-widget widget full">
        <google-analytics-line-chart title="Visitors & Page Views" endpoint="total-visitors-and-page-views" date-picker="hide" label-position="bottom"></google-analytics-line-chart>
      </div>

      <div class="ga-widget ga-widget-last widget half">
        <google-analytics-doughnut-chart title="Top Referrers" endpoint="top-referrers" label-position="right" date-picker="hide"></google-analytics-horizontal-bar-chart>
      </div>

      <div class="ga-widget ga-widget-last widget half">
        <google-analytics-doughnut-chart title="Top Browsers" endpoint="top-browsers" label-position="right" date-picker="hide"></google-analytics-horizontal-bar-chart>
      </div>
    </div>
  </div>
@stop
