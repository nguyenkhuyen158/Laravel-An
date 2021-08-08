@extends('admin-layout')
@section('h1-title')
Quản lý câu
@endsection


@section('css')
<!-- DataTables  & Plugins -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
<style type="text/css">
  mark {
    background-color: yellow !important;
  }
</style>
@endsection
@section('js')
<!-- DataTables  & Plugins -->
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>
{{-- <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/editable/bootstrap-table-editable.min.js"></script> --}}
<script type="text/javascript">
	var $table = $('#table')
	function operateFormatter(value, row, index) {
		return [
		'<a class="btn btn-primary btn-sm" href="javascript:edit()" title="Sửa">',
		'<i class="fas fa-edit fs-6"></i>',
		'</a>  ',
		'<a class="btn btn-danger btn-sm" href="javascript:void(0)" title="Xóa">',
		'<i class="fas fa-trash-alt fs-6"></i>',
		'</a>'
		].join('')
	}

	function edit(){
		// body...
	}

</script>


@endsection

@section('admin-content')
<div id="toolbar">
  <a class="" href="{{url('')}}/admin/add-topic" title="Thêm chủ đề">
  	<button type="button" class="btn btn-primary">
    <i class="fas fa-plus-square"></i>  Thêm chủ đề
    </button>
  </a>
  <a class="" href="{{url('')}}/admin/add-words" title="Thêm từ">
  	<button type="button" class="btn btn-primary">
    <i class="fas fa-plus-square"></i>  Thêm từ
    </button>
  </a>
</div>
<table
  id="table"
  data-toggle="table"
  data-search="true"
  data-show-refresh="true"
  data-sort-class="table-active"
  data-sortable="true"
  data-show-columns="true"
  data-show-toggle="true"
  data-mobile-responsive="true"
  data-editable = "true"
  data-pagination="true"
  data-search-highlight="true"
  data-editable-emptytext="true"
  
  data-url="{{url('')}}/admin/ajax/showsentences">
  <thead>
  <tr>
    <th data-field="id_sentence" data-sortable="true">ID sentence</th>
    <th data-field="sentence" data-editable="true" data-sortable="true">Sentence</th>
    <th data-field="sentence_v" data-editable="true" data-sortable="true">Câu</th>
    <th data-field="sentence_frequency" data-editable="true" data-sortable="true">Tần số</th>
    <th data-field="operate" data-formatter="operateFormatter" >Hành động</th>

  </tr>
  </thead>
</table>

@endsection
