<?php

namespace App\Http\Controllers;
use App\Gstcode;
use App\Gstcodelines;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class GstcodeController extends Controller
{
	
        public function __construct() {
        $this->data = array();
        $this->table = "f_gst_code_hdr_t";
        $this->subtable = "f_gst_code_lines_t";
        $this->pageModule = "gstcode";
        $this->model = new Gstcode;
        $this->submodel = new Gstcodelines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'gstcode',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu']=$this->indexs(); 
        $this->modelname = new Gstcode();
        $this->data['pageFormtype'] = 'ajax';
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

        $table = \DB::table('f_gst_code_hdr_t')->get();
		$this->data['datas'] = $table;
       return view('gstcode.table',$this->data);
    
    }

  
    public function create($id = null) {
      if ($id == null) {
            $this->data['row'] = (object) array();
            $this->data['row']->gst_code_hdr_id = "";
            $this->data['row']->classification_code = "";
            $this->data['row']->classification_name = "";
             $this->data['row']->description = "";
             $this->data['row']->active = "";
            
            $this->data['id'] = '';
            $this->data['linedata'] = array();
            $this->data['tax_group_id'] = $this->jCombo('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
			$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
            $this->data['tax_location_type'] = $this->jcustomselectactive('a_lookuplines_t','lookup_code','lookup_code','','AND lookup_type="TAX_LOCATION_TYPE"');
            }
        else {
            $this->data['id'] = $id;
            $table = \DB::table('f_gst_code_hdr_t')->where('gst_code_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $this->data['row']->classification_name = $table[0]->classification_name;
            $tablelines = \DB::table('f_gst_code_lines_t')->where('gst_code_hdr_id', $id)->get();
            //dd($tablelines);
            $this->data['linedata'] = $tablelines;
//            dd($tablelines);
           $this->data['tax_group_id'] = $this->jCombo('m_tax_group_t', 'tax_group_id', 'tax_group_name', $tablelines[0]->tax_group_id);
           $this->data['tax_location_type'] =  $this->jcustomselectactive('a_lookuplines_t','lookup_code','lookup_code',$tablelines[0]->tax_location_type,'AND lookup_type="TAX_LOCATION_TYPE"');
	   $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
//             dd( $this->data['tax_location_type']);
        }
        
        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->tax_group_id = $this->data['tax_group_id'] = $this->jCombo('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
                $this->data['linedata'][$key]->tax_location_type = $this->data['tax_location_type']= $this->jcustomselectactive('a_lookuplines_t','lookup_code','lookup_code',$value->tax_location_type,'AND lookup_type="TAX_LOCATION_TYPE"');
                }
        }
     
        return view('gstcode.form', $this->data);
    }

  
      /* Karthigaa purpose for  function */
    public function save(Request $request) {

			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file','enable-masterdetail',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
            $data['active'] = $request->active;
		
            $lines_data = $this->validatePost($form, $this->subtable, 'lines');
		foreach($lines_data['start_date'] as $k => $v){
			$lines_data['start_date'][$k] = date('Y-m-d',strtotime($v));
			$lines_data['end_date'][$k] = date('Y-m-d',strtotime($lines_data['end_date'][$k]));
		}
		
        \DB::beginTransaction();
        try {
          
            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'GST Code Saved', 'id' => $id, 'lid' => $lid));
        } catch (\Illuminate\Database\QueryException$e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
        
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }
  
 /*Karthigaa purpose for Display hdr & Lines View function*/ 
  public function show(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('f_gst_code_hdr_t')->select('f_gst_code_hdr_t.*','tb_users.username')->leftjoin('tb_users','tb_users.id','=','f_gst_code_hdr_t.created_by')
		  ->where('gst_code_hdr_id',$id)->get(); 
          $this->data['classification_code']=$vdata[0]->classification_code;  
          $this->data['classification_name']=$vdata[0]->classification_name;
           $this->data['description']=$vdata[0]->description;
           $this->data['active']=$vdata[0]->active;
		   $this->data['username']=$vdata[0]->username;
          $vlinesdata=\DB::table('f_gst_code_lines_t')->leftjoin('m_tax_group_t','m_tax_group_t.tax_group_id','=','f_gst_code_lines_t.tax_group_id')
                  ->where('gst_code_hdr_id',$id)->get();
          $this->data['vlinesdata']=$vlinesdata; 
          $this->data['tax_group_name']=$vlinesdata[0]->tax_group_name;
          $this->data['tax_location_type']=$vlinesdata[0]->tax_location_type;
          $start_date=$vlinesdata[0]->start_date;
          $this->data['start_date']=date(\Session::get('p_date_format'),strtotime($start_date));
          
          $end_date=$vlinesdata[0]->end_date;
          $this->data['end_date']=date(\Session::get('p_date_format'),strtotime($end_date));
          $this->data['active']=$vlinesdata[0]->active;
        
          return view('gstcode.view',$this->data);
        }
    }

    
   /* Karthigaa purpose for Display Data in JQgrid function */
    public function getGstcodeData() {
        $wh = '';

		 $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $wh.='and f_gst_code_hdr_t.company_id='.$compy;
		
        $SQL = "SELECT
    f_gst_code_hdr_t.*,
    tb_users.first_name,
    m_tax_group_t.tax_group_name
FROM
    f_gst_code_hdr_t
LEFT JOIN tb_users ON(
        tb_users.id = f_gst_code_hdr_t.created_by
    )
    LEFT JOIN f_gst_code_lines_t ON f_gst_code_lines_t.gst_code_hdr_id = f_gst_code_hdr_t.gst_code_hdr_id
    LEFT JOIN m_tax_group_t ON m_tax_group_t.tax_group_id = f_gst_code_lines_t.tax_group_id
WHERE
    1 = 1 $wh ";
		
        $result = \DB::select($SQL);
		return DataTables::of($result)->make(true);
    }
 
  public function delete($id=null)
	{
		$count=0;
		$queryquote = \DB::table('m_products_t')->where('hsn_code',$id)->count();
//                dd($queryquote); 
               
		if($queryquote >=1)
		{
		 $count++;
		}
               // dd($count);
		if($count <= 0)
			{
			$query = \DB::table('f_gst_code_hdr_t')->where('gst_code_hdr_id',$id)->delete();
			$query = \DB::table('f_gst_code_lines_t')->where('gst_code_hdr_id',$id)->delete();
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
        /* Karthigaa :purpose for check duplicate entry*/	
	public function gstcodecheckname(Request $request){
            $edit_id = $_REQUEST['edit_id'];
        if($edit_id == '')
            $subinventory=\DB::table('f_gst_code_hdr_t')->where('classification_code',$_REQUEST['classification_code'])->get();
        else
        {
            $whereData = [['classification_code', $_REQUEST['classification_code']],['gst_code_hdr_id', '!=', $edit_id]];
            
            $subinventory=\DB::table('f_gst_code_hdr_t')->where($whereData)->get();
        }
        
        
        if(count($subinventory)>0)
            return 1;
        else
            return 0;
        
        
    }
        
}
