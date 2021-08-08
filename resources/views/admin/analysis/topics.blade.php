@extends('admin-layout')
@section('h1-title')
Quản lý chủ đề
@endsection


@section('css')
<!-- DataTables  & Plugins -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
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

  window.addEventListener('show_form', event => {
    $('#sourceModel').modal('show');
  })
  window.addEventListener('hide_form', event => {
    $('#sourceModel').modal('hide');
    $('.modal-backdrop').remove();

  })
  window.addEventListener('table_refresh', event => {
    $('#table').bootstrapTable('refresh');
  })
  

</script>


@endsection

@section('admin-content')

<!-- Modal Thêm nguồn -->
<livewire:admin.add-source />
<!-- End Modal Thêm nguồn -->




<table
  id="table"
  data-toggle="table"
  data-search="true"
  data-sort-class="table-active"
  data-sortable="true"
  data-show-columns="true"
  data-show-toggle="true"
  data-mobile-responsive="true"
  data-editable = "true"
  data-editable-emptytext="true"
  
  data-url="{{url('')}}/admin/ajax/showtopics">
  <thead>
  <tr>
    <th data-field="id_topic" data-sortable="true">ID topic</th>
    <th data-field="topic" data-editable="true" data-sortable="true">Topic</th>
    <th data-field="topic_v" data-editable="true" data-sortable="true">Chủ đề</th>
    <th data-field="id_parent" data-editable="true" data-sortable="true">Thuộc chủ đề</th>
    <th data-field="operate" data-formatter="operateFormatter" >Hành động</th>

  </tr>
  </thead>
</table>

@endsection
