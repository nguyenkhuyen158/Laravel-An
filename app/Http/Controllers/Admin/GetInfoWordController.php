<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use KubAT\PhpSimple\HtmlDomParser;



class GetInfoWordController extends Controller
{
    public static function getDic($word, $from = 'lexico')
    {
        // include(app_path().'\Functions\simple_html_dom.php');
        switch ($from){
            case 'lexico':
            $url = "https://www.lexico.com/en/definition/".$word."?s=t";
            $html = HtmlDomParser::file_get_html($url);
            if ($html) {
                $dic = '';
                $dics = $html->find('section.gramb');
                foreach ($dics as $key => $value) {
                    $dic = $dic.$value;
                    $dic = $dic.$value->next_sibling();
                };
            }
            break;

            case 'google':
            $word = str_replace(' ', '+', $word);
            $url = "https://translate.google.com.vn/m?sl=en&tl=vi&hl=vi&q=".$word; 
            $html = HtmlDomParser::file_get_html($url);
            if ($html) {
                $dic = $html->find('div.result-container',0)->plaintext;
            }
            break;

            case 'oxford':
            $url = "https://www.oxfordlearnersdictionaries.com/definition/english/".$word;
            $html = HtmlDomParser::file_get_html($url);
            if ($html) {
                $dic = $html->find('ol', 0);
            }
            break;

            case 'cam':
            $url = "https://dictionary.cambridge.org/vi/dictionary/english/".$word;
            $html = HtmlDomParser::file_get_html($url);
            if ($html) {
                $dics = $html->find('div.sense-body');
                $dic = '';
                foreach ($dics as $key => $value) {
                    $dic = $dic.$value;
                };
                $dic = str_replace(['src="/','amp-img'], ['src="https://dictionary.cambridge.org/','img'], $dic);
            }
            break;
            default:
            $error = "error";
        }

        if(isset($dic)){
            return $dic;
        }else{
            return "null";
        }
    }


    public static function getAudio($word, $from = 'oxford', $accent = 'us')
    {
        switch ($from){
            case 'oxford':
            $url = "https://www.oxfordlearnersdictionaries.com/definition/english/".$word;
            $html = HtmlDomParser::file_get_html($url);
            $title = 'data-src-mp3';
            if ($html) {
                $mp3 = $html->find('div.pron-'.$accent, 0)->$title;
            }
            break;

            case 'dic':
            $url = "https://www.dictionary.com/browse/".$word."?s=t";
            $html = HtmlDomParser::file_get_html($url);
            if ($html) {
                $src = 'src';
                $mp3 = $html->find('source', 1)->$src;
            }
            break;

            case 'cam':
            $url = "https://dictionary.cambridge.org/vi/dictionary/english/".$word;
            $html = HtmlDomParser::file_get_html($url);
            if ($html) {
                if ($accent == 'uk') {
                    $accent = 0;
                } else {
                    $accent = 2;
                }
                $src = 'src';
                $mp3 = "https://dictionary.cambridge.org/".$html->find('source', $accent)->$src;
            }
            break;

            case 'lexico':
            $url = "https://www.lexico.com/en/definition/".$word."?s=t";
            $html = HtmlDomParser::file_get_html($url);
            if ($html) {
                $src = 'src';
                $mp3 = $html->find('audio', 0)->$src;
            }
            break;

            default:
            $error = "error";
        }

        if(isset($mp3)){
            return $mp3;
        }else{
            return "null";
        }

    }


    public static function getPhon($word, $from = 'oxford', $accent = 'us')
    {
        switch ($from){
            case 'oxford':
            $url = "https://www.oxfordlearnersdictionaries.com/definition/english/".$word;
            $html = HtmlDomParser::file_get_html($url);
            if ($html) {
                if ($accent == 'uk') {
                    $accent = 0;
                } else {
                    $accent = 1;
                }
                $phon = $html->find('span.phon', $accent)->plaintext;
            }
            break;

            case 'dic':
            $url = "https://www.dictionary.com/browse/".$word."?s=t";
            $html = HtmlDomParser::file_get_html($url);
            if ($html) {
                $phon = $html->find('span.pron-ipa-content', 0)->plaintext;
            }
            break;

            case 'cam':
            $url = "https://dictionary.cambridge.org/vi/dictionary/english/".$word;
            $html = HtmlDomParser::file_get_html($url);
            if ($html) {
                if ($accent == 'uk') {
                    $accent = 0;
                } else {
                    $accent = 1;
                }
                $phon = $html->find('span.pron', $accent)->plaintext;
            }
            break;

            case 'lexico':
            if ($accent == 'uk') {
                    $accent = "";
                } else {
                    $accent = "en/";
                }
            $url = "https://www.lexico.com/".$accent."definition/".$word."?s=t";
            $html = HtmlDomParser::file_get_html($url);
            if ($html) {
                $phon = $html->find('span.phoneticspelling', 0)->plaintext;
            }
            break;

            default:
            $error = "error";
        }

        if(isset($phon)){
            return $phon;
        }else{
            return "null";
        }
    }


