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
                    <h4 class="card-title">Employee Masterlist</h4>
                    <button class="btn btn-primary btn-fill" data-toggle="modal" data-target=".employee_save_form"><i class="fas fa-user-plus"></i> Add Employee</button>
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
                            @foreach ($emp2 as $key => $emps)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $emps->EmpNo }}</td>
                                <td>{{ $emps->EmpFirstName.' '.$emps->EmpLastName }}</td>
                                <td>{{ emp_type($emps->EmpType) }}</td>
                                <td>{{ date_con($emps->EmpDateHired) }}</td>
                                <td>{{ get_proc_name($emps->new) }}</td>
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

<!-- Add Modal -->
<div class="modal fade employee_save_form" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="save_employee">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>Employee ID</label>
                                <input name="EmpNo" type="text" class="form-control" required placeholder="Employee ID">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 pr-1">
                            <div class="form-group">
                                <label>Lastname</label>
                                <input name="last_name" type="text" class="form-control" required placeholder="Lastname">
                            </div>
                        </div>
                        <div class="col-md-4 pr-1">
                            <div class="form-group">
                                <label>Firstname</label>
                                <input name="first_name" type="text" class="form-control" required placeholder="Firstname">
                            </div>
                        </div>
                        <div class="col-md-4 pr-1">
                            <div class="form-group">
                                <label>Middlename</label>
                                <input name="middle_name" type="text" class="form-control" required placeholder="Middlename">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="type" required>
                                    <option disabled selected>Select Type</option>
                                    <option value="R">Regular</option>
                                    <option value="C">Contractual</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>Process Name</label>
                                <select name="cost_center_code" id="cost_center_code" required class="form-control">
                                    <option selected disabled>Select Process</option>
                                    @foreach($processName as $proc)
                                        <option value="{{$proc->costcode}}">{{$proc->process_name.' '.cost_center_status($proc->status)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>Cost Center</label>
                                <input class="form-control" type="text" disabled id="cost_center_code_dis"></input>
                            </div>
                        </div>
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>Classification</label>
                                <select class="form-control" name="classification" required>
                                    <option disabled selected>Select Classification</option>
                                    <option value="M">Monthly</option>
                                    <option value="D">Daily</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>Date Hired</label>
                                <input name="date_hired" id="date_hired" type="text" class="form-control" required placeholder="Date Hired">
                            </div>
                        </div>
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>End of Cutoff date</label>
                                <input name="date_end" id="date_end" type="text" class="form-control" required placeholder="End of Cut off date">
                            </div>
                        </div>
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>Total Of Days</label>
                                <input name="total_days" id="total_days" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>Allowance</label>
                                <input name="allowance" id="allowance" type="text" class="form-control" placeholder="Allowance">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary regemp">Register Employee</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End modal -->
@endsection


@section('customjs')
<script src="{{ asset('assets/datepicker/jquery-ui.js') }}"></script>
<script type="text/javascript">
    $('#date_hired').datepicker();
    $('#date_end').datepicker();

    $('#date_hired').on('change',function() {
        var date1 = $(this).val();
        $("#date_end").datepicker("option", "minDate", date1);
    });

    $('#date_end').on('change',function() {
        var date_from = $('#date_hired').val()
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

    $('#empactive').DataTable({
        "processing": true,
        "language": {
            "loadingRecords": "&nbsp;",
            "processing": "<div id='loader'></div>"
        } 
    });

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
        $('#cost_center_code_dis').val(code);
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
                           toastr.error(value, 'Employee');
                        }else{
                           toastr.success(value, 'Employee');
                            window.setTimeout(function(){location.reload()},2000)
                        }
                    });
                }
            });
        });
</script>


@stop
