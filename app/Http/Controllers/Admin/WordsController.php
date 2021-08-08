<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use KubAT\PhpSimple\HtmlDomParser;
use Illuminate\Support\Str;

use App\Jobs\AddWords;

use DB;
use Session;

class WordsController extends Controller
{
    private $html_Select;

    public function __construct()
    {
        $this->html_Select = '';
    }

    public function words()
    {
        $data_blade = array(
            'route_active'  => 'admin.words',
            'item_active'   => 'admin.sentes',
        );
        // $words = DB::table('words')->where('word_v','=','')->orderBy('word_frequency','desc')->limit(2)->get();
        // if ($words->count() > 1) {
        //     foreach ($words as $word){
        //         $word_v = GetInfoWordController::GGTranslate($word->word);
        //         $data_word = GetInfoWordController::DicOxford($word->word);
        //         if (is_null($data_word)) {
        //             $data_word = GetInfoWordController::DicCambridge($word->word);
        //             if (is_null($data_word)) {
        //                 $data_word = GetInfoWordController::DicLexico($word->word);
        //                 if (is_null($data_word)) {
        //                     $data_word = array(
        //                         'phon_uk' => 'null',
        //                         'phon_us' => 'null',
        //                         'audio_uk' => 'null',
        //                         'audio_us' => 'null',
        //                         'dic' => 'null',
        //                     );
        //                     // dd(hi);
        //                 }
        //             }else{
        //                 $dic = GetInfoWordController::DicLexico($word->word,'dic');
        //                 if (!is_null($dic)) {
        //                     $data_word['dic'] = $dic;
        //                 }else{
        //                     $data_word = array(
        //                         'phon_uk' => 'null',
        //                         'phon_us' => 'null',
        //                         'audio_uk' => 'null',
        //                         'audio_us' => 'null',
        //                         'dic' => 'null',
        //                     );
        //                 }
        //             }
        //         }else{
        //             $dic = GetInfoWordController::DicLexico($word->word,'dic');
        //             if (!is_null($dic)) {
        //                 $data_word['dic'] = $dic;
        //             }
        //         }
        //         $data = array(
        //             'word_v'        => $word_v,
        //             'phonetics_us'  => $data_word['phon_us'],
        //             'phonetics_uk'  => $data_word['phon_uk'],
        //             'audio_us'      => $data_word['audio_us'],
        //             'audio_uk'      => $data_word['audio_uk'],
        //             'dictionary'    => $data_word['dic'],
        //         );
        //         $data['word_en'] = GetInfoWordController::GetWordEn($word->word);
        //         DB::table('words')->where('id_word',$word->id_word)->limit(1)->update($data);
        //         unset($data);
        //         unset($data_word);
        //     }
        // }
        return view('admin.words.words')->with('data_blade',$data_blade);
    }

    public function ajax_show_words()
    {
        $words = DB::table('words')->orderBy('word_frequency', 'desc')->get();
        return json_encode($words);
    }

    public function sentences()
    {
        $data_blade = array(
            'route_active'  => 'admin.words',
            'item_active'   => 'admin.sentences',
        );
        return view('admin.words.sentences')->with('data_blade',$data_blade);
    }

    public function ajax_show_sentences()
    {
        $sentences = DB::table('sentences')->orderBy('sentence_frequency', 'desc')->get();
        return json_encode($sentences);
    }    

    public function add_words()
    {
        $data_blade = array(
            'route_active'  => 'admin.words',
            'item_active'   => 'admin.add-words',
        );
        $titles = DB::table('titles')->orderBy('id_topic', 'desc')->get();
        $html_Select = $this->TitlesRecusive($titles);
        return view('admin.words.add-words')->with('data_blade',$data_blade)->with('html_Select',$html_Select);
    }


