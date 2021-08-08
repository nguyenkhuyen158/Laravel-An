<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use DB;
use Session;
use App\Http\Controllers\Admin\TopicsController;


class AddSource extends Component
{
    public $source, $topic, $topicv, $id_parent, $html_Select;

    public function resetInput()
    {
        $this->source = '';
        $this->topic = '';
        $this->topicv = '';
        session()->forget(['message', 'class_message']);
        $this->resetValidation();
    }

    protected $rules = [
        'source' => 'required',
        'topic' => 'required',
        'topicv' => 'required',
    ];

    protected $messages = [
        'source.required' => 'Vui lòng điền nguồn tài liệu.',
        'topic.required' => 'Vui lòng điền chủ đề tài liệu.',
        'topicv.required' => 'Vui lòng điền chủ đề tiếng việt của tài liệu.',
        'id_parent.required' => 'Vui lòng chọn nguồn, chủ đề tài liệu.',
    ];

    public function mount()
    {
        $this->html_Select = "";
        $topics = DB::table('topics')->orderBy('id_parent', 'desc')->get();
        $this->html_Select = $this->TopicsRecusive($topics);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function show_model()
    {
        $this->dispatchBrowserEvent('show_form');
    }

    public function create_source()
    {
        $success = $this->validate(['source' => 'required']);
        if ($success) {
            $data = array(
                'topic'     => trim($this->source),
                'topic_v'   => '',
                'id_parent' => 0,
            );
            $source = DB::table('topics')->where('topic',$data['topic'])->first();
            if ($source) {
                session()->flash('message','Nguồn tại liệu '.$data['topic'].' đã có, hãy nhập nguồn tài liệu khác');
                session()->flash('class_message','alert-warning');
            }else{
                $result = DB::table('topics')->insert($data);
                if ($result) {
                    $this->dispatchBrowserEvent('table_refresh');
                    $this->dispatchBrowserEvent('hide_form');
                    $this->resetInput();
                }else{
                    session()->flash([
                        'message'       => 'Đã thêm nguồn '.$data['topic'].' thất bại',
                        'class_message' => 'alert-warning',
                    ]);
                }
            }
        }
    }

    public function create_topic()
    {
        $success = $this->validate([
            'topic' => 'required',
            'topicv' => 'required',
            'id_parent' => 'required',
        ]);
        $data = array(
            'topic'     => trim($this->topic),
            'topic_v'   => $this->topicv,
            'id_parent' => $this->id_parent,
        );
        if ($success) {
            $topic = DB::table('topics')->where('topic',$data['topic'])->where('id_parent',$data['id_parent'])->first();
            if ($topic) {
                session()->flash('message','Chủ đề '.$data['topic'].' đã có, hãy nhập nguồn tài liệu khác');
                session()->flash('class_message','alert-warning');
            }else{
                $result = DB::table('topics')->insert($data);
                if ($result) {
                    $this->dispatchBrowserEvent('table_refresh');
                    $this->dispatchBrowserEvent('hide_form');
                    $this->resetInput();
                }else{
                    session()->flash('message','Đã thêm chủ đề '.$data['topic'].' thất bại');
                    session()->flash('class_message','alert-danger');
                }
            }
        }
    }

    public function TopicsRecusive($topics,$id_topic = '0',$text = '')
    {   
        foreach ($topics as $topic) {
            if ($topic->id_parent == $id_topic) {
                $this->html_Select .= '
                <option value="'.$topic->id_topic.'" > '.$text.' '.$topic->id_topic.' - '.$topic->topic.' '.$topic->topic_v.'</option>';
                $this->TopicsRecusive($topics,$topic->id_topic,$text.'-');
            }

        }
        return $this->html_Select;
    }

    public function render()
    {
        $this->html_Select = "";
        $topics = DB::table('topics')->orderBy('id_parent', 'desc')->get();
        $this->html_Select = $this->TopicsRecusive($topics);
        return view(
            'livewire.admin.add-source',
            [
                'html_Select' => $this->html_Select,
            ]
        );
    }
}
