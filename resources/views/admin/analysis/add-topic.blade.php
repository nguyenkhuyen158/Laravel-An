@extends('admin-layout')

@section('h1-title')
Thêm chủ đề
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
			<h3 class="card-title">Thêm chủ đề</h3>
		</div>
		<!-- /.card-header -->
		<!-- form start -->
		<form action="{{url('')}}/admin/save-topic" method="post">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label>Nguồn</label>
					<select name="id_parent" class="form-control">
						{!!$html_Select!!}
					</select>
				</div>
				<div class="form-group">
					<label for="source">Topic</label>
					<input type="text" name="topic" class="form-control" id="validationCustom01" placeholder="Topic" required>
				</div>
				<div class="form-group">
					<label for="source">Chủ đề</label>
					<input type="text" name="topic_v" class="form-control" id="validationCustom01" placeholder="chủ đề" required>
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
