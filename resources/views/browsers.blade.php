@extends('layout')

@section('content')
  @if(!$setup)
    <div class="alert alert-warning" role="alert">
      <p>{!! $not_setup !!}</p>
    </div>
  @endif

  <div class="dashboard">
    <div class="flexy mb-24">
      <h1 class="fill">{{ $title }}</h1>

      <google-analytics-date-picker scope="page"></google-analytics-date-picker>
    </div>

    <div class="widgets">
      <div class="ga-widget widget full">
        <google-analytics-horizontal-bar-chart title="{{ $top_browsers }}" endpoint="top-browsers" label-position="right" date-picker="hide"></google-analytics-horizontal-bar-chart>
      </div>

      <div class="ga-widget ga-widget-last widget full">
          <google-analytics-table title="{{ $top_browsers }}" endpoint="top-browsers-table" date-picker="hide"></google-analytics-table>
        </div>
    </div>
  </div>
@stop
