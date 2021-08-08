<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BookmarkTopic;
use App\Models\Words;
use Illuminate\Support\Facades\Auth;

class BookmarkTopicLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $order_by    = 'id';
    public $order_desc   = true;
    public $paginate = 10;


    public $data = [];
 


    protected $rules = [
        'data.bookmark_topic' => 'required',
        'data.bookmark_topic_vn' => 'required',
        'data.id_parent' => 'required',
    ];

    protected $messages = [
        'data.bookmark_topic.required' => 'Vui lòng điền chủ đề tài liệu.',
        'data.bookmark_topic_vn.required' => 'Vui lòng điền chủ đề tiếng việt của tài liệu.',
        'data.id_parent.required' => 'Vui lòng chọn nguồn, chủ đề tài liệu.',
    ];

    public function setOrderField($field)
    {
        $this->order_by = $field;
        $this->toggleOrderDesc();
    }

    public function toggleOrderDesc()
    {
        $this->order_desc = !$this->order_desc;
    }

    public function resetInput()
    {
        session()->forget(['message', 'class_message']);
        $this->data = [];
        $this->resetValidation();
    }

    public function edit(BookmarkTopic $bookmarkTopic)
    {

        $this->data = array(
            'id'    => $bookmarkTopic->id,
            'bookmark_topic'  => "$bookmarkTopic->bookmark_topic",
            'bookmark_topic_vn'  => "$bookmarkTopic->bookmark_topic_vn",
            'id_parent'  => $bookmarkTopic->id_parent,
        );
        $this->dispatchBrowserEvent('show_form');
    }

    public function createTopic()
    {
        $this->data['id_user'] = 1;
        $this->validate($this->rules);
        if (isset($this->data['id'])) {
            bookmarkTopic::where('id',$this->data['id'])->update($this->data);
        }else{
            bookmarkTopic::insert($this->data);
        }
        $this->resetInput();
        $this->dispatchBrowserEvent('hide_form');
    }

    public function export($id_bookmark_topic)
    {
        $words = Words::join('bookmark_words', 'words.id_word','=','bookmark_words.id_word')->where('bookmark_words.id_bookmark_topic',$id_bookmark_topic)->get();
        $myfile = fopen('anki_'.$id_bookmark_topic.'.txt', "w+");
        // $text = '"word","word_vn","word_frequency"'."\n";
        // fwrite($myfile, $text);
        foreach ($words as $word) {
            // $text = '"'.$word->word.'","'.$word->word_vn.'","'.$word->word_frequency.'"'."\n";
            $text = $word->word."\t".$word->word_en."\t".$word->word_v."\t".$word->phonetics_uk."\t".$word->phonetics_us."\t".$word->audio_uk."\t".$word->audio_us."\t".$word->dictionary."\t".$word->word_frequency."\n";
            fwrite($myfile, $text);
        }
        fclose($myfile);
    }

    public function render()
    {
        $bookmarkTopics = BookmarkTopic::query()->orderBy($this->order_by, direction: $this->order_desc ? 'desc':'asc');

        if (! empty($this->filters)) {
            foreach ($this->filters as $key => $value) {
                if (empty($value)) {
                    continue;
                }
                $bookmarkTopics = $bookmarkTopics->where($key,"like","%".$value."%");
            } 
        }

        $bookmarkTopics = $bookmarkTopics->paginate(perPage: $this->paginate);
        return view('livewire.admin.bookmark-topic-livewire',[
            'bookmarkTopics' => $bookmarkTopics,
            'datta' => $this->data,
        ]);
    }
}
