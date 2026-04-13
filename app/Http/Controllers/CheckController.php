<?php

namespace App\Http\Controllers;
use DB;
use validate;
use App\checklist;
use Illuminate\Http\Request;
use yajra\datatables\datatables;

class CheckController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model = new checklist();
        $this->data['pageMethod']=\Request::route()->getName();
                $this->data['pageModule']='checklist';
        $this->data['pageFormtype']='ajax';
        $this->table=" checklist_tbl";   
                $this->middleware('auth');
                                $this->data['urlmenu']=$this->indexs(); 


    }
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

        $this->data['row']= (object)array();
        $this->data['row']->frequency_name=$this->jCombologin('frequency_tbl','frequency_id','frequency_name','');

        $this->data['pageMethod']="checklist";
        return view('checklist.form',$this->data);
    }


    
    public function save(Request $request)
    {   

        $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
  

            $checklist= new checklist();
            $checklist->checklist_name = $request->input('checklist_name');      
            $checklist->terms =  $request->input('terms');
            $checklist->updated_at ="";
            $checklist->created_at ="";
                
             $input_data = $request->all();
              $image = $request->file('file');

             //dd($image);
             
                     
            if($image != "")
            {
                $name = uniqid().'.'.$image->getClientOriginalExtension();
            
                $destinationPath = public_path('/upload/checklist');
                
                $image->move($destinationPath, $name);

                $input_data['file'] = $name;

            }  
    
          $checklist->fill($input_data)->save();
          // $id = $checklist->description_id;
          //   $table = $checklist->getTable();
          //   $column = $checklist->getKeyName();
          //   $this->hrmssaveinsert($table,$column,$id,1);
          //   //auditlog
          //   $this->auditlog($id,"checklist","create",$checklist,"checklist_tbl");


          
            return 1;
        }
        else
        {  $checklist  = checklist::findOrFail($edit_id);
             $file_upload = $request->file('file');
             $file_save='';
            if($file_upload != "")
            {
                $old_file = $checklist->file;
            
                $name = uniqid().'.'.$file_upload->getClientOriginalExtension();

                $destinationPath = public_path('/upload/checklist');
                $file_upload->move($destinationPath, $name);
                $checklist->file =$file_save= $name;
                if($old_file!=''){
                $myPublicFolder = public_path();
                $old_file = public_path().'/upload/checklist'.$old_file;
                unlink($old_file);
                }
            }
            $input_data = $request->all();
              DB::table('checklist_tbl')
                ->where('checklist_id', $edit_id)
                ->update(['checklist_name' =>$input_data['checklist_name'] ,'file'=>$file_save,'terms'=>$input_data['terms'],'updated_at'=>date('Y-m-d')]);
        
                 //$checklist->save(); 
           // $table = $checklist->getTable();
           //  $column = $checklist->getKeyName();
           //  $this->hrmssaveinsert($table,$column,$edit_id,2);s
           //  //auditlog
           //  $this->auditlog($edit_id,"checklist","Update",$_POST,"checklist_tbl");
            return 2;
        }
       
    }

    
  public function checklistgrid(Request $request)
{
    if ($request->ajax()) {
        $data = \DB::table('checklist_tbl')
 
            ->select('checklist_tbl.*');

        return DataTables::of($data)->make(true);
    }
}
    
    public function destroy($id=null)
 
     {

    $column = array('checklist_id');

        $table = array('checklist_lines_tbl');
        for($i=0; $i<count($table); $i++)
        {
           
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
        if($j==0)
        {
           // dd("fdg");
            $query = \DB::table('checklist_tbl')->where('checklist_id',$id)->delete();
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id,"checklist",$action,$id,"checklist_tbl");
            
        }

    return $j;

    }  
}
