@extends('layout')

@section('content')
  @if(!$setup)
    <div class="alert alert-warning" role="alert">
      <p>Reporting not yet setup. You must <a href="https://statamic.com/marketplace/addons/google-analytics/docs#reporting-setup" target="_blank">set up reporting</a> before you can see reports here.</p>
    </div>
  @endif

  <div class="dashboard">
    <div class="flexy mb-24">
      <h1 class="fill">Demographics | Google Analytics</h1>
    </div>

    <div class="widgets">
      <div class="ga-widget widget half">
        <google-analytics-doughnut-chart title="Top Browsers" endpoint="top-browsers" label-position="right"></google-analytics-doughnut-chart>
      </div>
    </div>
  </div>
@stop
