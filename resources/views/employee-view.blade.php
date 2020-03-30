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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Information</h4>
                </div>
                <div class="card-body">
                    <form id="update_emp">
                        {{ csrf_field() }}
                        @foreach ($infos as $info)
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label>Employee Number</label>
                                    <input disabled type="text" class="form-control" placeholder="Username" value="{{ $info->EmpNo }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input disabled type="text" class="form-control" value="{{$info->EmpFirstName}}">
                                </div>
                            </div>
                            <div class="col-md-4 px-1">
                                <div class="form-group">
                                    <label>Middle Name</label>
                                    <input disabled type="text" class="form-control"  value="{{$info->EmpMiddleName}}">
                                </div>
                            </div>
                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input disabled type="text" class="form-control"  value="{{$info->EmpLastName}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-control" name="type" required>
                                        <option disabled>Select Type</option>
                                        <option {{( $info->EmpType == 'R') ? 'selected' : ''}} value="R">Regular</option>
                                        <option {{( $info->EmpType == 'C') ? 'selected' : ''}} value="C">Contractual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 px-1">
                                <div class="form-group">
                                    <label>Classification</label>
                                    <select class="form-control" name="classification" required>
                                        <option disabled>Select Classification</option>
                                        <option {{( $info->EmpClass == 'M') ? 'selected' : ''}} value="M">Monthly</option>
                                        <option {{( $info->EmpClass == 'D') ? 'selected' : ''}} value="D">Daily</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status" required>
                                        <option disabled>Select Status</option>
                                        <option {{( $info->EmpStatus == 'A') ? 'selected' : ''}} value="A">Active</option>
                                        <option {{( $info->EmpStatus == 'I') ? 'selected' : ''}} value="I">Inactive</option>
                                        <option {{( $info->EmpStatus == 'R') ? 'selected' : ''}} value="R">Resigned</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label>Date Hired</label>
                                    <input type="text" class="form-control" id="date_hired" name="date_hired" disabled value="{{date('m/d/Y', strtotime($info->EmpDateHired))}}">
                                </div>
                            </div>
                            <div class="col-md-3 px-1">
                                <div class="form-group">
                                    <label>Date End</label>
                                    <input type="text" class="form-control" id="date_end" name="date_end" value="{{(empty($info->EmpEndDate))? '':date('m/d/Y', strtotime($info->EmpEndDate))}}">
                                </div>
                            </div>
                            <div class="col-md-3 px-1">
                                <div class="form-group">
                                    <label>Cost Center Code</label>
                                    @if($processNameValidate == 0)
                                    <select name="cost_center_code" id="cost_center_code" required class="form-control">
                                        <option selected value="{{$details->new}}">No Process Name</option>
                                        @foreach($processName as $proc)
                                        <option value="{{$proc->costcode}}">{{$proc->process_name}}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    <select name="cost_center_code" id="cost_center_code" required class="form-control">
                                        @foreach($processName as $proc)
                                        <option value="{{$proc->costcode}}" {{($proc->costcode == $processNameUsed->costcode) ? 'selected' : ''}}>{{$proc->process_name.' '.cost_center_status($proc->status)}}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                    <input type="hidden" name="EmpNo" value="{{ $info->EmpNo }}">
                                </div>
                            </div>
                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label>Cost Center Code</label>
                                    @if($processNameValidate == 0)
                                    <input type="text" id="cost_code" class="form-control" disabled value="{{$details->new}}">
                                    @else
                                    <input type="text" id="cost_code" class="form-control" disabled value="{{$processNameUsed->costcode}}">
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <button type="submit" class="btn btn-info btn-fill pull-right updemp" disabled="{{(Auth::user()->role != 1)? 'false':'true'}}">Update Profile</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Allowance Usage -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Allowance Usage</h4>
                </div>
                <div class="card-body">
                    <form id="update_allowance">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label>Regular Allowance(₱)</label>
                                    <input disabled type="text" class="form-control" value="{{ currency($regallowance) }}">
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label>OT Allowance(₱)</label>
                                    <input disabled type="text" class="form-control" value="{{ currency($otallowance) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label>Add Regular Allowance(₱)</label>
                                    <input type="text" class="form-control" value="0" name="reg_all">
                                    <input type="hidden" class="form-control" name="empNo" value="{{$empNo}}">
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label>Add OT Allowance(₱)</label>
                                    <input type="text" class="form-control" value="0" name="ot_all">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info btn-fill pull-right updall" disabled="{{(Auth::user()->role != 1)? 'false':'true'}}">Update Allowance</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Generate Transaction</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 pr-1">
                            <div class="form-group">
                                <label>From</label>
                                <input type="text" class="form-control" id="from_tran">
                            </div>
                        </div>
                        <div class="col-md-4 px-1">
                            <div class="form-group">
                                <label>To</label>
                                <input type="text" disabled class="form-control" id="to_tran">
                            </div>
                        </div>
                        <div class="col-md-4 pl-1">
                            <div class="form-group">
                                <label></label>
                                <a href="#" id="genbtn" target="_blank" data-id="{{$empNo}}" class="form-control btn btn-success btn-fill disabled">Generate</a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table id="transact_table" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <th>#</th>
                                    <th>Receipt No</th>
                                    <th>Ordered Item</th>
                                    <th>Amount</th>
                                    <th>Date Transact</th>
                                </thead>
                                <tbody>
                                    @foreach($currentTrans as $keys => $todayTrans)
                                    <tr>
                                        <td>{{$keys+1}}</td>
                                        <td>{{$todayTrans->Receipt}}</td>
                                        <td>{{$todayTrans->OrderedItemsDescription}}</td>
                                        <td>{{($todayTrans->AllowanceAmtRegUsage > 0) ? $todayTrans->AllowanceAmtRegUsage : $todayTrans->AllowanceAmtOvtUsage}}</td>
                                        <td>{{datetime_con($todayTrans->TransactionDate)}}</td>
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
</div>

