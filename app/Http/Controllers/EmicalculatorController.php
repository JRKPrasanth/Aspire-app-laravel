<?php

namespace App\Http\Controllers;

use App\Emicalculator;
use Illuminate\Http\Request;

class EmicalculatorController extends Controller
{

	
    public function index(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();
        
        $Controller = new Controller();
        $access = $Controller->Accessdined();
        
        $userAccess = json_decode($access, true);
        
        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }
        
        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');
               
        }

        // END
	  $this->data['pageMethod']=\Request::route()->getName();
      $this->data['urlmenu']=$this->indexs();
      return view('emicalculator.index', $this->data);
         
    }


}
