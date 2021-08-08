<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Words;

use App\Http\Controllers\Admin\GetInfoWordController;

use DB;


class Listword extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $orderBy    = 'word_frequency';
    public $orderDesc   = true;
    public $paginate = 10;

    public $filters = [];
    public $checked = [];
    public $state = [];
    public $audiofromDic;
    public $bookmarkTopics;
    public $idBookmarkTopic;
    public $id_w;



    // public function mount($state)
    // {
    //     $this->state = $state;
    // }

    public function setOrderField($field)
    {
        $this->orderBy = $field;
        $this->toggleOrderDesc();
    }

    public function toggleOrderDesc()
    {
        $this->orderDesc = !$this->orderDesc;
    }

    public function changeDic()
    {
        switch ($this->audiofromDic) {
            case 'Oxford':
                $data = GetInfoWordController::DicOxford($this->state['word'],'auio-phon');
                break;
            case 'Cambridge':
                $data = GetInfoWordController::DicCambridge($this->state['word'],'auio-phon');
                break;
            case 'Lexico':
                $data = GetInfoWordController::DicLexico($this->state['word'],'auio-phon');
                break;
            default:
                
            break;
        }
        
        if (is_array($data)) {
            $this->state['audio_us'] = $data['audio_us'];
            $this->state['audio_uk'] = $data['audio_uk'];
            $this->state['phonetics_us'] = $data['phon_us'];
            $this->state['phonetics_uk'] = $data['phon_us'];
        }
        $this->dispatchBrowserEvent('hide_form');
        $this->dispatchBrowserEvent('show_form');
        // dd($this->state);
        // $this->dispatchBrowserEvent('hide_form');

        // $this->dispatchBrowserEvent('show_form');
        // $this->emit('render');
    }


    public function edit($id_word)
    {
        $this->state = Words::where('id_word', $id_word)->first()->toArray();
        $this->dispatchBrowserEvent('show_form');
    }

    public function luuWord()
    {
        // dd($this->state);
        Words::where('id_word',$this->state['id_word'])->update($this->state);
        $this->resetInput();
        $this->dispatchBrowserEvent('hide_form');
    }

    public function resetInput()
    {
        $this->state = [];
        $this->id_w = '';
        $this->audiofromDic = '';
        $this->dispatchBrowserEvent('hide_form');
    }

    public function reload()
    {
        $this->emit('render');
    }

    public function updatedfilters()
    {
        $this->resetPage();
    }

    public function updatedaudiofromDic()
    {
        switch ($this->audiofromDic) {
            case 'Oxford':
                $data = GetInfoWordController::DicOxford($this->state['word'],'auio-phon');
                break;
            case 'Cambridge':
                $data = GetInfoWordController::DicCambridge($this->state['word'],'auio-phon');
                break;
            case 'Lexico':
                $data = GetInfoWordController::DicLexico($this->state['word'],'auio-phon');
                break;
            default:
                
            break;
        }
        
        if (is_array($data)) {
            $this->state['audio_us'] = $data['audio_us'];
            $this->state['audio_uk'] = $data['audio_uk'];
            $this->state['phonetics_us'] = $data['phon_us'];
            $this->state['phonetics_uk'] = $data['phon_us'];
        }
        $this->dispatchBrowserEvent('hide_form');
        $this->dispatchBrowserEvent('show_form');
        // dd($this->state);
    }

    public function bookmarkModel($id_w)
    {
        $this->id_w = $id_w;
        $this->dispatchBrowserEvent('show_form_bookmark');
    }

    public function addBookmark()
    {
        $word = Words::where('id_word', $this->id_w)->first();
        $data = array(
            'id_user'   => 1,
            'id_word'   => $this->id_w,
            'word_en'   => $word->word_en,
            'note'      => null,
            'true'      => null,
            'false'     => null,
            'remember'  => null,
            'id_bookmark_topic' => $this->idBookmarkTopic,   
        );
        $select = DB::table('bookmark_words')->where('id_user',1)->where('id_word',$this->id_w)->where('id_bookmark_topic',$this->idBookmarkTopic);
        // dd($select);
        if ($select->count() < 1) {
            DB::table('bookmark_words')->upsert($data,['id_user','id_word','id_bookmark_topic']);
        }
        $this->dispatchBrowserEvent('hide_form');
    }

    public function render()
    {
        // echo $this->audiofromDic;
        $words = Words::query()->orderBy($this->orderBy, direction: $this->orderDesc ? 'desc':'asc');

        if (! empty($this->filters)) {
            
            foreach ($this->filters as $key => $value) {
                if (empty($value)) {
                    continue;
                }
                $words = $words->where($key,"like","%".$value."%");
            } 
        }

        $words = $words->paginate(perPage: $this->paginate);
        // dd($words);
        $this->bookmarkTopics = DB::table('bookmark_topics')->orderBy('id_parent', 'desc')->get();

        return view('livewire.admin.listword',[
            'words' => $words,
            // 'state' => $this->state,
            // 'bookmarkTopics' => $this->bookmarkTopics,
        ]);
    }
}
