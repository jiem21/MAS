@extends('layouts.app')
@section('customcss')
<style type="text/css">
    .card .table tbody td:last-child, .card .table thead th:last-child {
        padding-right: 15px;
        display: table-cell;
    }
</style>
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card strpied-tabled-with-hover">
                <div class="card-header ">
                    <h4 class="card-title">Employee list near end date</h4>
                </div>
                <div class="card-body table-full-width table-responsive">
                    <table id="enddate" class="table table-striped table-bordered table-hover">
                        <thead>
                            <th>#</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Date Hired</th>
                            <th>End Date</th>
                            <th>Cost Center</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($list_emp as $key => $list_due)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $list_due->EmpNo }}</td>
                                <td>{{ $list_due->EmpFirstName.' '.$list_due->EmpLastName }}</td>
                                <td>{{ date_con($list_due->EmpDateHired) }}</td>
                                <td>{{ date_con($list_due->EmpEndDate) }}</td>
                                <td>{{ $list_due->new }}</td>
                                <td><a href="{{url('/master-list/view-emp').'/'.$list_due->EmpNo}}" class="btn btn-primary" id="{{ $list_due->EmpNo }}">Edit</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('customjs')
<script type="text/javascript">
    $('#enddate').DataTable();
    $('#resigned').DataTable();



</script>
@stop
