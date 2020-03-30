@extends('layouts.app')
@section('customcss')
<link href="{{ asset('assets/datepicker/jquery-ui.css') }}" rel="stylesheet" />
<style type="text/css">

</style>
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Generate Overtime Meal Allowance</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 px-1 text-center">
                            <a class="btn btn-success btn-fill" href="{{route('GenerateEmpListForOT')}}" id="genlist"><i class="fas fa-drumstick-bite"></i> Generate Employee List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Overtime Meal Allowance</h4>
                </div>
                <div class="card-body">
                    <form id="gen_ot_all" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="text" class="form-control" id="date_from" name="date_from">
                                </div>
                            </div>
                            <div class="col-md-3 px-1">
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="text" class="form-control" id="date_to" name="date_to">
                                </div>
                            </div>
                            <div class="col-md-3 px-1">
                                <div class="form-group">
                                    <label>Allowance Per Day</label>
                                    <input type="text" name="allowance" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label>File Upload</label>
                                    <input type="file" name="upload" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
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
                                    <button class="btn btn-success btn-fill Upload_gen" disabled="{{(Auth::user()->role != 1)? 'false':'true'}}" type="submit">Generate Overtime Meal Allowance</button>
                                </div>
                            </div>
                        </div>
                    </form>
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

    $('.Upload_gen').click(function() {
        // var data = $('#gen_ot_all').serialize();
        event.preventDefault();
        var data = new FormData($('#gen_ot_all')[0]);
        $.ajax({
            type:'POST',
            url:'{{  route('Import-Generated-OT') }}',
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
                        toastr.error(value, 'Generation of OT Allowance');
                    }else{
                        toastr.success(value, 'Generation of OT Allowance');
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
