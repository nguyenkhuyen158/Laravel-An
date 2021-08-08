<div>
    <div>
        
    </div>
    <table data-mobile-responsive="true" class="table table-hover">
        <thead>
            <tr>
                <td>
                    <button wire:click="reload()" class="btn btn-secondary"><i class="fas fa-sync-alt"></i></button>
                </td>
                <td>
                    <select wire:model="paginate" class="form-select">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </td>
                <td>
                    <input wire:model.debounce.500ms="filters.word" type="text" class="form-control w-100" style="with:100%" placeholder="Tìm kiếm word...">        
                </td>
                <td>
                    <input wire:model.debounce.500ms="filters.word_v" type="text" class="form-control w-100" placeholder="Tìm kiếm từ...">        
                </td>
                <td></td>
                <td>
                    <input wire:model.debounce.500ms="filters.phonetics_us" type="text" class="form-control w-100" placeholder="Tìm kiếm phonetics...">        
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th>
                    <input type="checkbox" name="">
                </th>
                <th wire:click="setOrderField('id_word')">
                    ID
                    @if ($orderBy == "id_word")
                        {!!$orderDesc ? '<i class="fas fa-sort-down"></i>':'<i class="fas fa-sort-up"></i>'!!}
                    @endif
                </th>
                <th wire:click="setOrderField('word')">
                    Word
                    @if ($orderBy == "word")
                        {!!$orderDesc ? '<i class="fas fa-sort-up"></i>':'<i class="fas fa-sort-down"></i>'!!}
                    @endif
                </th>
                <th wire:click="setOrderField('word_v')">
                    Từ vựng
                    @if ($orderBy == "word_v")
                        {!!$orderDesc ? '<i class="fas fa-sort-up"></i>':'<i class="fas fa-sort-down"></i>'!!}
                    @endif
                </th>
                <th wire:click="setOrderField('word_frequency')">
                    Tần số
                    @if ($orderBy == "word_frequency")
                        {!!$orderDesc ? '<i class="fas fa-sort-up"></i>':'<i class="fas fa-sort-down"></i>'!!}
                    @endif
                </th>
                <th wire:click="setOrderField('phonetics_us')">
                    US
                    @if ($orderBy == "phonetics_us")
                        {!!$orderDesc ? '<i class="fas fa-sort-up"></i>':'<i class="fas fa-sort-down"></i>'!!}
                    @endif
                </th>
                <th wire:click="setOrderField('phonetics_uk')">
                    UK
                    @if ($orderBy == "phonetics_uk")
                        {!!$orderDesc ? '<i class="fas fa-sort-up"></i>':'<i class="fas fa-sort-down"></i>'!!}
                    @endif
                </th>
                <th>
                    Hành động
                </th>
            </tr>
        </thead>
        <tbody>
            @if ($words->count() > 0)
                
            @foreach ($words as $word)
            <tr>
                <td>
                    <input wire:modl="checked" type="checkbox" value="{{$word->id_word}}">
                </td>
                <td>
                    {{$word->id_word}}
                </td>
                <td>
                    {{$word->word}}
                </td>
                <td>
                    {{$word->word_v}}
                </td>
                <td>
                    {{$word->word_frequency}}
                </td>
                <td>
                    @if ($word->audio_us && $word->audio_us != 'null')
                    <audio id="audio_us_{{$word->id_word}}" src="{{$word->audio_us}}"></audio>
                    <a class="btn btn-outline-primary btn-sm" onclick="document.getElementById('audio_us_{{$word->id_word}}').play()"><i class='fa fa-volume-up fs-6'></i></a>
                    @endif
                    {{$word->phonetics_us}}
                </td>
                <td>
                    @if ($word->audio_uk && $word->audio_uk != 'null')
                    <audio id="audio_uk_{{$word->id_word}}" src="{{$word->audio_uk}}"></audio>
                    <a class="btn btn-outline-primary btn-sm" onclick="document.getElementById('audio_uk_{{$word->id_word}}').play()"><i class='fa fa-volume-up fs-6'></i></a>
                    @endif
                    {{$word->phonetics_uk}}
                </td>
                <td>
                    <a wire:click.prevent="bookmarkModel({{$word->id_word}})" class="btn btn-outline-warning btn-sm" title="Bookmark"><i class="far fa-bookmark"></i></a>

                    {{-- <a wire:click.prevent="bookmark({{$word->id_word}})" class="btn btn-outline-warning btn-sm" title="Bookmark"><i class="fas fa-bookmark"></i></i></a> --}}


                    <a wire:click.prevent="edit({{$word->id_word}})" class="btn btn-primary btn-sm" title="Sửa"><i class="fas fa-edit fs-6"></i></a>
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
    <div>
        {!! $words->links() !!}
    </div>

    <!-- Modal Thêm nguồn -->
    <div class="modal" id="editModel" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" {{-- style="display: block; padding-left: 0px;" --}} aria-labelledby="staticBackdropLabel" aria-hidden="true" {{-- wire:ignore.self --}}>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Sửa từ</h5>
                    <button type="button"  wire:click.prevent="resetInput()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" autocomplete="on">
                    <div class="modal-body">
                        @csrf

                        <div class="form-group">
                            <label for="source">Word</label>
                            <input wire:model="state.word" type="text" class="form-control" placeholder="Word" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="source">Từ vựng</label>
                            <input wire:model="state.word_v" type="text" class="form-control" placeholder="Từ vựng" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="source">Tần số</label>
                            <input wire:model="state.word_frequency" type="number" class="form-control" placeholder="Tần số" required>
                          
                        </div>
                        <div class="form-group">
                            <label>Lấy audio từ</label>
                            <select wire:model="audiofromDic" {{-- wire:change="changeDic()"  --}}class="form-control">
                                <option value="null">Để nguyên</option>
                                <option value="Oxford">Oxford</option>
                                <option value="Cambridge">Cambridge</option>
                                <option value="Lexico">Lexico</option>
                            </select>
                            {{$audiofromDic}}
                            <div class="form-group">
                                <label for="source">Phonetics US</label>
                                <input wire:model="state.phonetics_us" value="state.phonetics_us" type="text" class="form-control" placeholder="Phonetics US" required>
                              
                            </div>
                            <div class="form-group">
                                <label for="source">Aduio US</label>
                                <input wire:model="state.audio_us" type="text" class="form-control" placeholder="Aduio US" required>
                              
                            </div>
                            <div class="form-group">
                                <label for="source">Phonetics UK</label>
                                <input wire:model="state.phonetics_uk" type="text" class="form-control" placeholder="Phonetics UK" required>
                              
                            </div>
                            <div class="form-group">
                                <label for="source">Aduio UK</label>
                                <input wire:model="state.audio_uk" type="text" class="form-control" placeholder="Aduio UK" required>
                    
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="source">Từ điển</label>
                            <textarea id="summernote" wire:model="state.dictionary" class="form-control" placeholder="Từ điển"></textarea>
                
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click.prevent="resetInput()" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Trở lại</button>
                        <button wire:click.prevent="luuWord()" type="button" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Thêm nguồn -->

    <!-- Modal Bookmark -->
    <div class="modal" id="bookmarkModel" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" {{-- style="display: block; padding-left: 0px;" --}} aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Bookmark</h5>
                    <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" autocomplete="on">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label>Chủ đề Bookmark</label>
                            <select wire:model="idBookmarkTopic" class="form-control">
                                <option value="null" selected disabled hidden>Chọn chủ đề</option>
                                @foreach ($bookmarkTopics as $bookmarkTopic)
                                <option value="{{$bookmarkTopic->id}}">{{$bookmarkTopic->bookmark_topic}}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="modal-footer">
                        <button wire:click.prevent="resetInput()" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Trở lại</button>
                        <button wire:click.prevent="addBookmark()" type="button" class="btn btn-primary ">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Thêm nguồn -->
</div>
