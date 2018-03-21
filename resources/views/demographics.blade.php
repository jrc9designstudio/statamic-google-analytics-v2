@extends('layout')

@section('content')
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
