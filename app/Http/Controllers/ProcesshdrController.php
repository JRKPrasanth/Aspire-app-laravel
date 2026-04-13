<?php

namespace App\Http\Controllers;

use App\processhdr;
use App\processlines;
use Illuminate\Http\Request;

class ProcesshdrController extends Controller
{
    public $module="processhdr";
	public function __construct()
	{
		$this->data=array();
                $this->data['urlmenu']=$this->indexs(); 
		$this->table="s_quote_hdr_t";
		$this->subtable="s_quote_lines_t";
		$this->pageModule="processhdr";
		$this->model=new processhdr;
		$this->submodel=new processlines;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
	}
    public function index()
    {
$this->data['process_name']= $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_meaning','',' and lookup_type="Process_name"');
$this->data['machine_name']= $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_meaning','',' and lookup_type="Machine_name"');


      return view("processhdr.form",$this->data);
    }
    public function save(processhdr $processhdr)
    {

      if($_POST['process_hdr_id']=="")
      {
        $id=\DB::table('process_hdr')->insert(['job_card'=>$_POST['job_card_number'],'process'=>$_POST['process']]);
        if($_POST['process']=="35")
        {
          $bulk_line_no=$_POST['bulk_line_no'];
          foreach($bulk_line_no as $k=>$y)
          {
              $sub_id=\DB::table('wire_drawing_tbl')->insert(['process_hdr_id'=>$id,'process'=>$_POST['process'],'line_no'=>$_POST['bulk_line_no'][$k],'machine_id'=>$_POST['bulk_machine_id'][$k],'output_size'=>$_POST['bulk_output_size'][$k],
              'input_size'=>$_POST['bulk_input_size'][$k],'seq_no'=>$_POST['bulk_seq_no'][$k],'planed_qty'=>$_POST['bulk_planed_qty'][$k],'palned_time'=>$_POST['bulk_planed_time'][$k],'packing'=>$_POST['bulk_packing'][$k],'customer'=>$_POST['bulk_customer'][$k]
              ,'achived_qty'=>$_POST['bulk_achived_qty'][$k],'actual_time'=>$_POST['bulk_achived_time'][$k],'remarks'=>$_POST['bulk_remarks'][$k]]);
          }
        }
        else
        {
          $bulk_priority=$_POST['bulk_priority'];
          foreach($bulk_priority as $k=>$y)
          {
              $sub_id=\DB::table('strip_cutting_tbl')->insert(['process_hdr_id'=>$id,'process'=>$_POST['process'],'priority'=>$_POST['bulk_priority'][$k],'profile'=>$_POST['bulk_profile'][$k],'r_or_f'=>$_POST['bulk_rf'][$k],
              'cut_length'=>$_POST['bulk_cutlength'][$k],'planned_qty'=>$_POST['bulk_planned_qty'][$k],'planned_time_from'=>$_POST['bulk_planed_from'][$k],'planned_time_to'=>$_POST['bulk_planed_to'][$k],'opr'=>$_POST['bulk_opr'][$k],'actual_time_from'=>$_POST['bulk_actual_from'][$k]
              ,'actual_time_to'=>$_POST['bulk_actual_to'][$k],'achieved_qty'=>$_POST['bulk_achiveed_qty'][$k],'remarks'=>$_POST['bulk_remarkss'][$k]]);
          }
        }
      }
    }

}
