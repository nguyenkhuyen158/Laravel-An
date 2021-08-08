<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use DB;

class AddWords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $array_sentence;
    public $id_title;



    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($array_sentence, $id_title)
    {
        $this->array_sentence = $array_sentence;
        $this->id_title = $id_title;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id_title = $this->id_title;
        foreach ($this->array_sentence as $sentence) {    
            $sentence = trim($sentence);
            $sql_sentence = DB::table('sentences')->where('sentence',$sentence)->first();
            if ($sql_sentence) {
                
                DB::table('sentences')->where('id_sentence',$sql_sentence->id_sentence)->increment('sentence_frequency');

                $sentence_title = DB::table('sentences_title')->where('id_sentence',$sql_sentence->id_sentence)->where('id_title',$id_title)->increment('sentence_title_frequency');

                if (!$sentence_title) {
                    // dd('he');
                    $sentence_title = DB::table('sentences_title')->insert(
                        array(
                            'id_sentence'               => $sql_sentence->id_sentence,
                            'id_title'                  => $id_title,
                            'sentence_title_frequency'  => 1,
                    ));
                }
                DB::enableQueryLog();

                $words = DB::table('word_sentence')->where('id_sentence', $sql_sentence->id_sentence)->get();
                // print_r($words);
                // dd(DB::getQueryLog());
                // echo $sql_sentence->id_sentence;
                // dd($words);
                if ($words->isEmpty()) {
                    // dd($words);
                    foreach ($words as $word) {

                        DB::table('words')->where('id_word',$word->id_word)->increment('word_frequency');
                        $sql_word_title = DB::table('word_title')->where('id_word',$word->id_word)->where('id_title',$id_title)->increment('word_title_frequency');
                        if (!$sql_word_title) {
                            DB::table('word_title')->insert(
                                array(
                                    'id_word'               => $word->id_word,
                                    'id_title'              => $id_title,
                                    'word_title_frequency'  => 1
                                ));
                        }
                    }
                }else{
                    // echo $sql_sentence->id_sentence;
                    // print_r($words);
                    // dd($words);
                    DB::table('words')->where('id_word',$words[0]->id_word)->increment('word_frequency');
                    $sql_word_title = DB::table('word_title')->where('id_word',$words[0]->id_word)->where('id_title',$id_title)->increment('word_title_frequency');
                    // dd($sql_word_title);
                    if (!$sql_word_title) {
                        DB::table('word_title')->insert(
                            array(
                                'id_word'               => $words[0]->id_word,
                                'id_title'              => $id_title,
                                'word_title_frequency'  => 1
                            ));
                    }

                }
                
            }else{
                $sentence = trim($sentence);
                $id_sentence = DB::table('sentences')->insertGetId(
                        array(
                            'sentence'              => $sentence,
                            'sentence_v'            => '',
                            'sentence_frequency'    => 1,
                    ));
                if ($id_sentence) {
                    $id_sentence_title = DB::table('sentences_title')->insertGetId(
                        array(
                            'id_sentence'               => $id_sentence,
                            'id_title'                  => $id_title,
                            'sentence_title_frequency'  => 1,
                        ));
                    if ($id_sentence_title) {
                        $sentence = preg_replace('/[^A-Za-z\-]/', ' ', $sentence);
                        $words = explode(" ", $sentence);
                        $words = array_filter( $words,'trim');
                        foreach ($words as $word){
                            $sql_word =DB::table('words')->where('word',$word)->first();
                            if ($sql_word) {
                                DB::table('words')->where('id_word',$sql_word->id_word)->increment('word_frequency');
                                $sql_word_title = DB::table('word_title')->where('id_word',$sql_word->id_word)->where('id_title',$id_title)->increment('word_title_frequency');
                                if (!$sql_word_title) {
                                    DB::table('word_title')->insert(
                                    array(
                                        'id_word'               => $sql_word->id_word,
                                        'id_title'              => $id_title,
                                        'word_title_frequency'  => 1
                                    ));
                                }
                            }else{
                                $id_word = DB::table('words')->insertGetId(
                                array(
                                    'word'              => trim($word),
                                    'word_v'            => '',
                                    'word_frequency'    => 1,
                                    'phonetics_uk'         => '',
                                    'phonetics_us'         => '',
                                    'audio_uk'          => '',
                                    'audio_us'          => '',
                                    'dictionary'        => '',
                                ));
                                if ($id_word) {
                                    DB::table('word_sentence')->insert(
                                        array(
                                            'id_sentence'   => $id_sentence,
                                            'id_word'       => $id_word,
                                        ));
                                    DB::table('word_title')->insert(
                                    array(
                                        'id_word'               => $id_word,
                                        'id_title'              => $id_title,
                                        'word_title_frequency'  => 1
                                    ));
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
