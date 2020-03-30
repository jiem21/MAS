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
                    <h4 class="card-title">Canteen Code List</h4>
                    <button class="btn btn-primary btn-fill" data-toggle="modal" data-target=".canteen_add">Add Canteen</button>
                </div>
                <div class="card-body table-full-width table-responsive">
                    <table id="empactive" class="table table-striped table-bordered table-hover">
                        <thead>
                            <th>#</th>
                            <th>Canteen Code</th>
                            <th>Canteen Name</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($canteen as $key => $canteens)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $canteens->canteencode }}</td>
                                <td>{{ $canteens->canteenname}}</td>
                                <td>{{ canteen_status($canteens->status)}}</td>
                                <td>{{ $canteens->created_by }}</td>
                                <td><button class="btn btn-primary canview" data-toggle="modal" data-target=".canteen_upd" data-id="{{$canteens->id}}"><i class="far fa-eye"></i></button></td>
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
<div class="modal fade canteen_add" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="save_canteen">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Add Canteen Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>Canteen Code</label>
                                <input name="code" type="text" class="form-control" required placeholder="Canteen Code">
                            </div>
                        </div>
                        <div class="col-md-9 px-1">
                            <div class="form-group">
                                <label>Canteen Name</label>
                                <input name="name" type="text" class="form-control" required placeholder="Canteen Name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary savecancode">Register Canteen Code</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End modal -->

<!-- update Modal -->
<div class="modal fade canteen_upd" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="update_canteen">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Add Canteen Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 pr-1">
                            <div class="form-group">
                                <label>Canteen Code</label>
                                <input name="code" id="code" type="text" class="form-control" required placeholder="Canteen Code">
                                <input name="id" id="id" type="hidden" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 px-1">
                            <div class="form-group">
                                <label>Canteen Name</label>
                                <input name="name" id="name" type="text" class="form-control" required placeholder="Canteen Name">
                            </div>
                        </div>
                        <div class="col-md-4 pl-1">
                            <div class="form-group">
                                <label>Canteen Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option selected disabled>Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary updcancode">Update Canteen Code</button>
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
    $('#empactive').DataTable();

    $('.savecancode').click(function() {
        event.preventDefault();
        var data = $('#save_canteen').serialize();
        $.ajax({
            type:'POST',
            url:'{{  route('saveCanteen') }}',
            data:data,
            success:function(data) {
                $.each(data.message, function(index,value){
                    if(data.error){
                        toastr.error(value, 'Canteen');
                    }else{
                        toastr.success(value, 'Canteen');
                        window.setTimeout(function(){location.reload()},2000)
                    }
                });
            }
        });
    });


      $('.updcancode').click(function() {
        event.preventDefault();
        var data = $('#update_canteen').serialize();
        $.ajax({
            type:'POST',
            url:'{{  route('upcanteen') }}',
            data:data,
            success:function(data) {
                $.each(data.message, function(index,value){
                    if(data.error){
                        toastr.error(value, 'Update Canteen');
                    }else{
                        toastr.success(value, 'Update Canteen');
                        window.setTimeout(function(){location.reload()},2000)
                    }
                });
            }
        });
    });

    $('#empactive').on('click','.canview',function() {
        var id = $(this).attr('data-id');
            $.ajax({
                type:'get',
                url:'{{  route('getdetailsCan') }}',
                data:{id:id},
                success:function(data) {
                    $.each(data.details, function(index,value){
                        if(data.valid){
                            $('#code').val(value.canteencode);
                            $('#id').val(value.id);
                            $('#name').val(value.canteenname);
                            $('#status').val(value.status);
                        }
                    });
                }
            });
    })
</script>


@stop
