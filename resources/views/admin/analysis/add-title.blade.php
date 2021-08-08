@extends('admin-layout')

@section('h1-title')
Thêm tiêu đề
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
			<h3 class="card-title">Thêm tiêu đề</h3>
		</div>
		<!-- /.card-header -->
		<!-- form start -->
		<form action="{{url('')}}/admin/save-title" method="post">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label for="source">Chủ đề</label>
					<select name="id_topic" class="form-control">
						{!!$html_Select!!}
					</select>
				</div>
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" name="title" class="form-control" id="validationCustom01" placeholder="Title" required>
				</div>
				<div class="form-group">
					<label for="title_v">Tiêu đề</label>
					<input type="text" name="title_v" class="form-control" id="validationCustom01" placeholder="Tiêu đề" required>
				</div>
				<div class="form-group">
					<label for="source">URL</label>
					<input type="url" name="url" class="form-control" id="validationCustom01" placeholder="URL" required>
				</div>
				<div class="form-group">
					<label for="content">Nội dung</label>
					<textarea name="content" class="form-control" id="validationCustom01" placeholder="Nội dung"></textarea>
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
