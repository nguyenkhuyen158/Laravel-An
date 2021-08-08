@extends('admin-layout')

@section('h1-title')
Thêm chủ đề
@endsection

@section('js')
<script type="text/javascript">
$('textarea').on("change paste keyup cut select",function() {    
    var characterCount = $(this).val().length,
        current_count = $('#current_count'),
        maximum_count = $('#maximum_count'),
        count = $('#count');
        current_count.text(characterCount);    
        // if (characterCount > 15000) {
        // 	$('button').attr("disabled", true);
        // }else(
        // 	$('button').attr("disabled", false)
        // )
});
</script>
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
			<h3 class="card-title">Thêm từ vựng</h3>
		</div>
		<!-- /.card-header -->
		<!-- form start -->
		<form action="{{url('')}}/admin/save-words" method="post">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label>Chủ đề</label>
					<select name="id_title" class="form-control">
						{!!$html_Select!!}
					</select>
				</div>
				<div class="form-group">
					<label for="source">Đoạn văn</label>
					<textarea name="text" class="form-control" id="validationCustom01" placeholder="Topic" style="height: 250px;" required></textarea>
					<div id="count" class="text-right">
			            <span id="current_count">0</span>
			            <span id="maximum_count">/ 15000</span>
			        </div>
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