    public function save_words(Request $request)
    {
        $data_blade = array(
            'route_active'  => 'admin.words',
            'item_active'   => 'admin.add-words',
        );

        $id_title   = $request->id_title;
        $text       = $request->text;
        $text = str_replace('.)','.',$text);
        $text = str_replace('. (','. ',$text);
        $text = str_replace(':\n','. ',$text);
        $text = str_replace('●','',$text);
        $text = str_replace('•','',$text);
        $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
        $text = preg_replace($regex, ')', $text);
        $text = str_replace(' — ','.',$text);
        $text = preg_replace('/([0-9])(\.)([0-9])/','\1,\3',$text);
        $text = preg_replace('/\[[0-9,-]+\]/','',$text);
        $text = preg_replace('/\n/','.', $text);
        $sentences = explode(".", $text);
        $sentences = array_filter( $sentences,'trim');
        // dd($sentences);
        $array_sentences =  array_chunk($sentences, 100);
        foreach ($array_sentences as $array_sentence) {
            AddWords::dispatch($array_sentence, $id_title);
        }
        // foreach ($sentences as $sentence) {
        //     $sentence = trim($sentence);
        //     $sql_sentence = DB::table('sentences')->where('sentence',$sentence)->first();
        //     if ($sql_sentence) {
                
        //         DB::table('sentences')->where('id_sentence',$sql_sentence->id_sentence)->increment('sentence_frequency');

        //         $sentence_title = DB::table('sentences_title')->where('id_sentence',$sql_sentence->id_sentence)->where('id_title',$id_title)->increment('sentence_title_frequency');

        //         if (!$sentence_title) {
        //             // dd('he');
        //             $sentence_title = DB::table('sentences_title')->insert(
        //                 array(
        //                     'id_sentence'               => $sql_sentence->id_sentence,
        //                     'id_title'                  => $id_title,
        //                     'sentence_title_frequency'  => 1,
        //             ));
        //         }
        //         DB::enableQueryLog();

        //         $words = DB::table('word_sentence')->where('id_sentence', $sql_sentence->id_sentence)->get();
        //         // print_r($words);
        //         // dd(DB::getQueryLog());
        //         // echo $sql_sentence->id_sentence;
        //         // dd($words);
        //         if ($words->isEmpty()) {
        //             // dd($words);
        //             foreach ($words as $word) {

        //                 DB::table('words')->where('id_word',$word->id_word)->increment('word_frequency');
        //                 $sql_word_title = DB::table('word_title')->where('id_word',$word->id_word)->where('id_title',$id_title)->increment('word_title_frequency');
        //                 if (!$sql_word_title) {
        //                     DB::table('word_title')->insert(
        //                         array(
        //                             'id_word'               => $word->id_word,
        //                             'id_title'              => $id_title,
        //                             'word_title_frequency'  => 1
        //                         ));
        //                 }
        //             }
        //         }else{
        //             // echo $sql_sentence->id_sentence;
        //             // print_r($words);
        //             // dd($words);
        //             DB::table('words')->where('id_word',$words[0]->id_word)->increment('word_frequency');
        //             $sql_word_title = DB::table('word_title')->where('id_word',$words[0]->id_word)->where('id_title',$id_title)->increment('word_title_frequency');
        //             // dd($sql_word_title);
        //             if (!$sql_word_title) {
        //                 DB::table('word_title')->insert(
        //                     array(
        //                         'id_word'               => $words[0]->id_word,
        //                         'id_title'              => $id_title,
        //                         'word_title_frequency'  => 1
        //                     ));
        //             }

        //         }
                
        //     }else{
        //         $sentence = trim($sentence);
        //         $id_sentence = DB::table('sentences')->insertGetId(
        //                 array(
        //                     'sentence'              => $sentence,
        //                     'sentence_v'            => '',
        //                     'sentence_frequency'    => 1,
        //             ));
        //         if ($id_sentence) {
        //             $id_sentence_title = DB::table('sentences_title')->insertGetId(
        //                 array(
        //                     'id_sentence'               => $id_sentence,
        //                     'id_title'                  => $id_title,
        //                     'sentence_title_frequency'  => 1,
        //                 ));
        //             if ($id_sentence_title) {
        //                 $sentence = preg_replace('/[^A-Za-z\-]/', ' ', $sentence);
        //                 $words = explode(" ", $sentence);
        //                 $words = array_filter( $words,'trim');
        //                 foreach ($words as $word){
        //                     $sql_word =DB::table('words')->where('word',$word)->first();
        //                     if ($sql_word) {
        //                         DB::table('words')->where('id_word',$sql_word->id_word)->increment('word_frequency');
        //                         $sql_word_title = DB::table('word_title')->where('id_word',$sql_word->id_word)->where('id_title',$id_title)->increment('word_title_frequency');
        //                         if (!$sql_word_title) {
        //                             DB::table('word_title')->insert(
        //                             array(
        //                                 'id_word'               => $sql_word->id_word,
        //                                 'id_title'              => $id_title,
        //                                 'word_title_frequency'  => 1
        //                             ));
        //                         }
        //                     }else{
        //                         $id_word = DB::table('words')->insertGetId(
        //                         array(
        //                             'word'              => trim($word),
        //                             'word_v'            => '',
        //                             'word_frequency'    => 1,
        //                             'phonetics_uk'         => '',
        //                             'phonetics_us'         => '',
        //                             'audio_uk'          => '',
        //                             'audio_us'          => '',
        //                             'dictionary'        => '',
        //                         ));
        //                         if ($id_word) {
        //                             DB::table('word_sentence')->insert(
        //                                 array(
        //                                     'id_sentence'   => $id_sentence,
        //                                     'id_word'       => $id_word,
        //                                 ));
        //                             DB::table('word_title')->insert(
        //                             array(
        //                                 'id_word'               => $id_word,
        //                                 'id_title'              => $id_title,
        //                                 'word_title_frequency'  => 1
        //                             ));
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        $data_blade['message'] = 'Đã thêm từ thành công';
        $data_blade['class_message'] = 'alert-success';
        $titles = DB::table('titles')->orderBy('id_topic', 'desc')->get();
        $html_Select = $this->TitlesRecusive($titles,$id_title);
        return view('admin.words.add-words')->with('data_blade',$data_blade)->with('html_Select',$html_Select);
        
    }

    public function TitlesRecusive($titles,$id_title_select = '0')
    {   
        $html_Select = '';
        foreach ($titles as $title) {
            $selected = 'selected';
            if ($title->id_title == $id_title_select) {
                $select = 'select';
            }
            $html_Select .= '<option '.$selected.' value="'.$title->id_title.'" >CĐ '.$title->id_topic.' > TĐ '.$title->id_title.' '.$title->title.' - '.$title->title_v.'</option>';
        }
        return $html_Select;
    }
}
