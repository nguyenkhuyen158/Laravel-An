<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function bookmark_topic()
    {
        $data_blade = array(
            'route_active'  => 'admin.bookmark',
            'item_active'   => 'admin.bookmark-topic',
        );
        return view('admin.bookmark.bookmark-topic')->with('data_blade',$data_blade);
    }


    public function bookmark_word()
    {
        $data_blade = array(
            'route_active'  => 'admin.bookmark',
            'item_active'   => 'admin.bookmark-word',
        );
        return view('admin.bookmark.bookmark-word')->with('data_blade',$data_blade);
    }
}