    public static function DicOxford($word,$info = 'all')
    {
        $response = Http::get('https://www.oxfordlearnersdictionaries.com/search/english/',['q' => $word])->body();
        $html = HtmlDomParser::str_get_html($response);
        if ($html) {
            if ($html->find('div.didyoumean',0)) {
                return null;
            }
            switch ($info){
                case 'all':
                $dic = '';
                $data['dic'] = $html->find('ol', 0);
                if ($html->find('span.phon', 0)) {
                    $data['phon_uk'] = $html->find('span.phon', 0)->plaintext;
                    $data['phon_us'] = $html->find('span.phon', 1)->plaintext;
                    $title = 'data-src-mp3';
                    $data['audio_uk'] = $html->find('div.pron-uk', 0)->$title;
                    $data['audio_us'] = $html->find('div.pron-us', 0)->$title;
                }else{
                    return null;
                }
                break;
                case 'dic':
                $data = $html->find('ol', 0);
                $hide = $html->find('#ring-links-box',0);
                $data = str_replace($hide,'',$data);
                break;
                case 'auio-phon':
                if ($html->find('span.phon', 0)) {
                    $data['phon_uk'] = $html->find('span.phon', 0)->plaintext;
                    $data['phon_us'] = $html->find('span.phon', 1)->plaintext;
                    $title = 'data-src-mp3';
                    $data['audio_uk'] = $html->find('div.pron-uk', 0)->$title;
                    $data['audio_us'] = $html->find('div.pron-us', 0)->$title;
                }else{
                    return null;
                }
                break;
                default:
                $error = "error";
            }
            if (isset($data)) {
                return $data;
            }
        }else{
            return null;
        }
    }


    public static function DicCambridge($word,$info = 'all')
    {
        $response = Http::get('https://dictionary.cambridge.org/vi/search/direct/',[
            'q' => $word,
            'datasetsearch' => 'english',
        ])->body();
        $html = HtmlDomParser::str_get_html($response);
        if ($html->find('h1.lpb-10.lbb')) {
            return null;
        }
        if ($html) {
            switch ($info){
                case 'all':
                $dics = $html->find('div.sense-body');
                $dic = '';
                foreach ($dics as $key => $value) {
                    $dic = $dic.$value;
                };
                $data['dic'] = str_replace(['src="/','amp-img'], ['src="https://dictionary.cambridge.org/','img'], $dic);
                if ($html->find('span.pron', 0)) {
                    $data['phon_uk'] = $html->find('span.pron', 0)->plaintext;
                }else{
                    $data['phon_uk'] = 'null';
                }
                if ($html->find('span.pron', 1)) {
                    $data['phon_us'] = $html->find('span.pron', 1)->plaintext;
                }else{
                    $data['phon_us'] = 'null';
                }

                $src = 'src';
                if ($html->find('source', 0)) {
                    $data['audio_uk'] = "https://dictionary.cambridge.org".$html->find('source', 0)->$src;
                }else{
                    $data['audio_uk'] = 'null';
                }
                if ($html->find('source', 2)) {
                    $data['audio_us'] = "https://dictionary.cambridge.org".$html->find('source', 2)->$src;
                }else{
                    $data['audio_us'] = 'null';
                }
                break;
                case 'dic':
                $dics = $html->find('div.sense-body');
                $dic = '';
                foreach ($dics as $key => $value) {
                    $dic = $dic.$value;
                };
                $data = str_replace(['src="/','amp-img'], ['src="https://dictionary.cambridge.org/','img'], $dic);
                break;
                case 'auio-phon':
                if ($html->find('span.pron', 0)) {
                    $data['phon_uk'] = $html->find('span.pron', 0)->plaintext;
                    $data['phon_us'] = $html->find('span.pron', 1)->plaintext;

                    $src = 'src';
                    $data['audio_uk'] = "https://dictionary.cambridge.org/".$html->find('source', 0)->$src;
                    $data['audio_us'] = "https://dictionary.cambridge.org/".$html->find('source', 2)->$src;
                }else{
                    return null;
                }
                break;
                default:
                $error = "error";
            }
            if (isset($data)) {
                return $data;
            }
        }else{
            return null;
        }
    }


