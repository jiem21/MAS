@extends('layouts.app')
@section('customcss')
<link href="{{ asset('assets/datepicker/jquery-ui.css') }}" rel="stylesheet" />
<style type="text/css">
    .card .table tbody td:last-child, .card .table thead th:last-child {
        padding-right: 15px;
        display: table-cell;
    }
    textarea {
        height: 100px !important;
    }
</style>
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Generated Allowance</h4>
                </div>
                <div class="card-body">
                    <form id="update_emp">
                        {{ csrf_field() }}
                        @foreach($lists as $list)
                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="text" class="form-control" id="date_from" name="date_from" disabled value="{{date_con($list->date_from)}}">
                                </div>
                            </div>
                            <div class="col-md-3 px-1">
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="text" class="form-control" id="date_to" name="date_to" disabled value="{{date_con($list->date_to)}}">
                                </div>
                            </div>
                            <div class="col-md-3 px-1">
                                <div class="form-group">
                                    <label>Allowance Type</label>
                                    <input type="hidden" name="all_type" id="all_type" value="{{$list->allowance_type}}">
                                    <input type="text" class="form-control" id="allowance_type" name="allowance_type" disabled value="{{ allowance_type($list->allowance_type) }}">
                                </div>
                            </div>
                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label>Status</label>
                                    <input type="text" class="form-control" id="status" name="status" disabled value="{{main_status($list->status)}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label>Total Days</label>
                                    <input disabled type="text" class="form-control" value="{{$list->total_days}}">
                                </div>
                            </div>
                            <div class="col-md-3 px-1">
                                <div class="form-group">
                                    <label>Total Employee</label>
                                    <input disabled type="text" class="form-control" value="{{$list->total_emp}}" >
                                </div>
                            </div>
                            <div class="col-md-3 px-1">
                                <div class="form-group">
                                    <label>Allowance</label>
                                    <input disabled type="text" class="form-control" value="₱{{currency($list->allowance)}}" >
                                </div>
                            </div>
                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label>Total Allowance</label>
                                    <input disabled type="text" class="form-control" value="₱{{currency($list->total_allowance)}}" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>Approved By</label>
                                    <input disabled type="text" class="form-control" value="{{ get_name($list->approved_by) }}">
                                </div>
                            </div>
                            <div class="col-md-4 px-1">
                                <div class="form-group">
                                    <label>Date Transacted</label>
                                    <input disabled type="text" class="form-control" value="{{ $list->approved_date }}">
                                </div>
                            </div>
                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label>Approver Status</label>
                                    <input disabled type="text" class="form-control" value="{{ main_status($list->status_approve) }}">
                                </div>
                            </div>
                        </div>
                        @if($list->approver_reason == NULL)

                        @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Approver Remarks</label>
                                    <textarea class="form-control" rows="2" disabled>{{$list->approver_reason}}</textarea>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>Checked By</label>
                                    <input disabled type="text" class="form-control" value="{{ get_name($list->reviewed_by) }}" >
                                </div>
                            </div>
                            <div class="col-md-4 px-1">
                                <div class="form-group">
                                    <label>Date Transacted</label>
                                    <input disabled type="text" class="form-control" value="{{ $list->reviewed_date }}" >
                                </div>
                            </div>
                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label>Reviewer Status</label>
                                    <input disabled type="text" class="form-control" value="{{ main_status($list->status_review) }}" >
                                </div>
                            </div>
                        </div>
                        @if($list->reviewer_reason == NULL)

                        @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Remarks of {{get_name($list->reviewed_by)}}</label>
                                    <textarea class="form-control" rows="2" disabled>{{$list->reviewer_reason}}</textarea>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>Prepared By</label>
                                    <input type="text" disabled class="form-control" id="prepare_by" name="prepare_by" value="{{ get_name($list->prepared_by) }}">
                                </div>
                            </div>
                            <div class="col-md-4 px-1">
                                <div class="form-group">
                                    <label>Prepared Date</label>
                                    <input type="text" disabled class="form-control" id="prepared_date" name="prepared_date" value="{{ date_con($list->prepared_date) }}">
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->id == $list->approved_by)
                        @if($list->status_review == 0)
                        <button type="button" class="btn btn-primary btn-fill disabled" disabled><i class="fas fa-thumbs-up"></i> Pending for Review</button>
                        @elseif($list->status_review == 1 AND $list->status == 0)
                        <button type="button" class="btn btn-success btn-fill transact" data-userrole="2" data-status="1" data-genid="{{$list->id}}"><i class="fas fa-thumbs-up"></i> Approve</button>
                        <button type="button" class="btn btn-danger btn-fill btn_remarks" data-toggle="modal" data-target=".remarks" data-userrole="2" data-status="2" data-genid="{{$list->id}}"><i class="fas fa-thumbs-down"></i> Disapprove</button>
                        @elseif($list->status == 1)
                        <button type="button" class="btn btn-success btn-fill disabled" disabled><i class="fas fa-thumbs-up"></i> Approved</button>
                        @else
                        <button type="button" class="btn btn-danger btn-fill disabled" disabled><i class="fas fa-thumbs-down"></i> Denied</button>
                        @endif
                        @elseif(Auth::user()->id == $list->reviewed_by)
                        @if($list->status_review == 1)
                        <button type="button" class="btn btn-success btn-fill disabled" disabled><i class="fas fa-thumbs-up"></i> Pending For Approval</button>
                        @elseif($list->status_review == 0)
                        <button type="button" class="btn btn-success btn-fill transact" data-userrole="3" data-status="1" data-genid="{{$list->id}}"><i class="fas fa-thumbs-up"></i> Approve</button>
                        <button type="button" class="btn btn-danger btn-fill btn_remarks" data-toggle="modal" data-target=".remarks" data-userrole="3" data-status="2" data-genid="{{$list->id}}"><i class="fas fa-thumbs-down"></i> Disapprove</button>
                        @else
                        <button type="button" class="btn btn-danger btn-fill disabled" disabled><i class="fas fa-thumbs-down"></i> Disapproved</button>
                        @endif
                        @elseif(Auth::user()->id == $list->prepared_by)
                        @if($list->status == 0)
                        <button type="button" class="btn btn-primary btn-fill disabled" disabled><i class="fas fa-thumbs-up"></i> Pending</button>
                        @elseif($list->status == 1)
                        <button type="button" class="btn btn-success btn-fill disabled" disabled><i class="fas fa-thumbs-up"></i> Approved</button>
                        @else
                        <button type="button" class="btn btn-danger btn-fill disabled" disabled><i class="fas fa-thumbs-down"></i> Disapproved</button>
                        @endif
                        @else
                        @endif
                        @endforeach
                        <!-- <div class="clearfix"></div> -->
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
                    <h4 class="card-title">List of employee</h4>
                    <form method="POST" action="{{url('Generate-List')}}" target="_blank">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$id}}">
                        <button class="btn btn-primary btn-fill">Generate Allowance Employee List</button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="transact_table" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <th>#</th>
                                    <th>Employee No.</th>
                                    <th>Employee Name</th>
                                    <th>Total of Days</th>
                                    <th>Cost Center</th>
                                    <th>Allowance</th>
                                    <th>Total Allowance</th>
                                </thead>
                                <tbody>
                                    @foreach ($emplist as $key => $list)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$list->EmpNo}}</td>
                                        <td>{{$list->EmpFirstName.' '.$list->EmpLastName}}</td>
                                        <td>{{$list->total_days}}</td>
                                        <td>{{get_costcode($list->CostCenterCode)}}</td>
                                        <td>₱{{currency($list->allowance)}}</td>
                                        <td><input readonly type="text" value="₱{{currency($list->total_allowance)}}"></td>
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

    <!-- Add Modal -->
    <div class="modal fade remarks" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="disapproved_content">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <h5 class="modal-title">Reason for disapproving this Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 px-1">
                                <div class="form-group">
                                    <textarea class="form-control" name="reasons" required placeholder="Remarks" rows="10"></textarea>
                                    <input type="hidden" name="status" id="status_dis">
                                    <input type="hidden" name="id" id="id">
                                    <input type="hidden" name="type" id="type">
                                    <input type="hidden" name="alltype" id="alltype">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary saveremarks">Proceed</button>
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
        $('#transact_table').DataTable();

        $('.btn_remarks').on('click',function() {
          var status = $(this).data('status');
          var id = $(this).data('genid');
          var type = $(this).data('userrole');
          var alltype = $('#all_type').val();

          $('#status_dis').val(status);
          $('#id').val(id);
          $('#type').val(type);
          $('#alltype').val(alltype);
      });

        $('.saveremarks').on('click',function(e) {
            e.preventDefault();
            var data = $('#disapproved_content').serialize();
            $.ajax({
                type:'POST',
                url:'{{  route('Approval-Section') }}',
                data:data,
                beforeSend:function() {
                    $('.loader').css('display','block');
                    $('.transact').prop('disabled',true);
                },
                success:function(data) {
                    $.each(data.message, function(index,value){
                        if(data.error){
                            toastr.error(value, 'Approval');
                            console.log(value);
                        }else{
                            if (data.approved) {
                                toastr.success(value, 'Approval');
                                window.setTimeout(function(){location.reload()},1000)
                            }
                            else{
                                toastr.error(value, 'Approval');
                                window.setTimeout(function(){location.reload()},1000)
                            }
                        }
                    });
                },
                complete:function(){
                    $('.loader').css('display','none');
                    $('.transact').prop('disabled',false);
                }
            });

        })

        $('.transact').on('click',function() {
            var status = $(this).data('status');
            var id = $(this).data('genid');
            var type = $(this).data('userrole');
            var alltype = $('#all_type').val();
            $.ajax({
                type:'GET',
                url:'{{  route('Approval-Section') }}',
                data:{status:status,id:id,type:type,alltype:alltype},
                beforeSend:function() {
                    $('.loader').css('display','block');
                    $('.transact').prop('disabled',true);
                },
                success:function(data) {
                    $.each(data.message, function(index,value){
                        if(data.error){
                            toastr.error(value, 'Approval');
                            console.log(value);
                        // window.setTimeout(function(){location.reload()},1000)
                    }else{
                        if (data.approved) {
                            toastr.success(value, 'Approval');
                            window.setTimeout(function(){location.reload()},1000)
                        }
                        else{
                            toastr.error(value, 'Approval');
                            window.setTimeout(function(){location.reload()},1000)
                        }
                    }
                });
                },
                complete:function(){
                    $('.loader').css('display','none');
                    $('.transact').prop('disabled',false);
                }
            });
        })
    </script>


    @stop
