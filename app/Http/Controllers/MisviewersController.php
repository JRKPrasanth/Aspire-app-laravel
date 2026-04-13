<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;


    class MisviewersController extends Controller
    {
        
        
        // month Wise 
        public function Index(Request $request)
        {

        // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();
        $this->data['pageMethod']=\Request::route()->getName();
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

            $this->data['employee'] = $this->jcustomselecttool("hr_employee_t", "employee_id", "first_name", ""," and group_type=14");
        
        
            $emp_id = $request->input('emp_id');
            $start_date = $request->input('start_date1'); 
            $end_date = $request->input('end_date1');

            $wh="";
            
            if($emp_id !='') {
                $wh .= " AND emp_id='$emp_id'";
            }
            
            if($start_date !='') {
                $wh .= " AND date BETWEEN '$start_date' AND '$end_date'";
            }
         
        // dd($wh);
         
    //dd($wh);
        $this->data['mis_view'] = \DB::select("SELECT tb_users.first_name,tb_menus.menus_name,date_time, DATE_FORMAT(date, '%b-%Y') AS month  FROM `sd_tracker_t` 
        LEFT JOIN tb_users ON tb_users.id = sd_tracker_t.emp_id
        LEFT JOIN  tb_menus ON tb_menus.controller_name = sd_tracker_t.url WHERE 1=1 $wh ORDER BY date DESC");
               
               
        $this->data['view_chart'] = \DB::select("SELECT tb_menus.menus_name as menu, count(url) as count
        FROM sd_tracker_t
        LEFT JOIN  tb_menus ON tb_menus.controller_name = sd_tracker_t.url WHERE 1=1 $wh GROUP BY tb_menus.menus_name");   
               
          $viewChart =    $this->data['view_chart'] ;  
          
               $primData = [];
              
                foreach ($viewChart as $value) {
                    
                $primData[] = [
                    'menu' => $value->menu,
                    'value' =>$value->count,
                ];
            }
            
            $this->data['prim_sumchart'] = json_encode($primData);
              

              
              
        $this->data['viewer_chart'] = \DB::select("SELECT tb_users.first_name as name,count(sd_tracker_t.emp_id) as value FROM `sd_tracker_t` 
        LEFT JOIN tb_users ON tb_users.id = sd_tracker_t.emp_id WHERE 1=1 $wh GROUP BY tb_users.first_name");   
               
          $viewerChart =    $this->data['viewer_chart'] ;  
          
               $chartData = [];
              
                foreach ($viewerChart as $value) {
                    
                $chartData[] = [
                    'name' => $value->name,
                    'count' =>$value->value,
                ];
            }
            
            
            $this->data['top_viewer_chart'] = json_encode($chartData);
            
            
            
              return view('misviewers.table',  $this->data);
     
    }
    
    
    
    
    
    
    
    
    
    
    }