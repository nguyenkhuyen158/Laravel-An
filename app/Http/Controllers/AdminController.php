<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use DB;
use Session;

session_start();


class AdminController extends Controller
{
    public function index(){
        $data_blade = array(
            'route_active'  => 'admin.dashboard',
            'item_active'   => '',
        );
        if(!session::get('admin_id')){
            return view('admin.admin-login')->with('data_blade',$data_blade);;
        }else{
            return view('admin.dashboard')->with('data_blade',$data_blade);;
        }
    }

    public function show_dashboard()
    {
        $data_blade = array(
            'route_active'  => 'admin.dashboard',
            'item_active'   => '',
        );
        return view('admin.dashboard')->with('data_blade',$data_blade);;
    }

    public function admin_login(Request $request)
    {
        $admin_email = $request->admin_email;
        $admin_password = md5($request->admin_password);
        
        if (filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
            $admin_request = "admin_email";
        }else{
            $admin_request = "admin_username";
        }
        $result = DB::table('admin')->where([$admin_request => $admin_email, 'admin_password' => $admin_password])->first();
        if ($result) {
            session::put([
                'admin_id' => $result->admin_id,
                'admin_name' => $result->admin_name
            ]);
        }else{
            session::put(['message' => 'Nhập sai tài khoản hoặc mật khẩu']);
        }
        return redirect('/admin');
    }

    public function admin_logout()
    {
        $data_blade = array(
            'route_active'  => 'admin.dashboard',
            'item_active'   => '',
        );
        session::put([
                'admin_id' => null,
                'admin_name' => null
            ]);
        return view('admin.admin-login')->with('data_blade',$data_blade);;
    }

}
