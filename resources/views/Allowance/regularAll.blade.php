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
                    <h4 class="card-title">Generate Regular Meal Allowance</h4>
                </div>
                <div class="card-body">
                    <form id="gen_reg_all">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="text" class="form-control" id="date_from" name="date_from">
                                </div>
                            </div>
                            <div class="col-md-4 px-1">
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="text" class="form-control" id="date_to" name="date_to">
                                </div>
                            </div>
                            <div class="col-md-2 px-1">
                                <div class="form-group">
                                    <label>Total of Working Days</label>
                                    <input type="text" class="form-control" name="days" id="total_days">
                                </div>
                            </div>
                            <div class="col-md-2 pl-1">
                                <div class="form-group">
                                    <label>Per Day Allowance</label>
                                    <input type="text" class="form-control" name="allowance">
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
                                    <button class="btn btn-success btn-fill Start_gen_reg" disabled="{{(Auth::user()->role != 1)? 'false':'true'}}" type="submit">Generate Regular Meal Allowance</button>
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

    $('#date_to').on('change',function() {
        var date_from = $('#date_from').val()
        var date_to = $(this).val();
        $.ajax({
            type:'GET',
            url:'{{url('/getWorking')}}',
            data:{date_from:date_from,date_to:date_to},
            success:function(data) {
                $('#total_days').val(data.workingday);
            }
        });
    });

    $('.Start_gen_reg').click(function(event){
        event.preventDefault();
        var data = $('#gen_reg_all').serialize();
        $.ajax({
            type:'POST',
            url:'{{  route('generateReg') }}',
            data:data,
            beforeSend:function() {
                $('.loader').css('display','block');
            },
            success:function(data) {
                $.each(data.message, function(index,value){
                    if(data.error){
                        toastr.error(value, 'Generation of Regular Allowance');
                    }else{
                        toastr.success(value, 'Generation of Regular Allowance');
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
