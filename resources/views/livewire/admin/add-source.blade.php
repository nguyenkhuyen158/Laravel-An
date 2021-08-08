<div>
<div id="toolbar">
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sourceModel">
  <i class="fas fa-plus-square"></i>  Thêm nguồn
</button>
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#topicModel">
  <i class="fas fa-plus-square"></i>  Thêm chủ đề
</button>
</div>

<!-- Modal Thêm nguồn -->
<div class="modal" id="sourceModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Thêm Nguồn</h5>
                <button type="button" wire:click="resetInput()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" autocomplete="on">
                <div class="modal-body">
                    @csrf
                    @if (session()->has('message'))
                    <div class="alert {{session('class_message')}}">
                        {{ session('message') }}
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="source">Nguồn</label>
                        <input type="text" wire:model.debounce.500ms="source" class="form-control" placeholder="nguồn" required>
                        @error('source')
                        <span style="color: #dc3545;">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="resetInput()" class="btn btn-secondary" data-bs-dismiss="modal">Trở lại</button>
                    <button type="button" wire:click="create_source()" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Thêm nguồn -->

<!-- Modal Thêm chủ đề -->
<div class="modal" id="topicModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Thêm Nguồn</h5>
                <button type="button" wire:click="resetInput()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" autocomplete="on" wire:submit="create_topic()">
                <div class="modal-body">
                    @csrf
                    @if (session()->has('message'))
                    <div class="alert {{session('class_message')}}">
                        {{ session('message') }}
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Nguồn</label>
                        <select wire:model.debounce.500ms="id_parent" name="id_parent" class="form-control">
                            <option value="null" selected disabled hidden>Chọn nguồn, chủ đề</option>
                            {!!$html_Select!!}
                        </select>
                        @error('id_parent')
                        <span style="color: #dc3545;">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="topic">Topic</label>
                        <input type="text" wire:model.debounce.500ms="topic" name="topic" class="form-control" id="validationCustom01" placeholder="Topic" required>
                        @error('topic')
                        <span style="color: #dc3545;">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="topic_v">Chủ đề</label>
                        <input type="text" wire:model.debounce.500ms="topicv" name="topic_v" class="form-control" id="validationCustom01" placeholder="chủ đề" required>
                        @error('topicv')
                        <span style="color: #dc3545;">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="resetInput()" class="btn btn-secondary" data-bs-dismiss="modal">Trở lại</button>
                    <button type="button" wire:click="create_topic()" class="btn btn-primary">Thêm</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Modal Thêm chủ đề -->
</div>