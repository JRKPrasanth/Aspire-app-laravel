<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\customertypes;
use Auth;
class LoginController extends Controller
{
    	public function __construct()
	{
            $this->data=array();
            $this->table="m_tb_users";
            $this->pageModule="Login";
            $this->data['pageModule']=$this->pageModule;	
	}

 public function index()	
 {

 }
 public function Signin(Request $request)
 {
  
        $credentials = $request->only('username', 'password');

        if (\Auth::attempt($credentials)) {
            // Authentication passed...

             return redirect ('product');
        }
        else
        {
           
           return view ('Login.Login');
        }
        
        
 }
    public function logout(Request $request) 
   {

   
       
     Auth::logout();
     return redirect('/login');
   }
}
