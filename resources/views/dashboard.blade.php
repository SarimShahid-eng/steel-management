@extends('partials.app',['title'=>'Dashboard'])
@section('content')
    <div class="col-span-12 space-y-6 xl:col-span-12">
        {{-- @include('partials.metric-group.metric-group-01') --}}
        <!-- Metric Group One -->
        <!-- Metric Group One -->

        <!-- ====== Chart One Start -->
        {{-- @include('partials.chart.chart-01') --}}
        <!-- ====== Chart One End -->
    </div>
    {{-- <div class="col-span-12 xl:col-span-5">
        <!-- ====== Chart Two Start -->
        @include('partials.chart.chart-02')
        <!-- ====== Chart Two End -->
    </div>

    <div class="col-span-12">
        <!-- ====== Chart Three Start -->
        @include('partials.chart.chart-03')
        <!-- ====== Chart Three End -->
    </div> --}}

    {{-- <div class="col-span-12 xl:col-span-5">
        <!-- ====== Map One Start -->
        @include('partials.map-01')
        <!-- ====== Map One End -->
    </div> --}}

    {{-- <div class="col-span-12 xl:col-span-7"> --}}
        <!-- ====== Table One Start -->
        {{-- @include('partials.table.table-01') --}}

        <!-- ====== Table One End -->
    {{-- </div> --}}
@endsection
