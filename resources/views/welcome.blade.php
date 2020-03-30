@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header ">
                <h4 class="card-title">Email Statistics</h4>
                <p class="card-category">Last Campaign Performance</p>
            </div>
            <div class="card-body ">
                <div id="chartPreferences" class="ct-chart ct-perfect-fourth"></div>
                <div class="legend">
                    <i class="fa fa-circle text-info"></i> Open
                    <i class="fa fa-circle text-danger"></i> Bounce
                    <i class="fa fa-circle text-warning"></i> Unsubscribe
                </div>
                <hr>
                <div class="stats">
                    <i class="fa fa-clock-o"></i> Campaign sent 2 days ago
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
