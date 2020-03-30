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
                    <button class="btn btn-primary btn-fill" data-toggle="modal" data-target=".costcode_save_form">Add Cost Center Code</button>
                </div>
                <div class="card-body table-full-width table-responsive">
                    <div class="row text-center mx-auto">
                        <div class="col-md-10 mx-auto">
                            <table id="tablecenter" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <th>#</th>
                                    <th>Process Name</th>
                                    <th>Cost Center</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach ($costcode as $key => $cost)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $cost->process_name }}</td>
                                        <td>{{ $cost->costcode }}</td>
                                        <td>{{ cost_status($cost->status) }}</td>
                                        <td><a class="btn btn-primary views" data-toggle="modal" data-target=".costcode_edit_form" href="#" data-oldcode="{{ $cost->costcode }}"><i class="fas fa-pencil-alt"></i> Edit</a></td>
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

<!-- Add Modal -->
<div class="modal fade costcode_save_form" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="save_code">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Add Cost Center Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 pr-1">
                            <div class="form-group">
                                <label>Process Name</label>
                                <input name="process_name" id="process_name" type="text" class="form-control" required placeholder="Process Name">
                            </div>
                        </div>
                        <div class="col-md-4 px-1">
                            <div class="form-group">
                                <label>Cost Center Code</label>
                                <input name="costcode" id="costcode" type="text" class="form-control" required placeholder="Cost Center Code">
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" disabled class="btn btn-primary regcode">Register Cost Code</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End modal -->

<!-- Update Modal -->
<div class="modal fade costcode_edit_form" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="update_code">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Update Cost Center Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>Process Name</label>
                                <input name="processname" id="processname" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3 px-1">
                            <div class="form-group">
                                <label>Current Cost Center Code</label>
                                <input name="costcode_new" id="costcode_new" type="text" class="form-control" required placeholder="Cost Center Code">
                                <input name="costcode_old" id="costcode_old" type="hidden">
                                <input name="costcode_id" id="costcode_id" type="hidden">
                            </div>
                        </div>
                        <div class="col-md-3 pl-1">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="code_status" id="code_status">
                                    <option disabled>Select Code Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Obsolete</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary upcode">Update Cost Code</button>
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
 $('#tablecenter').DataTable();


 $('#costcode').on('blur',function(e) {
    var code = $(this).val();
    $.ajax({
        type:'GET',
        url:'{{route('validate-code')}}',
        data:{code:code},
        success:function(data) {
            $.each(data.message, function(index,value){
                if(data.error){
                    toastr.error(value, 'Cost Center Code');
                    $('#costcode').removeClass('valid');
                    $('#costcode').addClass('invalid');
                    $('.regcode').prop('disabled',true);
                }else{
                    toastr.success(value, 'Cost Center Code');
                    $('#costcode').removeClass('invalid');
                    $('#costcode').addClass('valid');
                    $('.regcode').prop('disabled',false);
                }
            });
        }
    });
});

$('#process_name').on('blur',function(e) {
    var name = $(this).val();
    $.ajax({
        type:'GET',
        url:'{{route('validate-name')}}',
        data:{name:name},
        success:function(data) {
            $.each(data.message, function(index,value){
                if(data.error){
                    toastr.error(value, 'Cost Center Code');
                    $('#process_name').removeClass('valid');
                    $('#process_name').addClass('invalid');
                    $('.regcode').prop('disabled',true);
                }else{
                    toastr.success(value, 'Cost Center Code');
                    $('#process_name').removeClass('invalid');
                    $('#process_name').addClass('valid');
                    $('.regcode').prop('disabled',false);
                }
            });
        }
    });
});

$('.regcode').click(function(event){
            event.preventDefault();
            var data = $('#save_code').serialize();
            $.ajax({
                type:'POST',
                url:'{{  route('saveCode') }}',
                data:data,
                success:function(data) {
                    $.each(data.message, function(index,value){
                        if(data.error){
                            toastr.error(value, 'Cost Center Code');
                        }else{
                            toastr.success(value, 'Cost Center Code');
                            window.setTimeout(function(){location.reload()},2000);
                        }
                    });
                }
            });
        });

$('.upcode').click(function(event){
            event.preventDefault();
            var data = $('#update_code').serialize();
            $.ajax({
                type:'POST',
                url:'{{  route('updateCode') }}',
                data:data,
                success:function(data) {
                    $.each(data.message, function(index,value){
                        if(data.error){
                            toastr.error(value, 'Cost Center Code');
                        }else{
                            toastr.success(value, 'Cost Center Code');
                            window.setTimeout(function(){location.reload()},500)
                        }
                    });
                }
            });
        });

$('#tablecenter').on('click','.views',function() {
    var code = $(this).attr('data-oldcode');
    $.ajax({
        type:'get',
        url:'{{  route('code-details') }}',
        data:{code:code},
        success:function(data) {
            $.each(data.code, function(index,value){
                if(data.valid){
                    $('#costcode_id').val(value.id);
                    $('#processname').val(value.process_name);
                    $('#costcode_new').val(value.costcode);
                    $('#costcode_old').val(value.costcode);
                    $('#code_status').val(value.status);
                }
            });
        }
    });
});
</script>


@stop
