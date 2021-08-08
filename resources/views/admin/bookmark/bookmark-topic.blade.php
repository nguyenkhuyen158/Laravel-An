@extends('admin-layout')
@section('h1-title')
Chủ đề Bookmark
@endsection


@section('css')

@endsection
@section('js')

<script type="text/javascript">
	window.addEventListener('show_form', event => {
		$('#topicModel').modal('show');
	})
	window.addEventListener('hide_form', event => {
		$('#topicModel').modal('hide');
		$('.modal-backdrop').remove();

	})

</script>


@endsection

@section('admin-content')
<livewire:admin.bookmark-topic-livewire />
@endsection
