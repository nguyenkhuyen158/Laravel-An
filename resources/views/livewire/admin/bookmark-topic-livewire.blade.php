<div>
    <div id="toolbar">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#topicModel">
            <i class="fas fa-plus-square"></i>  Thêm chủ đề
        </button>
    </div>
    <br>
    <table class="table table-hover">
        <thead>
            <tr>
                <th wire:click="setOrderField('id')">
                    ID
                    @if ($order_by == "id_bookmark_topic")
                        {!!$order_desc ? '<i class="fas fa-sort-down"></i>':'<i class="fas fa-sort-up"></i>'!!}
                    @endif
                </th>
                <th wire:click="setOrderField('bookmark_topic')">
                    Topic
                    @if ($order_by == "bookmark_topic")
                        {!!$order_desc ? '<i class="fas fa-sort-down"></i>':'<i class="fas fa-sort-up"></i>'!!}
                    @endif
                </th>
                <th wire:click="setOrderField('bookmark_topic_vn')">
                    Chủ đề
                    @if ($order_by == "bookmark_topic_vn")
                        {!!$order_desc ? '<i class="fas fa-sort-down"></i>':'<i class="fas fa-sort-up"></i>'!!}
                    @endif
                </th>
                <th wire:click="setOrderField('id_parent')">
                    Thuộc chủ đề
                    @if ($order_by == "id_parent")
                        {!!$order_desc ? '<i class="fas fa-sort-down"></i>':'<i class="fas fa-sort-up"></i>'!!}
                    @endif
                </th>
                <th>
                    Hành động
                </th>
            </tr>
        </thead>
        <tbody>
            @if ($bookmarkTopics->count() > 0)
                @foreach ($bookmarkTopics as $bookmarkTopic)
                <tr>
                    <td>
                        {{$bookmarkTopic->id}}
                    </td>
                    <td>
                        {{$bookmarkTopic->bookmark_topic}}
                    </td>
                    <td>
                        {{$bookmarkTopic->bookmark_topic_vn}}
                    </td>
                    <td>
                        {{$bookmarkTopic->id_parent}}
                    </td>
                    <td>
                        <a wire:click.prevent="edit({{$bookmarkTopic}})" class="btn btn-primary btn-sm" title="Sửa"><i class="fas fa-edit fs-6"></i></a>
                        <a wire:click.prevent="export({{$bookmarkTopic->id}})" class="btn btn-primary btn-sm" title="Sửa"><i class="fas fa-file-export fs-6"></i></i></a>
                    </td>
                </tr>
                @endforeach
            @else
            <tr >
                <td class="align-middle" colspan="8">
                Không tìm thấy kết quả
                </td>
            </tr>
                
            @endif
        </tbody>
    </table>
    <!-- Modal Thêm chủ đề -->
    <div class="modal" id="topicModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Thêm Nguồn</h5>
                    <button type="button" wire:click="resetInput()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" autocomplete="on" wire:submit="createTopic()">
                    <div class="modal-body">
                        @csrf
                        @if (session()->has('message'))
                        <div class="alert {{session('class_message')}}">
                            {{ session('message') }}
                        </div>
                        @endif
                        <input type="hidden" wire:model="data.id" name="topic" class="form-control" id="validationCustom01" placeholder="Topic">
                        <div class="form-group">
                            <label>Nguồn</label>
                            <select wire:model="data.id_parent" name="id_parent" class="form-control">
                                <option value="null" selected disabled hidden>Chọn nguồn, chủ đề</option>
                                <option value="1" >1</option>
                                <option value="2" >2</option>
                                {{-- {!!$html_Select!!} --}}
                            </select>
                            @error('data.id_parent')
                            <span style="color: #dc3545;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="topic">Topic</label>
                            <input type="text" wire:model="data.bookmark_topic" name="topic" class="form-control" id="validationCustom01" placeholder="Topic" required>
                            @error('data.bookmark_topic')
                            <span style="color: #dc3545;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="topic_v">Chủ đề</label>
                            <input type="text" wire:model="data.bookmark_topic_vn" name="topic_v" class="form-control" id="validationCustom01" placeholder="chủ đề" required>
                            @error('data.bookmark_topic_vn')
                            <span style="color: #dc3545;">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="resetInput()" class="btn btn-secondary" data-bs-dismiss="modal">Trở lại</button>
                        <button type="button" wire:click="createTopic()" class="btn btn-primary">Thêm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal Thêm chủ đề -->
</div>
