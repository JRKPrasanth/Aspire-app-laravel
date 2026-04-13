<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Lettercontent;
use Illuminate\Http\Request;
use DB;

class LettercontentController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model=new Lettercontent();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageModule']=\Request::route()->getName();
        $this->data['urlmenu']=$this->indexs();
    }
    /** table index load function  start**/
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

       $this->data['letter_content'] = Lettercontent::all();
        return view('lettercontent.table',$this->data);
    }

     /** table index load function  end**/
     /**create function start **/
    public function create()
    {
         $this->data['letter_type']     = '';
        $this->data['body_content']      = '';
        $this->data['edit_id']      = '';
        $this->data['active']      = '';
	    $this->data['letter_type']=$this->jcustomselecttool('a_lookuplines_t','lookuplines_id','lookup_meaning','',"and lookup_type='LETTER_TYPE'");
		 $this->data['employee_type']=$this->jcustomselecttool('a_lookuplines_t','lookuplines_id','lookup_meaning','',"and lookup_type='EMPLOYEE_TYPE'");
         
        return view('lettercontent.form',$this->data);
    }

    /**create function end **/
        /**save function start **/
    public function save(Request $request)
    {
        
        $edit_id = $request->input('edit_id');
        if($edit_id != "")
        {
            $letter_type = $request->input('letter_type');
            $body_content = $request->input('lettercontent');
            $employee_type = $request->input('employee_type');
            $active = $request->input('active');
            $company_id = \Session::get('companyid');
            $organization =\Session::get('organization');
            $location = "1";
            $last_updated_by =\Session::get('id');
            $updated_at =date('Y-m-d');
            $lettercontent  = DB::table('m_letter_content')->where('id',$edit_id)->update(array('letter_type' => $letter_type,'body_content'=>$body_content,'employee_type'=>$employee_type,'active'=>$active,'company_id'=>$company_id,'location_id'=>$location,'organization_id'=>$organization,'updated_at'=>$updated_at,'last_updated_by'=>$last_updated_by));
            // auditlog
              $this->auditlog($edit_id,"lettercontent","edit",$_POST,"m_letter_content");
            return 2;
        }
        else
        {
            $lettercontent = new Lettercontent();
            $lettercontent->letter_type = $request->input('letter_type');
            $lettercontent->body_content = $request->input('lettercontent');
            $lettercontent->employee_type = $request->input('employee_type');
            $lettercontent->active = $request->input('active');
            $lettercontent->company_id = \Session::get('companyid');
            $lettercontent->organization_id =\Session::get('organization');
            $lettercontent->location_id = "1";
            $lettercontent->created_by = \Session::get('id');
            $lettercontent->created_at = date('Y-m-d');
         
            $result = $lettercontent->save($request->all());
            $name = $lettercontent->getKeyName();
            $id = $lettercontent->$name; 
            $table = $lettercontent->getTable();
            $column = $lettercontent->getKeyName();
            $this->hrmssaveinsert($table,$column,$id,1);
             // auditlog
              $this->auditlog($id,"lettercontent","edit",$_POST,"m_letter_content");
            return 1;
        }
    }

	
    public function edit(Lettercontent $lettercontent,$id = null)
    {
        $lettercontent  = Lettercontent::findOrFail($id); 
        $this->data['letter_type']=$this->jcustomselecttool('a_lookuplines_t','lookuplines_id','lookup_meaning',$lettercontent->letter_type,"and lookup_type='LETTER_TYPE'");
		 $this->data['employee_type']=$this->jcustomselecttool('a_lookuplines_t','lookuplines_id','lookup_meaning',$lettercontent->employee_type,"and lookup_type='EMPLOYEE_TYPE'");
        $this->data['body_content']      = $lettercontent->body_content;
        $this->data['edit_id']      = $lettercontent->id;
        $this->data['active']      = $lettercontent->active;

        return view('lettercontent.form',$this->data);
        
    }
     /**edit page open function end **/


 public function lettercontentgriddata(Request $request)
    {

  		  $SQL = "select * from (SELECT 
                m_letter_content.id,
                m_letter_content.letter_type,
                m_letter_content.active,
                m_letter_content.body_content,
                a_lookuplines_t.lookup_meaning
						from m_letter_content  left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=m_letter_content.letter_type)v1";

        $result = \DB::select( $SQL );
        
        return DataTables::of($result)->make(true);
	 
    }

   
}
