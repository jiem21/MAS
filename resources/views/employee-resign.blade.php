@extends('layouts.app')
@section('customcss')
<link href="{{ asset('assets/datepicker/jquery-ui.css') }}" rel="stylesheet" />
<style type="text/css">
    .card .table tbody td:last-child, .card .table thead th:last-child {
        padding-right: 15px;
        display: table-cell;
    }
    #loader {
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid #3498db;
      width: 120px;
      height: 120px;
      -webkit-animation: spin 2s linear infinite;
      animation: spin 2s linear infinite;
      margin-left:200px;
      margin-top:30px;
      z-index: 9999;
  }   
</style>
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card strpied-tabled-with-hover">
                <div class="card-header ">
                    <h4 class="card-title">Employee Resigned List</h4>
                </div>
                <div class="card-body table-full-width table-responsive">
                    <table id="empactive" class="table table-striped table-bordered table-hover">
                        <thead>
                            <th>#</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Employee Type</th>
                            <th>Date Hired</th>
                            <th>Cost Center</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($resign as $key => $emps)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $emps->EmpNo }}</td>
                                <td>{{ $emps->EmpFirstName.' '.$emps->EmpLastName }}</td>
                                <td>{{ emp_type($emps->EmpType) }}</td>
                                <td>{{ date_con($emps->EmpDateHired) }}</td>
                                <td>{{ $emps->new }}</td>
                                <td><a class="btn btn-primary" target="_blank" href="{{url('/master-list/view-emp').'/'.$emps->EmpNo}}"><i class="fas fa-hamburger"></i>View</a></td>
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
<script src="{{ asset('assets/datepicker/jquery-ui.js') }}"></script>
<script type="text/javascript">
    $('#date_hired').datepicker();
    $('#empactive').DataTable({
        "processing": true,
        "language": {
            "loadingRecords": "&nbsp;",
            "processing": "<div id='loader'></div>"
        } 
    });

    $('#cost_center').on('blur',function(e) {
        var code = $(this).val();
        $.ajax({
            type:'GET',
            url:'{{url('/check-code')}}',
            data:{code:code},
            success:function(data) {
                $.each(data.message, function(index,value){
                    if(data.error){
                        alert(value);
                    }else{
                        alert(value);
                        $('#cost_center_code').val(data.cost_center);
                    }
                });
            }
        });
    });

    $('.regemp').click(function(event){
            event.preventDefault();
            var data = $('#save_employee').serialize();
            $.ajax({
                type:'POST',
                url:'{{  route('save-emp') }}',
                data:data,
                success:function(data) {
                    $.each(data.message, function(index,value){
                        if(data.error){
                            alert(value);
                        }else{
                            alert(value);
                            window.setTimeout(function(){location.reload()},2000)
                        }
                    });
                }
            });
        });
</script>


@stop
