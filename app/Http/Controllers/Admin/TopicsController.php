<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Session;


class TopicsController extends Controller
{
    private $html_Select;

    public function __construct()
    {
        $this->html_Select = '';
    }

    public function show_topics()
    {
        $data_blade = array(
            'route_active'  => 'admin.analysis',
            'item_active'   => 'admin.topics',
        );
        $topics = DB::table('topics')->orderBy('id_parent', 'desc')->get();
        $html_Select = $this->TopicsRecusive($topics);
        return view('admin.analysis.topics')->with('data_blade',$data_blade)->with('html_Select',$html_Select);
    }

    public function ajax_show_topics()
    {
        $topics = DB::table('topics')->orderBy('id_parent', 'asc')->get();
        return json_encode($topics);
    }

    public function add_source()
    {
        $data_blade = array(
            'route_active'  => 'admin.analysis',
            'item_active'   => 'admin.add-source',
            'message'       => '',
            'class_message' => '',
        );
        return view('admin.analysis.add-source')->with('data_blade',$data_blade);
    }

    public function save_source(Request $request)
    {
        $data_blade = array(
            'route_active'  => 'admin.analysis',
            'item_active'   => 'admin.add-source',
        );
        if ($request->source) {
            $data = array(
                'topic'     => $request->source,
                'topic_v'   => '',
                'id_parent' => 0,
            );
            $source = DB::table('topics')->where('topic',$request->topic)->first();
            if ($source) {
                $data_blade['message'] = 'Nguồn tại liệu '.$request->source.' đã có, hãy nhập nguồn tài liệu khác';
                $data_blade['class_message'] = 'alert-warning';
            }else{
                $result = DB::table('topics')->insert($data);
                if ($result) {
                    $data_blade['message'] = 'Đã thêm nguồn '.$request->source.' thành công';
                    $data_blade['class_message'] = 'alert-success';
                }else{
                    $data_blade['message'] = 'Đã thêm nguồn '.$request->source.' thất bại';
                    $data_blade['class_message'] = 'alert-danger';
                }
            }
        } else {
            $data_blade['message'] = 'Vui lòng nhập nguồn tài liệu';
            $data_blade['class_message'] = 'alert-warning';

        }
        return view('admin.analysis.add-source')->with('data_blade',$data_blade);
    }


    public function add_topic()
    {
        $data_blade = array(
            'route_active'  => 'admin.analysis',
            'item_active'   => 'admin.add-topic',
            'message'       => '',
            'class_message' => '',
        );
        $topics = DB::table('topics')->orderBy('id_parent', 'desc')->get();
        $html_Select = $this->TopicsRecusive($topics);
        if ($topics->count()) {
            return view('admin.analysis.add-topic')->with('data_blade',$data_blade)->with('html_Select',$html_Select);
        }else{
            $data_blade['message'] = 'Chưa có nguồn tài liệu, hãy thêm nguồn trước khi thêm chủ đề';
            $data_blade['class_message'] = 'alert-danger';
            return view('admin.analysis.add-source')->with('data_blade',$data_blade)->with('html_Select',$html_Select);
        }
    }

    public function save_topic(Request $request)
    {
        $data_blade = array(
            'route_active'  => 'admin.analysis',
            'item_active'   => 'admin.add-topic',
        );

        if ($request->topic) {
            $data = array(
                'topic'     => $request->topic,
                'topic_v'   => $request->topic_v,
                'id_parent' => $request->id_parent,
            );
            $topic = DB::table('topics')->where('topic',$request->topic)->where('id_parent',$request->id_parent)->first();
            if ($topic) {
                $data_blade['message'] = 'Chủ đề '.$request->topic.' đã có, hãy nhập chủ đề khác';
                $data_blade['class_message'] = 'alert-warning';
            }else{
                $result = DB::table('topics')->insert($data);
                if ($result) {
                    $data_blade['message'] = 'Đã thêm chủ đề '.$request->topic.' thành công';
                    $data_blade['class_message'] = 'alert-success';
                }else{
                    $data_blade['message'] = 'Đã thêm chủ đề '.$request->topic.' thất bại';
                    $data_blade['class_message'] = 'alert-danger';
                }
            }
        } else {
            $data_blade['message'] = 'Vui lòng nhập chủ đề (topic)';
            $data_blade['class_message'] = 'alert-warning';

        }
        $topics = DB::table('topics')->orderBy('id_parent', 'desc')->get();
        $html_Select = $this->TopicsRecusive($topics);
        return view('admin.analysis.add-topic')->with('data_blade',$data_blade)->with('html_Select',$html_Select);
    }


