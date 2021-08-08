<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\Admin\GetInfoWordController;
use DB;

class UpdateSentenceInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Update:SentenceInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật thông tin câu';

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
        $sentences = DB::table('sentences')->where('sentence_v','=','')->orderBy('sentence_frequency','desc')->limit(50)->get();
        if ($sentences->count() > 1) {
            foreach ($sentences as $sentence){
                $sentence_v = GetInfoWordController::GGTranslate($sentence->sentence);
                if ($sentence_v) {
                    $data = array(
                        'sentence_v' => $sentence_v,
                    );
                    DB::table('sentences')->where('id_sentence',$sentence->id_sentence)->limit(1)->update($data);
                    unset($data);
                }
            }
        }
        return 0;
    }
}