@endsection


@section('customjs')
<script src="{{ asset('assets/datepicker/jquery-ui.js') }}"></script>
<script type="text/javascript">
    $('#date_hired').datepicker();
    $('#date_end').datepicker();
    $('#from_tran').datepicker();
    $('#to_tran').datepicker();

    $('#transact_table').DataTable();

    // $('#cost_center').on('blur',function(e) {
    //     var code = $(this).val();
    //     $.ajax({
    //         type:'GET',
    //         url:'{{url('/check-code')}}',
    //         data:{code:code},
    //         success:function(data) {
    //             $.each(data.message, function(index,value){
    //                 if(data.error){
    //                     toastr.error(value, 'Cost Center Validation');
    //                 }else{
    //                     toastr.success(value, 'Cost Center Validation');
    //                     $('#cost_center_code').val(data.cost_center);
    //                 }
    //             });
    //         }
    //     });
    // });

    $('#cost_center_code').on('change',function() {
        var code = $(this).find('option:selected').val();
        $('#cost_code').val(code);
    })

    $('#from_tran').on('change',function() {
        var date1 = $(this).val();
        $('#to_tran').prop('disabled',false);
        $("#to_tran").datepicker("option", "minDate", date1);
    });
    $('#to_tran').on('change',function() {
        var date1 = $('#from_tran').val();
        var date2 = $(this).val();
        var emp = $('#genbtn').data('id');
        $.ajax({
            type:'GET',
            url:'{{url('/convert-date')}}',
            data:{date1:date1, date2:date2},
            success:function(data) {
                $.each(data.dates, function(index,value){
                    if(data.converted){
                        $('#genbtn').removeClass('disabled');
                        var url = "/Generate-Per-Emp/"+emp+"/"+value.date1+"/"+value.date2;
                        $('#genbtn').attr('href',url);
                    }
                });
            }
        });
    });

    $('.updemp').click(function() {
        event.preventDefault();
        var data = $('#update_emp').serialize();
        $.ajax({
            type:'POST',
            url:'{{  route('update-emp') }}',
            data:data,
            success:function(data) {
                $.each(data.message, function(index,value){
                    if(data.error){
                        toastr.error(value, 'Update Employee');
                    }else{
                        toastr.success(value, 'Update Employee');
                        window.setTimeout(function(){location.reload()},1000)
                    }
                });
            }
        });
    });
    $('.updall').click(function() {
        event.preventDefault();
        var data = $('#update_allowance').serialize();
        $.ajax({
            type:'POST',
            url:'{{  route('update-all') }}',
            data:data,
            success:function(data) {
                $.each(data.message, function(index,value){
                    if(data.error){
                        toastr.error(value, 'Employee Allowance');
                    }else{
                        toastr.success(value, 'Employee Allowance');
                        window.setTimeout(function(){location.reload()},1000)
                    }
                });
            }
        });
    });

</script>


@stop
