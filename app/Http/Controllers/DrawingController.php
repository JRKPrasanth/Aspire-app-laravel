<?php

namespace App\Http\Controllers;
use yajra\datatables\datatables;
use App\Drawing;
use Illuminate\Http\Request;
use DB;
class DrawingController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model = new Drawing();
       $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
       $this->data['pageFormtype']='ajax';
       $this->data['pageModule']='drawing_tbl';
       $this->table=" drawing_tbl";   
       $this->middleware('auth');
        //$this->data['urlmenu']=$this->indexs();
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


               $this->data['department'] =  $this->jcustomselect('m_department_lines_t','department_line_id','sub_department_code|sub_department_name',""," and sub_department_code LIKE '%c010%'");
                $table = \DB::table('drawing_tbl')->get();
                $this->data['datas']=json_encode($table);
                $this->data['pageMethod']="drawingfiles";
		
          return view('drawingfiles.form',$this->data);
    }

    
public function filegrids(Request $request)
{
    if ($request->ajax()) {
        $data = \DB::table('drawing_tbl')
            ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'drawing_tbl.department')
            ->select('drawing_tbl.drawing_id','drawing_tbl.document','drawing_tbl.created_at','m_department_lines_t.sub_department_name','m_department_lines_t.department_line_id');

        return DataTables::of($data)->make(true);
    }
}

    
        
 public function store(Request $request)
    {   
       //dd($_POST);
        //dd("cvcv");
              $edit_id = $request->input('edit_id');
             // dd($edit_id);
        if($edit_id == '')
        {

            
            $drawing= new Drawing();
            $drawing->department = $request->input('department');
            $drawing->document = $request->input('document');
            //dd($drawing->department);
            $drawing->file =  $request->input('file');
            $drawing->updated_at =  "";
            $drawing->created_by=\Session::get('id');
           // dd($drawing);
            $input_data = $request->all();
              $file = $request->file('file');
           // dd($file);
            if($file != "")
            {
                $name = uniqid().'.'.$file->getClientOriginalExtension();

                $destinationPath = public_path('/upload/drawing');
                 // dd($destinationPath);
                $file->move($destinationPath, $name);
               // dd($file);
                $input_data['file'] = $name;
            }
             // dd($request->input('severity_name'));
             $drawing->fill($input_data)->save();

            // dd($drawing);

            return 1;
        }
        else
        {   
            //dd($_POST);

           $action="Edit";
            $edit_id=$_POST['edit_id'];
            drawing::find($edit_id)->update($_POST); 
            $this->auditlog($edit_id,"drawing",$action,$_POST,"drawing_tbl");
            //dd( $this);
            return response()->json(array('status' => 'success', 'message' => 'Drawing Updated Successfully','id'=>$edit_id));
            return 2;
        }
       
    }
    
    /*Ajith Purpose for Used Data Should Not Allow to Edit Function*/
    public function edit(Request $request, $id=null) 
    {

       $column = array('drawing_id');
        $table = array('drawing_tbl');
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
            if(count($query)>0)
            {
                $j=1;
               return $j;
            }
        }
    return $j;
    }

 
    public function destroy(Request $request, $id = null)
  {

            $query =\DB::table('drawing_tbl')->where('drawing_id',$id)->delete();
           

       
        if ($query) {
            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Delete failed. Please try again.'
            ], 500);
        }
		
    }

}
