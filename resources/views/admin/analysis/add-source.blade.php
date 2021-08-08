@extends('admin-layout')

@section('h1-title')
Thêm nguồn tài liệu
@endsection

@section('admin-content')
<div class="row justify-content-center">
	<!-- general form elements -->


	@if (isset($data_blade['message']))
		<div class="col-10 col-md-8 alert {{$data_blade['class_message']}}" role="alert">
			{{$data_blade['message']}}
		</div>
	@endif
	<div class="col-10 col-md-8 card card-primary">

		<div class="card-header">
			<h3 class="card-title">Thêm nguồn</h3>
		</div>
		<!-- /.card-header -->
		<!-- form start -->
		<form action="{{url('')}}/admin/save-source" method="post">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label for="source">Nguồn</label>
					<input type="text" name="source" class="form-control" id="validationCustom01" placeholder="nguồn" autocomplete="on" >
				</div>
				<!-- /.card-body -->
				<div class="card-footer text-center">
					<button type="submit" class="btn btn-primary btn-block">Thêm</button>
				</div>
			</div>
		</form>
		<!-- /.card -->
	</div>
</div>
@endsection
