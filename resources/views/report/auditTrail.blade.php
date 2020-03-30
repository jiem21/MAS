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
                    <h4 class="card-title">Audit Trail Report Generation</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center mx-auto">
                        <div class="col-md-12">
                            <form id="gen_reg_all" method="POST" action="{{url('Generate-Audit')}}" target="_blank">
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
                                    <label></label>
                                    <button class="btn btn-success btn-fill form-control" type="submit">Generate Audit Trail Report</button>
                                </div>
                            </div>
                        </div>
                    </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card strpied-tabled-with-hover">
                <div class="card-header ">
                    <h4 class="card-title">Acitivity Logs for today</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center mx-auto">
                        <div class="col-md-12">
                             <table id="tableaudit" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <th>#</th>
                                    <th>Activity</th>
                                    <th>User</th>
                                    <th>IP Address</th>
                                    <th>Time</th>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $key => $log)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $log->activity_name }}</td>
                                        <td>{{ $log->activity_user }}</td>
                                        <td>{{ $log->ip_address}}</td>
                                        <td>{{ date("h:i:s A",strtotime($log->activity_date))}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    $('#tableaudit').DataTable();
    $('#date_from').datepicker();
    $('#date_to').datepicker();

     $('#date_from').on('change',function() {
        var date1 = $(this).val();
        $("#date_to").datepicker("option", "minDate", date1);
        $('#total_days').val('');
    });
</script>


@stop