<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\Admin\GetInfoWordController;
use DB;

class UpdateWordInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Update:WordInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật thông tin từ vựng';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $words = DB::table('words')->where('word_v','=','')->orderBy('word_frequency','desc')->limit(8)->get();
        if ($words->count() > 1) {
            foreach ($words as $word){
                $word_v = GetInfoWordController::GGTranslate($word->word);
                $data_word = GetInfoWordController::DicOxford($word->word);
                if (is_null($data_word)) {
                    $data_word = GetInfoWordController::DicCambridge($word->word);
                    if (is_null($data_word)) {
                        $data_word = GetInfoWordController::DicLexico($word->word);
                        if (is_null($data_word)) {
                            $data_word = array(
                                'phon_uk' => 'null',
                                'phon_us' => 'null',
                                'audio_uk' => 'null',
                                'audio_us' => 'null',
                                'dic' => 'null',
                            );
                            // dd(hi);
                        }
                    }else{
                        $dic = GetInfoWordController::DicLexico($word->word,'dic');
                        if (!is_null($dic)) {
                            $data_word['dic'] = $dic;
                        }else{
                            $data_word = array(
                                'phon_uk' => 'null',
                                'phon_us' => 'null',
                                'audio_uk' => 'null',
                                'audio_us' => 'null',
                                'dic' => 'null',
                            );
                        }
                    }
                }else{
                    $dic = GetInfoWordController::DicLexico($word->word,'dic');
                    if (!is_null($dic)) {
                        $data_word['dic'] = $dic;
                    }
                }
                $data = array(
                    'word_v'        => strtolower($word_v),
                    'phonetics_us'  => $data_word['phon_us'],
                    'phonetics_uk'  => $data_word['phon_uk'],
                    'audio_us'      => $data_word['audio_us'],
                    'audio_uk'      => $data_word['audio_uk'],
                    'dictionary'    => $data_word['dic'],
                );
                $data['word_en'] = GetInfoWordController::GetWordEn($word->word);
                DB::table('words')->where('id_word',$word->id_word)->limit(1)->update($data);
                unset($data);
                unset($data_word);
            }
        }
        $this->info('success');
        return 0;
    }
}
