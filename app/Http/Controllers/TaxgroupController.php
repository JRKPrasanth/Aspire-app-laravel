<?php

namespace App\Http\Controllers;

use App\Taxgroup;
use App\Taxgrouplines;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class TaxgroupController extends Controller
{
        public function __construct() {
        $this->data = array();
        $this->table = "m_tax_group_t";
        $this->subtable = "m_tax_group_lines_t";
        $this->pageModule = "taxgroup";
        $this->model = new Taxgroup;
        $this->submodel = new Taxgrouplines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'taxgroup',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu']=$this->indexs(); 
        $this->modelname = new Taxgroup();
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

       $table = \DB::table('m_tax_group_t')->get();
       $this->data['datas'] = $table;
       return view('taxgroup.table',$this->data);
    }

  
   public function create($id = null) {
      if ($id == null) {
            $this->data['row'] = (object) array();
            $this->data['row']->tax_group_id = "";
            $this->data['row']->tax_group_name = "";
            $this->data['row']->display_name = "";
            $this->data['row']->active = "";
             
            $this->data['tax_category'] = $this->jCombo('f_tax_category_t','tax_category_id','tax_category_name','');
            $this->data['tax_code_name'] = $this->jCombo('f_tax_code_t','tax_code_id','tax_code_name','');
            $this->data['input_tax_account_id'] =$this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['output_tax_account_id'] =$this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
			$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
            $this->data['id'] = '';
            $this->data['linedata'] = array();
            
            }
        else {
            $this->data['id'] = $id;
            $table = \DB::table('m_tax_group_t')->where('tax_group_id', $id)->get();
            $this->data['row'] = $table[0];
			$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
            $this->data['row']->tax_group_name = $table[0]->tax_group_name;
            $tablelines = \DB::table('m_tax_group_lines_t')->where('tax_group_id', $id)->get();
            $this->data['linedata'] = $tablelines;
//            dd($tablelines);
//             $this->data['tax_category'] = $this->jCombo('f_tax_category_t','tax_category_id','tax_category_name',$tablelines[0]->tax_category);
//             dd($this->data['tax_category']);
//            $this->data['tax_code_name'] = $this->jCombo('f_tax_code_t','tax_code_id','tax_code_name','');
          
        }
          if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                //dd($value);
                $this->data['linedata'][$key]->tax_category = $this->data['tax_category'] = $this->jCombo('f_tax_category_t', 'tax_category_id', 'tax_category_name', $value->tax_category);
                $this->data['linedata'][$key]->tax_code_name = $this->data['tax_code_name'] = $this->jCombo('f_tax_code_t', 'tax_code_id', 'tax_code_name', $value->tax_code_name);
                $this->data['linedata'][$key]->input_tax_account_id = $this->data['input_tax_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->input_tax_account_id);
                $this->data['linedata'][$key]->output_tax_account_id = $this->data['output_tax_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->output_tax_account_id);
//                dd($this->data);
                }
        }  
        
//dd($this->data);
        return view('taxgroup.form', $this->data);
    }


      /* Karthigaa purpose for Save function */
    public function save(Request $request) {
//        dd("sad");
        $id = '';
        $data = $this->validatePost($request->all(), $this->table, 'header');
        $lines_data = $this->validatePost($request->all(), $this->subtable, 'lines');
        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Tax Group Saved', 'id' => $id, 'lid' => $lid));
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
          $vdata=\DB::table('m_tax_group_t')->select('m_tax_group_t.*','tb_users.username')->leftjoin('tb_users','tb_users.id','=','m_tax_group_t.created_by')
		  ->where('tax_group_id',$id)->get(); 
//          dd($vdata);
          $this->data['tax_group_name']=$vdata[0]->tax_group_name;  
          $this->data['display_name']=$vdata[0]->display_name;
          $this->data['active']=$vdata[0]->active;
		  $this->data['username']=$vdata[0]->username;
          $vlinesdata=\DB::table('m_tax_group_lines_t')->select('m_tax_group_lines_t.*','f_tax_category_t.tax_category_name','f_tax_code_t.tax_code_name','output.concatenated_segments as output_segments','input.concatenated_segments as input_segments')
                  ->leftjoin('f_tax_category_t','f_tax_category_t.tax_category_id','=','m_tax_group_lines_t.tax_category')
                  ->leftjoin('f_tax_code_t','f_tax_code_t.tax_code_id','=','m_tax_group_lines_t.tax_code_name')
                  ->leftjoin('f_account_structure_t as input','input.f_account_structure_id','=','m_tax_group_lines_t.input_tax_account_id')
                  ->leftjoin('f_account_structure_t as output','output.f_account_structure_id','=','m_tax_group_lines_t.output_tax_account_id')
                  ->where('tax_group_id',$id)->get();
          
          $this->data['vlinesdata']=$vlinesdata; 
//          dd($vlinesdata);
          $this->data['tax_category']=$vlinesdata[0]->tax_category_name;
          $this->data['tax_code_name']=$vlinesdata[0]->tax_code_name;
          $this->data['input_segments']=$vlinesdata[0]->input_segments;
          $this->data['output_segments']=$vlinesdata[0]->output_segments;
          $this->data['active']=$vlinesdata[0]->active;
          
          return view('taxgroup.view',$this->data);
        }
    }

    
   
    public function getTaxgroupData() {
        $wh = '';

		$org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
		$wh.='and m_tax_group_t.company_id='.$compy;
		
        
        $SQL = "SELECT m_tax_group_t.*,tb_users.first_name FROM m_tax_group_t left join tb_users on (tb_users.id =m_tax_group_t.created_by) where 1=1 $wh";
		

        $result = \DB::select($SQL);
		
	return DataTables::of($result)->make(true);
		
    }
  
    public function delete($id=null){
		$count=0;
		$queryquote = \DB::table('f_gst_code_lines_t')->where('tax_group_id',$id)->count();
		if($queryquote >=1){
		 $count++;
		}
		if($count <= 0){
			$query = \DB::table('m_tax_group_t')->where('tax_group_id',$id)->delete();
			$query = \DB::table('m_tax_group_lines_t')->where('tax_group_id',$id)->delete();
			if($query){
				return 0;
			}
			else{
				return 1;
			}
		}
		else{
			return 2;
		}
	}
        /* Karthigaa :purpose for check duplicate entry*/	
public function getCheckname(Request $request)
{
       //dd($request);
        $edit_id = $_REQUEST['edit_id'];
        if($edit_id == '')
            $taxgroup=\DB::table('m_tax_group_t')->where('tax_group_name',$_REQUEST['tax_group_name'])->get();
        else
        {
            $whereData = [['tax_group_name', $_REQUEST['tax_group_name']],['tax_group_id', '!=', $edit_id]];
            
            $taxgroup=\DB::table('m_tax_group_t')->where($whereData)->get();
        }
        
        
        if(count($taxgroup)>0)
            return 1;
        else
            return 0;
        
        
    }
	/*end*/
        
            
}