    public static function DicLexico($word,$info = 'all')
    {
        $url_us = "https://www.lexico.com/en/definition/".$word."?s=t";
        $url_uk = "https://www.lexico.com/definition/".$word."?s=t";
        $html_us = HtmlDomParser::file_get_html($url_us);
        $html_uk = HtmlDomParser::file_get_html($url_uk);
        if ($html_us && $html_uk) {
            switch ($info){
                case 'all':
                $dic = '';
                $dics = $html_us->find('section.gramb');
                foreach ($dics as $key => $value) {
                    $dic = $dic.$value;
                    $dic = $dic.$value->next_sibling();
                };
                $data['dic'] = $dic;
                $src = 'src';
                if ($html_uk->find('audio', 0)) {
                    $data['audio_uk'] = $html_uk->find('audio', 0)->$src;
                }else{
                    $data['audio_uk'] = 'null';
                }
                if ($html_us->find('audio', 0)) {
                    $data['audio_us'] = $html_us->find('audio', 0)->$src;
                }else{
                    $data['audio_us'] = 'null';
                }
                
                if ($html_us->find('span.phoneticspelling', 1)) {
                    $data['phon_us'] = $html_us->find('span.phoneticspelling', 1)->plaintext;
                }elseif($html_us->find('span.phoneticspelling', 0)){
                    $data['phon_us'] = $html_us->find('span.phoneticspelling', 0)->plaintext;
                }else{
                    $data['phon_us'] = 'null';
                }
                if ($html_uk->find('span.phoneticspelling', 1)) {
                    $data['phon_uk'] = $html_uk->find('span.phoneticspelling', 1)->plaintext;
                }elseif($html_uk->find('span.phoneticspelling', 0)){
                    $data['phon_uk'] = $html_uk->find('span.phoneticspelling', 0)->plaintext;
                }else{
                    $data['phon_uk'] = 'null';
                }
                break;

                case 'dic':
                $dic = '';
                $dics = $html_us->find('section.gramb');
                foreach ($dics as $key => $value) {
                    $dic = $dic.$value;
                    $dic = $dic.$value->next_sibling();
                };
                $data = $dic;
                break;

                case 'auio-phon':
                $phon_us = $html_us->find('span.phoneticspelling', 1)->plaintext;
                if (!$phon_us) {
                    $phon_us = $html_us->find('span.phoneticspelling', 0)->plaintext;
                }
                $data['phon_uk'] = $html_uk->find('span.phoneticspelling', 0)->plaintext;
                $data['phon_us'] = $phon_us;
                $title = 'data-src-mp3';
                $src = 'src';
                $data['audio_uk'] = $html_uk->find('audio', 0)->$src;
                $data['audio_us'] = $html_us->find('audio', 0)->$src;
                break;
                default:
                $error = "error";
            }
            if (isset($data)) {
                return $data;
            }
        }else{
            return null;
        }
    }



    public static function GGTranslate($word, $from = 'en', $to = 'vi')
    {
        $response = Http::get('https://translate.google.com.vn/m',[
            'sl' => $from,
            'tl' => $to,
            'hl' => $to,
            'q' => $word
        ])->body();
        $html = HtmlDomParser::str_get_html($response);
        if ($html) {
            $dic = $html->find('div.result-container',0)->plaintext;
        }
        if(isset($dic)){
            return $dic;
        }else{
            return "null";
        }
    }


