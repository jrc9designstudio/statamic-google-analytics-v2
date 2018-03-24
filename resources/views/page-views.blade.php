@extends('layout')

@section('content')
  <div class="dashboard">
    <div class="flexy mb-24">
      <h1 class="fill">Google Analytics</h1>

      <google-analytics-date-picker scope="page"></google-analytics-date-picker>
    </div>

    <div class="widgets">
      <div class="ga-widget widget full">
        <google-analytics-line-chart title="Visitors & Page Views" endpoint="total-visitors-and-page-views" date-picker="hide"></google-analytics-line-chart>
      </div>

      <div class="ga-widget ga-widget-last widget full">
        <google-analytics-table title="Most Visited Pages" endpoint="most-visited-pages" date-picker="hide"></google-analytics-table>
      </div>
    </div>
  </div>
@stop
