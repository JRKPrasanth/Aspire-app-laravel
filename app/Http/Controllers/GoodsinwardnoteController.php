<?php

namespace App\Http\Controllers;

use App\Goodsinwardnote;
use App\Ginlines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Validator,DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class GoodsinwardnoteController extends Controller
{
    public $module="goodsinwardnote";
    public function __construct()
	{
            $this->data=array();
             $this->table="p_gin_hdr_t";
		$this->subtable="p_gin_lines_t";
		$this->pageModule="goodsinwardnote";
		$this->model=new Goodsinwardnote;
		$this->submodel=new Ginlines;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
		$this->data=array(
                    'pageModule'=> 'goodsinwardnote',
                    'pageUrl'	=>  url('goodsinwardnote'),
                    'pageMethod'=>$this->data['pageMethod']
                  );
                $this->data['urlmenu']=$this->indexs(); 
	}
	
     /* Purpose For :Index Function to Call Table Blade*/
    public function index(Request $request){ 
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

		$this->data['suppliername']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name'); 
		$table = \DB::table('p_gin_hdr_t')->get();
		$this->data['datas'] = json_encode($table);
        $this->data['pageMethod']="goodsinwardnote";
         return view('goodsinwardnote.table',$this->data);
    }
	
  /*  purpose for Display Data in JQgrid function */
    public function getgoodsinwardData()
	{
				$wh='';
                $loc="1";
                $compy=\Session::get('companyid');		
				$groupname=\Session::get('groupname');

				$wh = $grid_data=$this->grid_check('p_gin_hdr_t','dc_date');

               $SQL = "SELECT p_gin_hdr_t.p_gin_hdr_id,p_gin_hdr_t.gin_number,
			m_supplier_t.supplier_name,m_subcontract_supplier_t.subcontract_name,
			p_gin_hdr_t.dc_number,p_gin_hdr_t.dc_date,p_gin_hdr_t.total_packs,p_gin_hdr_t.supplier_type,p_gin_hdr_t.gin_status
			FROM p_gin_hdr_t LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_gin_hdr_t.supplier_id
			LEFT JOIN m_subcontract_supplier_t ON m_subcontract_supplier_t.subcontract_supplier_id = p_gin_hdr_t.subcontract_supplier_id
            where 1=1 $wh order by p_gin_hdr_t.p_gin_hdr_id DESC";

		
		$result = \DB::select( $SQL );
		
		 return DataTables::of($result)->make(true);
	}	
	
	
    /* Purpose For Create Function*/
    public function goodsinwardnotecreate($id=null)
	{ 
                  $row=$this->model::find($id);
                   $this->data['row']['p_gin_hdr_id']='';
                   $this->data['row']['gin_number']='';
                   $this->data['row']['gin_description']='';
                   $this->data['row']['dc_number']='';
                   $this->data['row']['total_packs']='';
                   $this->data['row']['gin_status']='DRAFT';
                   $this->data['row']['save_status']='';
                   $this->data['row']['dc_date']=date('Y-m-d');
                   $this->data['row']['supplier_type']='';
           
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_number|supplier_name','');
                $this->data['subcontract_supplier_id'] = $this->jCombo(' m_subcontract_supplier_t','subcontract_supplier_id','subcontract_number|subcontract_name','');
                $this->data['supnameopt']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
                $this->data['suptypeopt']=$this->jqgridselect('m_suppliertypes_t','suppliertype_id','suppliertype_name');
                $this->data['country']=$this->jqgridselectlogin('m_countries_t','country_id','country_name');
		$this->data['state']=$this->jqgridselectlogin('m_states_t','state_id','state_name');
		$this->data['city']=$this->jqgridselectlogin('m_cities_t','city_id','city_name');
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
                $this->data['prdgrpopt']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');
		 $this->data['supplier_site_id'] = $this->jCombo('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_name', '');
                 $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
	  return view('goodsinwardnote.form',$this->data);
	}
   /*Karthigaa Purpose For Edit Function*/
    public function goodsinwardnoteedit($id=null){
		       	  $row=\DB::select("SELECT * FROM p_gin_hdr_t where p_gin_hdr_id='$id'");
                   $this->data['row']['p_gin_hdr_id']= $row[0]->p_gin_hdr_id;
                   $this->data['row']['gin_number']=$row[0]->gin_number;
                   $this->data['row']['gin_description']=$row[0]->gin_description;
                   $this->data['row']['dc_number']=$row[0]->dc_number;
                   $this->data['row']['total_packs']=$row[0]->total_packs;
                   $this->data['row']['dc_date']=$row[0]->dc_date;
                   $this->data['row']['save_status']=$row[0]->save_status;
                   $this->data['row']['gin_status']=$row[0]->gin_status;
                   $this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_number|supplier_name',$row[0]->supplier_id);
                $this->data['supnameopt']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
                $this->data['suptypeopt']=$this->jqgridselect('m_suppliertypes_t','suppliertype_id','suppliertype_name');
                $this->data['country']=$this->jqgridselectlogin('m_countries_t','country_id','country_name');
		$this->data['state']=$this->jqgridselectlogin('m_states_t','state_id','state_name');
		$this->data['city']=$this->jqgridselectlogin('m_cities_t','city_id','city_name');
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
                $this->data['prdgrpopt']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
		return view('goodsinwardnote.form',$this->data);
		
	}

    /* purpose for Save function*/   
	
    public function save(Request $request){
	
        $id = '';
        $form = $request->all();
        $dataupload = "";
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file',
            'po_date_hidden',
            'bill_to_address',
            'ship_to_address',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
             /* Purpose for Auto Number*/
               if ($_POST['gin_number'] =="") 
				{
					$seqno=$this->Seqnoe('GIN-','p_gin_hdr_t','','gin_count');
					$data['gin_number'] = $seqno[0];
                    $data['gin_count'] = $seqno[1];
				}
				else
				{
					$seqno[0] = $_POST['gin_number'];
				}


			\DB::beginTransaction();
			try
			{
				$id=$this->model->insertRow($data);
				\DB::commit();
                                 /**Auditlog**/
                                if($_POST['p_gin_hdr_id']==""){
                                            $action="create";
                                            }else{
                                            $action="edit";
                                            }
                                     $this->auditlog($id,"goodsinwardnote",$action,$_POST,"p_gin_hdr_t");
                                
				return response()->json(array('status' => 'success', 'message' => 'GIN Saved'));
			}
			catch (\Illuminate\Database\QueryException$e)
			{
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}	

        }
	
        /*End*/
	
    /* Purpose For View Function*/
    public function goodsinwardnoteview($id=null){
     	$this->data['p_gin_hdr_id'] = $id;
	$this->data['ginvdata'] =$table = DB::table('p_gin_hdr_t')->select('m_supplier_t.supplier_name','m_supplier_t.supplier_id','tb_users.id','p_gin_hdr_t.*')
				->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_gin_hdr_t.supplier_id')
			    	->leftJoin('m_organizations_t','m_organizations_t.organization_id','=','p_gin_hdr_t.organization_id')
                                ->leftJoin('tb_users','tb_users.id','=','p_gin_hdr_t.created_by')
				->where('p_gin_hdr_id',$id)->get();
        $this->data['supplier_name'] = $this->idname('supplier_name','m_supplier_t','supplier_id',$this->data['ginvdata'][0]->supplier_id);
        $this->data['created_by'] = $this->idname('username','tb_users','id',$this->data['ginvdata'][0]->created_by);
        return view('goodsinwardnote.view',$this->data);
		
     }
   /*End*/
     

  public function getSubcontractgridData(){
		
        $wh='';

	if(!empty($_GET['site_type'])){
		$wh.=" and m_subcontract_sites_t.site_type='".$_GET['site_type']."' and m_subcontract_sites_t.subcontract_supplier_id=".$_GET['cid'];
	}

		$SQL = "SELECT m_subcontract_supplier_t.subcontract_supplier_id as subcontract_supplierid,m_subcontract_sites_t.*,m_subcontract_supplier_t.subcontract_number as subcontract_number,m_subcontract_supplier_t.subcontract_name,m_suppliertypes_t.suppliertype_name,m_subcontract_sites_t.subcontract_site_name as subcontract_site_name,m_subcontract_sites_t.site_type as site_type,m_subcontract_sites_t.address as address,m_cities_t.city_name,m_states_t.state_name,m_countries_t.country_name FROM `m_subcontract_supplier_t` left join m_subcontract_sites_t on(m_subcontract_sites_t.subcontract_supplier_id=m_subcontract_supplier_t.`subcontract_supplier_id`)left join m_suppliertypes_t on(m_suppliertypes_t.suppliertype_id=m_subcontract_supplier_t.`supplier_type_id`) left join m_countries_t on(m_countries_t.country_id=m_subcontract_sites_t.`country`)left join m_cities_t on(m_cities_t.city_id=m_subcontract_sites_t.`city`)left join m_states_t on(m_states_t.state_id=m_subcontract_sites_t.`state`)where 1=1 and m_subcontract_supplier_t.active= 'Yes' and m_subcontract_supplier_t.active='Yes' $wh";
		
	$result = \DB::select( $SQL );
	return DataTables::of($result)->make(true);
		
}

	
	
}
