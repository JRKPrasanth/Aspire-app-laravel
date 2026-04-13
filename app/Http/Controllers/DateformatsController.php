<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Dateformats;
use Illuminate\Http\Request;
use DB;
class DateformatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
		 $table= DB::table('date_formats_tbl')->get(); 
		 $this->data['datas']=json_encode($table); 
		$this->data['pageMethod']='dateformatssettings';
         return view('dateformats.table',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    { 
	     if(isset($id))	
		 {                        
			 $dateformatsdata = Dateformats::find($id); 
			 $this->data['dateformats']=$dateformatsdata;		
		     return view('dateformats.form',$this->data); 
		 }
            $this->data['return_url']='dateformatssettings';
            return view('dateformats.form',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $dateformatsdata = new Dateformats;
		$dateformatsdata->date_formats_id=$request->date_formats_id;
		$dateformatsdata->php_format=$request->php_format;
		$dateformatsdata->javascript_format=$request->javascript_format;
		$dateformatsdata->status=$request->status;
		if($dateformatsdata->date_formats_id==null)
		{
		   $dateformatsdata->save();
		   return response()->json(array('status' => 'success', 'message' => 'your data saved successfully','id'=>$dateformatsdata->date_formats_id));
			// return redirect('dateformatssettings')->with('Success','your data saved successfully');
		}
		else
		{

		   $dateformatsdata->date_formats_id=$request->date_formats_id;
			 $dateformatsdata->find($dateformatsdata->date_formats_id)->update($_POST);
			return response()->json(array('status' => 'success', 'message' => 'your data saved successfully','id'=>$dateformatsdata->date_formats_id));
             // return redirect('dateformatssettings')->with('notice', 'data updated');
		
		}
		
		return redirect('dateformatssettings');
		
    }

 public function dateformatssettingsdelete(Request $request,$id=null)
    {

        $count = 0;
        $queryquote = \DB::table('settings_tbl')->where('date_format',$id)->count();
        if($queryquote >=1)
        {
            $count++;
        }
        if($count <= 0)
            {
            $query = \DB::table('date_formats_tbl')->where('date_formats_id',$id)->delete();
            if($query)
            {
                return 0;
            }
            else
            {
                return 1;
            }
        }
        else{
            return 2;
        }
    }
   /*Harish Purpose for duplicate check*/
    public function dateformatcheck(Request $request)
    {
        $edit_id = $_REQUEST['edit_id'];
        if($edit_id == '')
            $group=\DB::table('date_formats_tbl')->where('php_format',$_REQUEST['php_format'],'javascript_format', $_REQUEST['javascript_format'])->get();
        else
        {
            $whereData = [['php_format', $_REQUEST['php_format']],['javascript_format', $_REQUEST['javascript_format']],['date_formats_id', '!=', $edit_id]];

            $group=\DB::table('date_formats_tbl')->where($whereData)->get();
        }
        if(count($group)>0)
            return 1;
        else
            return 0;


    }
    
    // data	
	public function getDateformatsData(Request $request)
     {
         if ($request->ajax()) {
             $query = \DB::table('date_formats_tbl')
                 ->select('date_formats_tbl.*');
     
             return DataTables::of($query)->make(true);
         }
     }
}
