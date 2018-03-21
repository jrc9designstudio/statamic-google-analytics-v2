@extends('layout')

@section('content')
  <div class="dashboard">
    <div class="flexy mb-24">
      <h1 class="fill">Referals | Google Analytics</h1>

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
