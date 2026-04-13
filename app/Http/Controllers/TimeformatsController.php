<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Timeformats;
use Illuminate\Http\Request;

class TimeformatsController extends Controller
{

    public function index()
    {   
		 $table=\DB::table('time_formats_tbl')->get();
	     $this->data['datas'] = json_encode($table);  //dd( $this->data['datas']);
		$this->data['pageMethod']='timeformats';
		  return view('timeformats.table',$this->data);
    }

    public function create($id=null)
    { 
		if(isset($id))
		{
		  $timefmdata = Timeformats::find($id);
		  $this->data['timefmdata']=$timefmdata;  
			return view('timeformats.form',$this->data);
		}
		$this->data['return_url']='timeformats';		
				
        return view('timeformats.form',$this->data);
    }

    public function save(Request $request)
    { 
        $timeformatsdata = new Timeformats; 
		$edit_id = $_POST['time_formats_id'];
		if($_POST['time_formats_id']=='')
		{
			$timeformatsdata->php_format=$request->php_format; 
			$timeformatsdata->js_format=$request->js_format; 
			$timeformatsdata->display_format=$request->display_format; 

			$timeformatsdata->save();

			$id = $request->time_formats_id;
			return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id));
		}
		else
		{  
			$timefor['php_format']=$request->php_format; 
			$timefor['js_format']=$request->js_format; 
			$timefor['display_format']=$request->display_format; 
			$timefor['created_by']=\Session::get('id');
            $timefor['updated_by']=\Session::get('id');
            $timefor['updated_at']=date('Y-m-d h:s:i');
            $timefor['created_at']=date('Y-m-d h:s:i');
            $timefor['organization_id']=\Session::get('organization');
            $timefor['location_id']=\Session::get('location');
            $timefor['company_id']=\Session::get('companyid');

			$update = \DB::table('time_formats_tbl')->where('time_formats_id',$edit_id)->update($timefor);

			return response()->json(array('status' => 'success', 'message' => 'Updated Successfully','id' => $edit_id));
		}
		
		return redirect('timeformats');
    }


	 /*Harish Purpose for duplicate check*/
    public function timeformatcheck(Request $request)
    {
        $edit_id = $_REQUEST['edit_id'];
        if($edit_id == '')
            $group=\DB::table('time_formats_tbl')->where('php_format',$_REQUEST['php_format'],'js_format', $_REQUEST['js_format'],'display_format', $_REQUEST['display_format'])->get();
        else
        {
            $whereData = [['php_format', $_REQUEST['php_format']],['js_format', $_REQUEST['js_format']],['display_format', $_REQUEST['display_format']],['time_formats_id', '!=', $edit_id]];

            $group=\DB::table('time_formats_tbl')->where($whereData)->get();
        }
        
    	// dd($group);
        if(count($group)>0)
            return 1;
        else
            return 0;


    }

	public function timeformatdelete($id=null)
    {
        $count=0;
        if($count <= 0)
        {
            $query = \DB::table('time_formats_tbl')->where('time_formats_id',$id)->delete();
            if($query)
            {
                return 0;
            }
            else
            {
                return 1;
            }

        }
        else
        {
            return 2;
        }
    }

	
	// data	
	public function gettimeformatsData(Request $request)
     {
         if ($request->ajax()) {
             $query = \DB::table('time_formats_tbl')
                 ->select('time_formats_tbl.*');
     
             return DataTables::of($query)->make(true);
         }
     }
	
}
