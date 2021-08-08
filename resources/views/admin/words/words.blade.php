@extends('admin-layout')
@section('h1-title')
Quản lý từ
@endsection


@section('css')
<!-- DataTables  & Plugins -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

<style type="text/css">
	mark {
		background-color: yellow;
	}
</style>
@endsection
@section('js')
<!-- DataTables  & Plugins -->
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>
{{-- <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/editable/bootstrap-table-editable.min.js"></script> --}}

{{-- <script src="{{url('src')}}/plugins/summernote/summernote-bs4.min.js"></script> --}}


<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script type="text/javascript">

  window.addEventListener('show_form', event => {
    $('#editModel').modal('show');
  })

  window.addEventListener('show_form_bookmark', event => {
    $('#bookmarkModel').modal('show');
  })

  window.addEventListener('hide_form', event => {
    $('#editModel').modal('hide');
    $('#bookmarkModel').modal('hide');
    $('.modal-backdrop').remove();

  })

  var $table = $('#table');
  // $(document).ready(function() {
  //       $a =  $('#summernote').summernote();
  //       ‎Console.log($a);
  //   });
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
<br>
<livewire:admin.listword />

{{-- <table
  id="table"
  data-toggle="table"
  data-search="true"
  data-sort-class="table-active"
  data-sortable="true"
  data-show-refresh="true"
  data-show-columns="true"
  data-show-toggle="true"
  data-mobile-responsive="true"
  data-editable = "true"
  data-pagination="true"
  data-editable-emptytext="true"
  
  data-url="{{url('')}}/admin/ajax/showwords">
  <thead>
  <tr>
    <th data-field="id_word" data-sortable="true">ID word</th>
    <th data-field="word" data-editable="true" data-sortable="true">Word</th>
    <th data-field="word_v" data-editable="true" data-sortable="true">Từ vựng</th>
    <th data-field="word_frequency" data-editable="true" data-sortable="true">Tần số</th>
    <th data-field="phonetics_us" data-editable="true" data-sortable="true">Phonetics US</th>
    <th data-field="phonetics_uk" data-editable="true" data-sortable="true">Phonetics UK</th>
    <th data-field="phonetics_uk" data-editable="true" data-sortable="true">Phonetics UK</th>
    <th data-field="operate" data-formatter="operateFormatter" >Hành động</th>

  </tr>
  </thead>
</table> --}}
<textarea id="codeMirrorDemo" placeholder="Từ điển"></textarea>
@endsection
