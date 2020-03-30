@extends('layouts.app')
@section('customcss')
<link href="{{ asset('assets/datepicker/jquery-ui.css') }}" rel="stylesheet" />
<style type="text/css">
    .card .table tbody td:last-child, .card .table thead th:last-child {
        padding-right: 15px;
        display: table-cell;
    }
    .valid{
        border:1px solid #00ff00;
    }
    .invalid{
        border:1px solid #ff0000;
    }
</style>
@stop
@section('content')

<div class="container">
    <!-- Start Container -->
    <div class="row">
        <div class="col-md-12">
            <div class="card strpied-tabled-with-hover">
                <div class="card-header ">
                    <h4 class="card-title">Cost Center Generation Report</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center mx-auto">
                        <div class="col-md-12">
                            <form id="gen_reg_all" method="POST" action="{{url('Generate-Cost')}}" target="_blank">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="text" class="form-control" id="date_from" name="date_from" required>
                                </div>
                            </div>
                            <div class="col-md-4 px-1">
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="text" class="form-control" id="date_to" name="date_to" required>
                                </div>
                            </div>
                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label>Canteen</label>
                                    <select class="form-control" name="can" required>
                                        <option selected disabled>SELECT CANTEEN</option>
                                        <option value="0">All</option>
                                        @foreach($canteen as $can)
                                        <option value="{{$can->canteencode}}">{{$can->canteenname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pr-4">
                                <div class="form-group">
                                    <label>Prepared By</label>
                                    <input type="hidden" name="prepared_by" value="{{Auth::user()->id}}">
                                    <input disabled type="text" class="form-control" value="{{Auth::user()->name}}">
                                </div>
                            </div>
                            <div class="col-md-4 pr-4">
                                <div class="form-group">
                                    <label>Reviewed By</label>
                                    <select class="form-control" name="reviewed_by" required>
                                        <option disabled selected>Select Reviewer</option>
                                        @foreach($reviewer as $reviewers)
                                        <option value="{{$reviewers->id}}">{{$reviewers->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 pr-4">
                                <div class="form-group">
                                    <label>Approved By</label>
                                    <select class="form-control" name="approved_by" required>
                                        <option disabled selected>Select Approver</option>
                                        @foreach($approver as $approvers)
                                        <option value="{{$approvers->id}}">{{$approvers->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-8 pr-4">
                                <div class="form-group">
                                    <button class="btn btn-success btn-fill" type="submit">Generate Cost Center Report</button>
                                </div>
                            </div>
                        </div>
                    </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container -->
</div>
@endsection


@section('customjs')
<script src="{{ asset('assets/datepicker/jquery-ui.js') }}"></script>
<script type="text/javascript">
    $('#date_from').datepicker();
    $('#date_to').datepicker();

     $('#date_from').on('change',function() {
        var date1 = $(this).val();
        $("#date_to").datepicker("option", "minDate", date1);
        $('#total_days').val('');
    });
</script>


@stop