    public static function GetAudioPhonetics($word)
    {
        $response = Http::get('https://www.oxfordlearnersdictionaries.com/search/english/',['q' => $word])->body();
        $html = HtmlDomParser::str_get_html($response);
        if ($html && !$html->find('div.didyoumean',0)) {
            if ($html->find('span.phon', 0)) {
                $data['phon_uk'] = $html->find('span.phon', 0)->plaintext;
                $data['phon_us'] = $html->find('span.phon', 1)->plaintext;
                $title = 'data-src-mp3';
                $data['audio_uk'] = $html->find('div.pron-uk', 0)->$title;
                $data['audio_us'] = $html->find('div.pron-us', 0)->$title;

                return $data;
            }
        }
        
        $response = Http::get('https://dictionary.cambridge.org/vi/search/direct/',[
            'q' => $word,
            'datasetsearch' => 'english',
            ])->body();
        $html = HtmlDomParser::str_get_html($response);
        if (!$html->find('h1.lpb-10.lbb')) {
            if ($html->find('span.pron', 0)) {
                $data['phon_uk'] = $html->find('span.pron', 0)->plaintext;
                $data['phon_us'] = $html->find('span.pron', 1)->plaintext;

                $src = 'src';
                $data['audio_uk'] = "https://dictionary.cambridge.org".$html->find('source', 0)->$src;
                $data['audio_us'] = "https://dictionary.cambridge.org".$html->find('source', 2)->$src;
                
                return $data;
            }
        }
        $url_us = "https://www.lexico.com/en/definition/".$word."?s=t";
        $url_uk = "https://www.lexico.com/definition/".$word."?s=t";
        $html_us = HtmlDomParser::file_get_html($url_us);
        $html_uk = HtmlDomParser::file_get_html($url_uk);
        if ($html_us && $html_uk) {
            $phon_us = $html_us->find('span.phoneticspelling', 1)->plaintext;
            if (!$phon_us) {
                $phon_us = $html_us->find('span.phoneticspelling', 0)->plaintext;
            }
            $data['phon_uk'] = $html_uk->find('span.phoneticspelling', 0)->plaintext;
            $data['phon_us'] = $phon_us;
            $title = 'data-src-mp3';
            $src = 'src';
            $data['audio_uk'] = $html_uk->find('audio', 0)->$src;
            $data['audio_us'] = $html_us->find('audio', 0)->$src;

            if (isset($data)) {
                return $data;
            }else{
                $data = array(
                    'phon_us'   => 'null',
                    'phon_uk'   => 'null',
                    'audio_us'   => 'null',
                    'audio_uk'   => 'null',
                );
                return $data;
            }
        }
        $data = array(
            'phon_us'   => 'null',
            'phon_uk'   => 'null',
            'audio_us'   => 'null',
            'audio_uk'   => 'null',
        );
        return $data;
        
    }

    public static function GetWordEn($word)
    {
        $response = Http::get('https://www.oxfordlearnersdictionaries.com/search/english/',['q' => $word])->body();
        $html = HtmlDomParser::str_get_html($response);
        if ($html) {
            if (!$html->find('div.didyoumean',0)) {
                $word_en = $html->find('h1.headword',0)->plaintext;
                $word_en = str_replace('1','',$word_en);
                return $word_en;
            }
        }

        $url_us = "https://www.lexico.com/en/definition/".$word."?s=t";
        $html_us = HtmlDomParser::file_get_html($url_us);
        if ($html_us){
            $word_en = $html_us->find('span.hw', 0)->plaintext;
            return $word_en;
        }

        $response = Http::get('https://dictionary.cambridge.org/vi/search/direct/',[
            'q' => $word,
            'datasetsearch' => 'english',
        ])->body();
        $html = HtmlDomParser::str_get_html($response);
        if ($html && $html->find('span.hw.dhw',0)) {
            $word_en = $html->find('span.hw.dhw',0)->plaintext;
            return $word_en;
        }
        return 'null';
    }

    public static function GetDictionary($word)
    {
        $url_us = "https://www.lexico.com/en/definition/".$word."?s=t";
        $html_us = HtmlDomParser::file_get_html($url_us);
        if ($html_us){
            $dic = '';
            $dics = $html_us->find('section.gramb');
            foreach ($dics as $key => $value) {
                $dic = $dic.$value;
                // $dic = $dic.$value->next_sibling();
            };
            return $dic;
        }

        $response = Http::get('https://www.oxfordlearnersdictionaries.com/search/english/',['q' => $word])->body();
        $html = HtmlDomParser::str_get_html($response);
        if ($html) {
            if (!$html->find('div.didyoumean',0)) {
                $dic = $html->find('ol', 0);
                $hide = $html->find('#ring-links-box',0);
                $dic = str_replace($hide,'',$dic);

                return $dic;
            }
        }

    }

}
