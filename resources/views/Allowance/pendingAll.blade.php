@extends('layouts.app')
@section('customcss')
<link href="{{ asset('assets/datepicker/jquery-ui.css') }}" rel="stylesheet" />
<style type="text/css">
         .card .table tbody td:last-child, .card .table thead th:last-child {
        padding-right: 15px;
        display: table-cell;
    }
</style>
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card strpied-tabled-with-hover">
                <div class="card-header">
                    <h4 class="card-title">Pending Transaction</h4>
                </div>
                <div class="card-body table-full-width table-responsive">
                    <table id="genlist" class="table table-striped table-bordered table-hover">
                        <thead>
                            <th>#</th>
                            <th>Cutoff Date</th>
                            <th>Allowance</th>
                            <th>Total Days</th>
                            <th>Total Employees</th>
                            <th>Total Allowance</th>
                            <th>Allowance Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($lists as $key => $list)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ date_con($list->date_from).' - '. date_con($list->date_to)}}</td>
                                <td>₱{{ currency($list->allowance) }}</td>
                                <td>{{ $list->total_days }}</td>
                                <td>{{ $list->total_emp }}</td>
                                <td>₱{{ currency($list->total_allowance) }}</td>
                                <td>{{ allowance_type($list->allowance_type) }}</td>
                                <td>{{ allowance_status($list->status) }}</td>
                                <td><a class="btn btn-primary" href="{{url('/Generated-Allowance/View').'/'.$list->id}}"><i class="fas fa-hamburger"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    $('#genlist').DataTable();
    $('#genlist2').DataTable();
</script>

@stop
