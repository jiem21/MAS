@extends('layouts.app')
@section('customcss')
<style type="text/css">
	.card .table tbody td:last-child, .card .table thead th:last-child {
		padding-right: 15px;
		display: table-cell;
	}
	.hide{
		display: none;
	}

</style>
@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card strpied-tabled-with-hover">
				<div class="card-header ">
					<h4 class="card-title">User List</h4>
					<button class="btn btn-primary btn-fill" data-toggle="modal" data-target=".bd-example-modal-lg">Add User</button>
				</div>
				<div class="card-body table-full-width table-responsive">
					<table id="myTable" class="table table-striped table-bordered table-hover">
						<thead>
							<th>#</th>
							<th>Username</th>
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
							<th>Edit</th>
							<th>Delete</th>
						</thead>
						<tbody>
							@foreach ($users as $key => $user)
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ $user->username }}</td>
								<td>{{ $user->name }}</td>
								<td>{{ $user->email }}</td>
								<td>{{ role($user->role) }}</td>
								<td class="text-center"><button class="btn btn-primary useredit" data-toggle="modal" data-target=".upmodal" id="{{ $user->id }}"><i class="fas fa-pen"></i></button></td>
								<td class="text-center"><button class="btn btn-danger userdelete" data-toggle="modal" data-target=".delmodal" id="{{ $user->id }}"><i class="fas fa-trash-alt"></i></button></td>
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
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="save_employee">
				{{ csrf_field() }}
				<div class="modal-header">
					<h5 class="modal-title">Add Account</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 pr-1">
							<div class="form-group">
								<label>Name</label>
								<input name="fullname" type="text" class="form-control" required placeholder="Fullname">
							</div>
						</div>
						<div class="col-md-6 pl-1">
							<div class="form-group">
								<label>Email</label>
								<input name="email" type="email" class="form-control" required placeholder="Email Address">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 pr-1">
							<div class="form-group">
								<label>Username</label>
								<input name="username" type="text" class="form-control" required placeholder="Username">
							</div>
						</div>
						<div class="col-md-4 pr-1">
							<div class="form-group">
								<label>Password</label>
								<input name="password" type="password" class="form-control" required placeholder="Password">
							</div>
						</div>
						<div class="col-md-4 pr-1">
							<div class="form-group">
								<label>Account Role</label>
								<select class="form-control" name="role" required>
									<option disabled selected>Select Account Role</option>
									<option class="{{(Auth::user()->role == 1)? 'show':'hide'}}" value="1">Admin</option>
									<option value="2">Approver</option>
									<option value="3">Reviewer</option>
									<option value="4">General User</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary regaccnt">Register Account</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End modal -->


<!-- delete Modal -->
<div class="modal fade bd-example-modal-lg delmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="delete_employee">
				{{ csrf_field() }}
				<div class="modal-header">
					<h5 class="modal-title">Delete Account</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 pr-1">
							<div class="form-group">
								<label>Are you sure you want to delete this user? <b><i><span id="userid"></span></i></b></label>
								<input type="hidden" name="id" id="del_id">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger delaccnt">Yes</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Modal -->

<!-- Update Modal -->
<div class="modal fade bd-example-modal-lg upmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="update_employee">
				{{ csrf_field() }}
				<div class="modal-header">
					<h5 class="modal-title">Update Account</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 pr-1">
							<div class="form-group">
								<label>Name</label>
								<input type="hidden" name="id" id="up_id">
								<input id="up_fullname" name="fullname" type="text" class="form-control" required placeholder="Fullname">
							</div>
						</div>
						<div class="col-md-6 pl-1">
							<div class="form-group">
								<label>Email</label>
								<input id="up_email" name="email" type="email" class="form-control" required placeholder="Email Address">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 pr-1">
							<div class="form-group">
								<label>Username</label>
								<input id="up_username" name="username" type="text" class="form-control" required placeholder="Username" disabled>
							</div>
						</div>
						<div class="col-md-6 pr-1">
							<div class="form-group">
								<label>Account Role</label>
								<select class="form-control" id="up_role" name="role" required>
									<option disabled selected>Select Account Role</option>
									<option value="1">Admin</option>
									<option value="2">Approver</option>
									<option value="3">Reviewer</option>
									<option value="4">General User</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary updtaccnt">Update Account</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Modal -->

@stop

@section('customjs')
<script type="text/javascript">
	$('#myTable').DataTable();
	$(document).ready(function() {
		$('.regaccnt').click(function(event){
			event.preventDefault();
			var data = $('#save_employee').serialize();
			$.ajax({
				type:'POST',
				url:'{{  route('usersave') }}',
				data:data,
				success:function(data) {
					$.each(data.message, function(index,value){
						if(data.error){
							toastr.error(value, 'User maintenance');
						}else{
							toastr.success(value, 'User maintenance');
							window.setTimeout(function(){location.reload()},2000)
						}
					});
				}
			});
		});

		$('#myTable').on('click','.userdelete',function() {
			var id = $(this).attr('id');
			$.ajax({
				type:'get',
				url:'{{  route('user-details') }}',
				data:{id:id},
				success:function(data) {
					$.each(data.info, function(index,value){
						if(data.valid){
							$('#del_id').val(value.id);
							$('#userid').text(value.name);
						}
					});
				}
			});
		});

		$('#myTable').on('click','.useredit',function() {
			var id = $(this).attr('id');
			$.ajax({
				type:'get',
				url:'{{  route('user-details') }}',
				data:{id:id},
				success:function(data) {
					$.each(data.info, function(index,value){
						if(data.valid){
							$('#up_id').val(value.id);
							$('#up_fullname').val(value.name);
							$('#up_email').val(value.email);
							$('#up_username').val(value.username);
							$('#up_role').val(value.role);
						}
					});
				}
			});
		});

		$('.updtaccnt').click(function(event){
			event.preventDefault();
			var data = $('#update_employee').serialize();
			$.ajax({
				type:'POST',
				url:'{{  route('userupdate') }}',
				data:data,
				success:function(data) {
					$.each(data.message, function(index,value){
						if(data.error){
							toastr.error(value, 'User maintenance');
						}else{
							toastr.success(value, 'User maintenance');
							window.setTimeout(function(){location.reload()},2000)
						}
					});
				}
			});
		});

		$('.delaccnt').click(function(event){
			event.preventDefault();
			var data = $('#delete_employee').serialize();
			$.ajax({
				type:'POST',
				url:'{{  route('userdelete') }}',
				data:data,
				success:function(data) {
					$.each(data.message, function(index,value){
						if(data.error){
							toastr.error(value, 'User maintenance');
						}else{
							toastr.success(value, 'User maintenance');
							window.setTimeout(function(){location.reload()},2000)
						}
					});
				}
			});
		});
	});
</script>
@stop
