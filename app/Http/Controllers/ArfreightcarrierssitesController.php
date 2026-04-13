<?php

namespace App\Http\Controllers;
use App\Arfreightcarriershdr;

use Illuminate\Http\Request;

class ArfreightcarrierssitesController extends Controller
{
    //

    public function create($id=null)
    {
      
      $arfreightcarriershdr=arfreightcarriershdr::find($id);

      $this->data['frightcarriers']=$arfreightcarriershdr;
//dd($arfreightcarriershdr);


      return view('freightcarriershdr.form',$this->data);

    }




    public function index()
    {
      $table = \DB::table('m_frieghtcarriers_hdr_t')->get();
      $this->data['datas'] = $table;
	//$arfreightcarriershdr= Arfreightcarriershdr::all();
        return view('freightcarriershdr.table',$this->data);
    }

public function save(Request $request)
{
//dd($_POST);
	$arfreightcarriershdr=new arfreightcarriershdr();

	if($_POST['ar_frieghtcarriers_hdr_id']!=""){
	$arfreightcarriershdr->ar_frieghtcarriers_hdr_id=$_POST['ar_frieghtcarriers_hdr_id'];
	}
	$arfreightcarriershdr->carrier_name=(string)$_POST['carrier_name'];
	$arfreightcarriershdr->remarks=(string)$_POST['remarks'];
	$arfreightcarriershdr->source_type_id=(string)$_POST['source_type_id'];
	$arfreightcarriershdr->organization_id=(int)$_POST['organization_id'];
//dd($arfreightcarriershdr->organization_id);



	if($_POST['ar_frieghtcarriers_hdr_id']=='')
	{
	$arfreightcarriershdr->save();

	//return redirect('freightcarriershdr')->with('status', 'You have successfully Created!');
	return redirect('freightcarriershdr')->with('success','your data Saved successfully');
	}
	else
	{
	
	//$id=$_POST['ar_frieghtcarriers_hdr_id'];
	$arfreightcarriershdr=new arfreightcarriershdr();
	$id=$_POST['ar_frieghtcarriers_hdr_id'];
	arfrieghtcarriershdr::find($id)->update($_POST);
	//return redirect('freightcarriershdr')->with('status', 'your data Updated successfully!');
	return redirect('freightcarriershdr')->with('success','your data Updated successfully');


	}	
	}

	public function show(arfreightcarriershdr $arfreightcarriershdr, $id=null)
	{
	
	
	$this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("m_frieghtcarriers_hdr_t");
	$this->data['values'] = arfreightcarriershdr::find($id);
	//dd($this->data);
	return view('freightcarriershdr.view',$this->data);	
	
	}

	public function delete(request $request,$id=null)
	{

	arfreightcarriershdr::destroy($id);
	
//	$test=arfreightcarriershdr::find($id);
	//return redirect('freightcarriershdr')->with('status', 'You have successfully Deleted ID:'.$id.'!');
	return redirect('freightcarriershdr');
	
	
	}






public function getGridData()
{
  $wh='';
  if($_GET['_search']=='true')
  {
  $wh=$this->jqgridsearch($_GET['filters']);
  }

  $page = $_GET['page'];
  $limit = $_GET['rows'];
  $sidx = $_GET['sidx'];
  $sord = $_GET['sord'];
  if(!$sidx) $sidx =1;
  $result = \DB::select("SELECT COUNT(ar_frieghtcarriers_hdr_id) AS count FROM m_frieghtcarriers_hdr_t where 1=1 $wh");
  $count = $result[0]->count;
  if( $count > 0 && $limit > 0)
  {
  $total_pages = ceil($count/$limit);
  } else {
  $total_pages = 0;
  }
  if ($page > $total_pages)
  $page=$total_pages;
  $start = $limit*$page - $limit;
  if($start <0) $start = 0;
  $SQL = "SELECT * FROM m_frieghtcarriers_hdr_t where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
  $result = \DB::select( $SQL );
  $responce->rows[]='';
  $responce->rows=$result;
  $responce->page = $page;
  $responce->total = $total_pages;
  $responce->records = $count;
  echo json_encode($responce);
}


}
