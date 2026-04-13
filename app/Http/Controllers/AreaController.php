<?php
namespace App\Http\Controllers;
use App\area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class AreaController extends Controller
{

	public function __construct()
	{
		  
		    $this->data=array(
             'pageModule'=> 'AreaController',
             'pageUrl'	=>  url('area')
              );
        $this->data['urlmenu']=$this->indexs(); 
		    $this->data['pageFormtype']='ajax';
        $this->data['pageMethod']=\Request::route()->getName();
	}

	// table data
public function getareagrid(Request $request)
{
    if ($request->ajax()) {
        $query = \DB::table('m_area_t')
            ->leftJoin('m_countries_t', 'm_countries_t.country_id', '=', 'm_area_t.country_id')
            ->leftJoin('m_states_t', 'm_states_t.state_id', '=', 'm_area_t.state_id')
            ->leftJoin('m_cities_t', 'm_cities_t.city_id', '=', 'm_area_t.city_id')
            ->leftJoin('tb_users', 'tb_users.id', '=', 'm_area_t.created_by')
            ->select(
                'tb_users.first_name',
                'm_area_t.area_name',
                'm_area_t.area_id',
                'm_countries_t.country_id',
                'm_countries_t.country_name',
                'm_states_t.state_id',
                'm_states_t.state_name',
                'm_cities_t.city_id',
                'm_cities_t.city_name'
            );

        return DataTables::of($query)->make(true);
    }
}
    /*end*/

/*Main Page for Area*/
	public function create($id=null,$type=null)
	{   
		$com=\Session::get('companyid');
    $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
    $this->data['country_id']=$this->jCombologin('m_countries_t','country_id','country_name','');
         $this->data['pageMethod']=\Request::route()->getName();
		
      return view('area.form',$this->data);
   }
/*End*/

/*Save Function*/
	public function save(Request $request)
	{

	  $area = new area();

       $edit_id = $request->input('edit_id'); 
       
        if($edit_id == '')
        {
             $area->country_id=$_POST['country_id'];
			 $area->city_id=$_POST['city_id'];
			 $area->state_id=$_POST['state_id'];
	         $area->area_name=$_POST['area_name'];
	         $area->created_by=$_POST['created_by'];
             $area->created_at =date('Y-m-d H:i:s');
             $area->company_id =\Session::get('companyid');
             $area->organization_id =\Session::get('organization');
             $area->location_id =\Session::get('loc_id');
			       $area->save();
             $edit_id= DB::getPdo()->lastInsertId();
             $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"area",$action,$_POST,"m_area_t");
          return response()->json(array('status' => 'success', 'message' => 'Area Saved Successfully!!','id'=>$edit_id));
        }
        else
        {
             area::find($edit_id)->update($_POST);
             $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"Area",$action,$_POST,"m_area_t");
			  return response()->json(array('status' => 'success', 'message' => 'Area updated Successfully!!','id'=>$edit_id));
        }



	}
	/*End*/

  /*Edit Function*/
	public function areagrddataedit($id=null)
	{
	  $id=$_GET['id'];
		    $column = array('area','area','teritory_name');
        $table = array('app_doctors_adr_t','app_chemist_detail_t','app_stockist_t');
        
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
		return $j;
	}
/*End*/

/*Delete Function*/
  public function destroy($del_id)
    {
	 
      $column = array('area','area','teritory_name');
        $table = array('app_doctors_adr_t','app_chemist_detail_t','app_stockist_t');
      for($i=0; $i<count($table); $i++)
      {
          $j=0;
          $query = \DB::table($table[$i])->where($column[$i],$del_id)->get();
          if(count($query)>0)
          {
              $j=1;
              break;
          }
      }

      if($j==0)
      {
		  // dd($del_id);
           $query = \DB::table('m_area_t')->where('area_id',$del_id)->delete();
            /**Auditlog**/
            $this->auditlog($del_id,"area","delete","","m_area_t");
      }
  return $j;

    }	
/*end*/

	/*deepika purpose:duplicate name function*/
		public function getCheckname(Request $request)
    {

        $edit_id = $_GET['edit_id'];
        $group_name = $_GET['product_group_id'];
        $product_category_id = $_GET['product_category_id'];
        
        if($edit_id == '')
                $group=\DB::table('m_product_subcategory_t')->where('product_group_id',$group_name)->where('product_category_id',$product_category_id)->where('subcategory_name',$_GET['subcategory_name'])->get();
        else
        {
            $whereData = [['subcategory_name', $_GET['subcategory_name']],['product_group_id', '=', $group_name],['product_category_id','=',$product_category_id],['product_subcategory_id', '!=', $edit_id]];
            
            $group=\DB::table('m_product_subcategory_t')->where($whereData)->get();
           
        }
        if(count($group)>0)
            return 1;
        else
            return 0;
    }
/*End*/



}
