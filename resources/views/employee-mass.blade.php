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
                    <h4 class="card-title">Generate Template</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn btn-success btn-fill form-control" href="{{ route('mass_temp') }}">Generate Template</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card strpied-tabled-with-hover">
                <div class="card-header ">
                    <h4 class="card-title">Employee Mass Upload</h4>
                </div>
                <div class="card-body">
                    <form id="upload_mass" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>File Upload</label>
                                    <input type="file" name="upload" required class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                </div>
                            </div>
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>Allowance</label>
                                    <input type="text" name="allowance" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label> </label>
                                    <button class="btn btn-success btn-fill form-control mass_btn" type="submit">Upload New Employee</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('customjs')
<script src="{{ asset('assets/datepicker/jquery-ui.js') }}"></script>
<script type="text/javascript">
    $('.mass_btn').click(function() {
        // var data = $('#gen_ot_all').serialize();
        event.preventDefault();
        var data = new FormData($('#upload_mass')[0]);
        $.ajax({
            type:'POST',
            url:'{{  route('mass_upload') }}',
            data:data,
            cache:false,
            processData: false,
            contentType: false,
            beforeSend:function() {
                $('.loader').css('display','block');
            },
            success:function(data) {
                $.each(data.message, function(index,value){
                    if(data.error){
                        toastr.error(value, 'Mass Upload');
                    }else{
                        toastr.success(value, 'Mass Upload');
                        window.setTimeout(function(){location.reload()},1000)
                    }
                });
            },
            complete:function(){
                $('.loader').css('display','none');
            }
        });
    });
</script>


@stop