    public function TopicsRecusive($topics,$id_topic = '0',$text = '')
    {   
        foreach ($topics as $topic) {
            if ($topic->id_parent == $id_topic) {
                $this->html_Select .= '
                <option value="'.$topic->id_topic.'" > '.$text.' '.$topic->id_topic.' '.$topic->topic.' '.$topic->topic_v.'</option>';
                $this->TopicsRecusive($topics,$topic->id_topic,$text.'-');
            }

        }
        return $this->html_Select;
    }


    public function titles()
    {
        $data_blade = array(
            'route_active'  => 'admin.analysis',
            'item_active'   => 'admin.titles',
        );
        return view('admin.analysis.titles')->with('data_blade',$data_blade);
    }

    public function ajax_show_titles()
    {
        $titles = DB::table('titles')->orderBy('id_title', 'desc')->get();
        return json_encode($titles);
    }


    public function add_title()
    {
        $data_blade = array(
            'route_active'  => 'admin.analysis',
            'item_active'   => 'admin.add-title',
            'message'       => '',
            'class_message' => '',
        );
        $topics = DB::table('topics')->orderBy('id_parent', 'desc')->get();
        $html_Select = $this->TopicsRecusive($topics);
        if ($topics->count()) {
            return view('admin.analysis.add-title')->with('data_blade',$data_blade)->with('html_Select',$html_Select);
        }else{
            $data_blade['message'] = 'Chưa có chủ đề tài liệu, hãy thêm chủ đề trước khi thêm chủ đề';
            $data_blade['class_message'] = 'alert-danger';
            return redirect('/admin/add-topic');
        }
    }

    public function save_title(Request $request)
    {
        $data_blade = array(
            'route_active'  => 'admin.analysis',
            'item_active'   => 'admin.add-title',
        );
        
        if ($request->title && $request->id_topic && $request->url) {
            $data = array(
                'title'     => $request->title,
                'title_v'   => $request->title_v,
                'id_topic' => $request->id_topic,
                'url'   => $request->url,
                'content'   => $request->content,
            );
            $topic = DB::table('titles')->where('title',$request->title)->where('id_topic',$request->id_topic)->first();
            if ($topic) {
                $data_blade['message'] = 'Tiêu đề '.$request->title.' của '.$request->topic.' đã có, hãy nhập tiêu đề khác';
                $data_blade['class_message'] = 'alert-warning';
            }else{
                $result = DB::table('titles')->insert($data);
                if ($result) {
                    $data_blade['message'] = 'Đã thêm tiêu đề '.$request->title.' thành công';
                    $data_blade['class_message'] = 'alert-success';
                }else{
                    $data_blade['message'] = 'Đã thêm tiêu đề '.$request->title.' thất bại';
                    $data_blade['class_message'] = 'alert-danger';
                }
            }
        } else {
            $data_blade['message'] = 'Vui lòng nhập đầy đủ thông tin';
            $data_blade['class_message'] = 'alert-warning';

        }
        $topics = DB::table('topics')->orderBy('id_parent', 'desc')->get();
        $html_Select = $this->TopicsRecusive($topics);
        return view('admin.analysis.add-title')->with('data_blade',$data_blade)->with('html_Select',$html_Select);
    }



}

