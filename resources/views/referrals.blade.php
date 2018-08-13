@extends('layout')

@section('content')
  @if(!$setup)
    <div class="alert alert-warning" role="alert">
      <p>Reporting not yet setup. You must <a href="https://statamic.com/marketplace/addons/google-analytics/docs#reporting-setup" target="_blank">set up reporting</a> before you can see reports here.</p>
    </div>
  @endif

  <div class="dashboard">
    <div class="flexy mb-24">
      <h1 class="fill">Referrals | Google Analytics</h1>

      <google-analytics-date-picker scope="page"></google-analytics-date-picker>
    </div>

    <div class="widgets">
      <div class="ga-widget widget full">
        <google-analytics-horizontal-bar-chart title="Top Referrers" endpoint="top-referrers" label-position="bottom" date-picker="hide"></google-analytics-horizontal-bar-chart>
      </div>

      <div class="ga-widget ga-widget-last widget full">
        <google-analytics-table title="Top Referrers" endpoint="top-referrers-table" date-picker="hide"></google-analytics-table>
      </div>
    </div>
  </div>
@stop
