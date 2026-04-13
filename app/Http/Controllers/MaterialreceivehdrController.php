<?php
namespace App\Http\Controllers;

use App\Materialreceivehdr;
use App\Materialreceivelines;
use Illuminate\Http\Request;
use Redirect;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class MaterialreceivehdrController extends Controller
{
  /* purpose: construct function to set values throughout the controller like model,submodel,pagemethod*/
  public function __construct()
  {
    $this->data = array();
    $this->data['urlmenu'] = $this->indexs();
    $this->model = new Materialreceivehdr;
    $this->submodel = new Materialreceivelines;
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['pageFormtype'] = 'ajax';
    $this->data['pageModule'] = 'materialreceive';
    $this->table = "w_materialreceive_hdr_t";
    $this->subtable = "w_materialreceive_line_t";
    if ($this->data['pageMethod'] == "materialreceive") {
      //$this->data['status']='OPEN';
      $this->data['status'] = '';
    } else {
      $this->data['status'] = '';
    }

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

    if ($this->data['pageMethod'] == "materialreceive") {
      $this->data['pageurl'] = "materialreceive";
      $this->data['url'] = 'materialreceivecreate';
      $this->data['source'] = 'MATERIALRECEIVE';
      $this->data['type'] = 'sfg';
    } else if ($this->data['pageMethod'] == "packingmaterialreceive") {
      $this->data['pageurl'] = "packingmaterialreceive";
      $this->data['url'] = 'materialreceivecreate';
      $this->data['source'] = 'PACKINGMATERIALRECEIVE';
      $this->data['type'] = 'fg';

    }

    return view('materialreceive.table', $this->data);
  }

  public function recieveindex(Request $request)
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

    if ($this->data['pageMethod'] == "mtlreceivedetails") {
      $this->data['pageurl'] = "mtlreceivedetails";
      $this->data['url'] = 'mtlrecievedetailscreate';
      $this->data['source'] = 'MATERIALRECEIVEDETAILS';
      $this->data['type'] = 'sfg';
    } else if ($this->data['pageMethod'] == "packingmtlreceivedetails") {
      $this->data['pageurl'] = "packingmtlreceivedetails";
      $this->data['url'] = '';
      $this->data['source'] = 'PACKINGMATERIALRECEIVE';
      $this->data['type'] = 'fg';
    }
    return view('mtlrecievedetails.table', $this->data);
  }

  public function show($id = null)
  {
    $data = DB::table('w_materialreceive_hdr_t')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'w_materialreceive_hdr_t.product_id')->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'w_materialreceive_hdr_t.uom_code_id')->leftjoin('w_jobcard_hdr_t', 'w_jobcard_hdr_t.w_jobs_hdr_id', '=', 'w_materialreceive_hdr_t.w_jobs_hdr_id')->where('w_materialreceive_hdr_t.w_materialreceive_hdr_id', $id)->get();
    $this->data['product_code'] = $data[0]->product_code;
    $this->data['concatenated_product'] = $data[0]->concatenated_product;
    $this->data['uom_code'] = $data[0]->uom_code;
    $this->data['job_no'] = $data[0]->job_no;
    $this->data['batch_no'] = $data[0]->batch_no;
    $this->data['job_status'] = $data[0]->job_status;
    $this->data['job_process'] = $data[0]->job_process;
    $this->data['mtl_receive_date'] = $data[0]->mtl_receive_date;
    $this->data['job_qty'] = $data[0]->job_qty;
    $this->data['remarks'] = $data[0]->remarks;

    $received_by = \DB::select("SELECT tb_users.first_name, w_materialreceive_hdr_t.created_by FROM w_materialreceive_hdr_t LEFT JOIN tb_users ON w_materialreceive_hdr_t.created_by = tb_users.id where w_materialreceive_hdr_t.w_materialreceive_hdr_id = $id");
    $this->data['received_by'] = $received_by[0]->first_name;
    $linesdata = \DB::table('w_materialreceive_line_t')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'w_materialreceive_line_t.product_id')->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'm_uom_codes_t.uom_code_id')->leftjoin('m_sublocators_t', 'm_sublocators_t.sublocator_id', '=', 'w_materialreceive_line_t.locator_id')->where('w_materialreceive_line_t.w_materialreceive_hdr_id', $id)->groupBy('w_materialreceive_line_t.w_materialreceive_line_id')->get();
    $this->data['linesdata'] = $linesdata;
    //dd($linesdata);
    if (isset($_GET)) {
      $this->data['pageurl'] = $_GET['pageurl'];
    }

    return view('materialreceive.view', $this->data);


  }

  public function getmtlrecievedetails()
  {

    $wh = '';


    if (isset($_GET)) {
      if ($_GET['type'] == 'fg') {
        $wh .= " and m_product_groups_t.group_name='FINISHED GOODS' ";
      } else {
        $wh .= " and m_product_groups_t.group_name='SEMI FINISHED GOODS' ";
      }
    }

    $wh .= $grid_data = $this->grid_check('w_materialreceive_hdr_t', 'mtl_receive_date');

    $sql = "SELECT
  w_materialreceive_hdr_t.w_jobs_hdr_id,
  w_materialreceive_hdr_t.w_materialreceive_hdr_id,
    w_materialreceive_hdr_t.batch_no,
    w_materialreceive_hdr_t.mtl_receive_date,
    w_jobcard_hdr_t.job_no,
    w_jobcard_hdr_t.job_status,
    w_jobcard_hdr_t.job_process,
    w_jobcard_hdr_t.job_qty,
    m_products_t.concatenated_product,
	 m_products_t.product_code,
    m_uom_codes_t.uom_code
FROM
    `w_materialreceive_hdr_t`
LEFT JOIN m_products_t ON
    (
        w_materialreceive_hdr_t.product_id = m_products_t.product_id
    )
	 LEFT JOIN m_product_groups_t ON(m_products_t.product_group_id= m_product_groups_t.product_group_id)
LEFT JOIN m_uom_codes_t ON (m_uom_codes_t.uom_code_id = w_materialreceive_hdr_t.uom_code_id)   left join w_jobcard_hdr_t on(w_jobcard_hdr_t.w_jobs_hdr_id=w_materialreceive_hdr_t.w_jobs_hdr_id) where 1=1 $wh group by w_materialreceive_hdr_t.w_materialreceive_hdr_id ORDER BY w_materialreceive_hdr_t.w_materialreceive_hdr_id DESC";

    $result = \DB::select($sql);

    return DataTables::of($result)->make(true);
  }


  public function create($id = null)
  {

    $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'Materialissue')->get();

    if (isset($_GET['source'])) {
      if ($_GET['source'] == "MATERIALRECEIVE" || $_GET['source'] == "PACKINGMATERIALRECEIVE") {
        if ($_GET['source'] == "MATERIALRECEIVE") {
          $this->data['pageurl'] = "materialreceive";
        } else if ($_GET['source'] == "PACKINGMATERIALRECEIVE") {
          $this->data['pageurl'] = "packingmaterialreceive";
        }
        $this->data['url'] = "materialreceivecreate";

        $this->data['id'] = $id;
        $this->data['w_materialreceive_hdr_id'] = "";
        $this->data['pagemode'] = "edit";
        $this->data['source'] = $_GET['source'];
        $table = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->get();
        $this->data['organization_id'] = $this->jcombo("m_organizations_t", "organization_id", "organization_name", \Session::get('organization'));
        $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $table[0]->uom_code_id);

        $this->data['ass_product_id'] = $this->jCombo('m_products_t', 'product_id', ' product_code|concatenated_product', $table[0]->product_id);

        $this->data['w_jobs_hdr_id'] = $this->jCombocomp('w_jobcard_hdr_t', 'w_jobs_hdr_id', 'job_no', $table[0]->w_jobs_hdr_id);
        $this->data['job_qty'] = $table[0]->job_adjusted_qty;
        $this->data['job_status'] = $table[0]->job_status;
        $this->data['batch_no'] = $table[0]->batch_no;
        $this->data['remarks'] = $table[0]->remarks;
        $this->data['process'] = $table[0]->bom_process;
        $this->data['job_process'] = $table[0]->job_process;
        date_default_timezone_set('Asia/Calcutta');
        $this->data['mtl_receive_date'] = date("d-m-Y H:i:s");
        $group = \DB::table('m_products_t')->select('m_products_t.*', 'm_product_groups_t.*')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('m_products_t.product_id', $table[0]->product_id)->get();

        $job_adjusted_qty = 0;
        if ($group[0]->group_name == "FINISHED GOODS") {
          if (($table[0]->bom_process != "") && ($table[0]->bom_process != '0')) {
            if ($table[0]->bom_process == "PROCESS-2") {
              $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-1' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");
            } else if ($table[0]->bom_process == "PROCESS-3") {
              $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-2' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");
              if (count($jobprs) <= 0) {
                $jobprsfind = \DB::select("select bom_process from w_jobcard_hdr_t where product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc LIMIT 1");
                $procc = $jobprsfind[0]->bom_process;
                //dd($procc);
                $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='$procc' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");
                //dd($jobprs);
              }
              //dd($jobprs);
            } else if ($table[0]->bom_process == "PROCESS-4") {
              $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-3' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");
              if (count($jobprs) <= 0) {
                $jobprsfind = \DB::select("select bom_process from w_jobcard_hdr_t where product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc LIMIT 1");
                $procc = $jobprsfind[0]->bom_process;
                $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='$procc' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");

              }
            } else if ($table[0]->bom_process == "FINALPROCESS") {
              $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-4' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");
              if (count($jobprs) <= 0) {
                $jobprsfind = \DB::select("select bom_process from w_jobcard_hdr_t where product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc LIMIT 1");
                $procc = $jobprsfind[0]->bom_process;
                $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='$procc' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");
              }
            } else {
              $jobprs = array();
              $job_adjusted_qty = 0;

            }
            if (count($jobprs) > 0) {
              $job_adjusted_qty = $jobprs[0]->job_adjusted_qty;
            } else {
              $job_adjusted_qty = 0;
            }

          }
        } else {
          $job_adjusted_qty = 0;
        }

        $mtlhdr = \DB::table('w_materialissue_hdr_t')->where('w_jobs_hdr_id', $table[0]->w_jobs_hdr_id)->select('w_materialissue_hdr_t.*', 'w_materialissue_hdr_t.job_qty as qty')->where('receive_status', 0)->get();

        if (count($mtlhdr) > 0) {
          if (isset($mtlhdr)) {
            $mtlhdrid = "";
            foreach ($mtlhdr as $k => $v) {
              $mtlhdrid .= $v->w_materialissue_hdr_id . ",";
            }
            $mtlhdrid1 = rtrim($mtlhdrid, ",");

            $mtllines = \DB::table('w_materialissue_line_t')->select('product_id', 'uom_code_id', 'w_materialissue_hdr_id', 'qty', 'issue_qty', 'w_materialissue_line_id', 'subinventory_id', 'locator_id', 'comments', DB::raw('sum(mtl_issue_qty) as mtl_issue_qty'))->whereIn('w_materialissue_hdr_id', explode(",", $mtlhdrid1))->where('receive_status', 0)->groupBy('product_id')->groupBy('batchnumber')->orderBy('w_materialissue_line_id')->get();

          } else {
            $mtllines = array();
          }
        } else {
          if ($table[0]->bom_process == '0' || $table[0]->bom_process == "") {
            $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $table[0]->reference_source_id)->where('parent_product', $table[0]->product_id)->get();
          } else {
            $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $table[0]->reference_source_id)->where('parent_product', $table[0]->product_id)->where('process_level', $table[0]->bom_process)->get();
          }
          $mtllines = $planlines;
        }


        if ($_GET['source'] == "PACKINGMATERIALRECEIVE" && $table[0]->bom_process != "PROCESS-1" && $table[0]->bom_process != "FINALPROCESS") {
          $mtllines = collect($mtllines)->map(function ($x) {
            return (array) $x;
          })->toArray();
          $mtlhdr = collect($mtlhdr)->map(function ($x) {
            return (array) $x;
          })->toArray();

          foreach ($mtlhdr as $key => $value) {
            if ($key != 0) {
              unset($mtlhdr[$key]);
            } else {
              $mtlhdr[$key]['issue_qty'] = 0;
              $mtlhdr[$key]['mtl_issue_qty'] = $job_adjusted_qty;
              $mtlhdr[$key]['w_materialissue_line_id'] = 0;
              $mtlhdr[$key]['comments'] = '';
            }
          }
          //	dd($mtllines);
          $hdrln = array_merge($mtlhdr, $mtllines);
          //dd($hdrln);
          foreach ($hdrln as $key => $value) {
            $hdrln[$key] = (object) $value;
          }
          $mtllines1 = $hdrln;
        }

        if ($table[0]->job_status == 'JOBCARD REWORK') {
          $this->data['linedata'] = array();
          $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', '');

          $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
        } else if ($_GET['source'] == "MATERIALRECEIVE") {
          $this->data['linedata'] = $mtllines;
        } else if ($_GET['source'] == "PACKINGMATERIALRECEIVE" && $table[0]->bom_process == "PROCESS-1") {

          $this->data['linedata'] = $mtllines;
        } else if ($_GET['source'] == "PACKINGMATERIALRECEIVE" && $table[0]->bom_process == "FINALPROCESS") {
          //	dd($table);
          $this->data['linedata'] = $table;
        } else {
          //dd($mtllines1);
          $this->data['linedata'] = $mtllines1;
        }

        $product_group_id = '';
        if ($_GET['source'] == "MATERIALRECEIVE") {
          $product_group_id = "2";
        } else {
          $product_group_id = "1";
        }
        $this->data['productid'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id in(' . $product_group_id . ")");
        //dd($this->data['linedata']);
        if (count($this->data['linedata']) > 0) {

          foreach ($this->data['linedata'] as $key => $value) {
            $productid = $value->product_id;
            $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $productid, 'and product_id=' . $productid);
            $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
            $this->data['linedata'][$key]->w_materialreceive_line_id = "";
            $this->data['linedata'][$key]->comments = '';
            $sql = \DB::select("select * from m_products_t where product_id=" . $productid);
            if (!empty($sql[0]->subinventory_id)) {
              $this->data['linedata'][$key]->subinventory_id = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', $sql[0]->subinventory_id);
              $this->data['linedata'][$key]->locator_id = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', $sql[0]->sublocator_id, 'and subinventory_id=' . $sql[0]->subinventory_id);
            } else {
              $this->data['linedata'][$key]->subinventory_id = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', '');
              $this->data['linedata'][$key]->locator_id = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', '');
            }
            $decimal = \Session::get('decimal');
            if ($_GET['source'] == "MATERIALRECEIVE") {
              $this->data['linedata'][$key]->mtl_issue_qty = number_format($value->mtl_issue_qty, $decimal, ".", "");
              $this->data['linedata'][$key]->qty = $value->qty;
              $this->data['linedata'][$key]->issue_qty = number_format($value->issue_qty, $decimal, ".", "");
              $this->data['linedata'][$key]->receive_qty = number_format($value->mtl_issue_qty, $decimal, ".", "");
              $this->data['linedata'][$key]->reference_source_hdr_id = $value->w_materialissue_hdr_id;
              $this->data['linedata'][$key]->reference_source_line_id = $value->w_materialissue_line_id;
              $this->data['linedata'][$key]->reference_source = 'PACKINGMATERIALISSUE';
            } else if ($_GET['source'] == "PACKINGMATERIALRECEIVE" && $table[0]->bom_process == "PROCESS-1") {
              if (isset($value->mtl_issue_qty)) {
                $this->data['linedata'][$key]->mtl_issue_qty = number_format($value->mtl_issue_qty, $decimal, ".", "");
                $this->data['linedata'][$key]->qty = $value->qty;
                $this->data['linedata'][$key]->issue_qty = number_format($value->issue_qty, $decimal, ".", "");
                $this->data['linedata'][$key]->receive_qty = number_format($value->mtl_issue_qty, $decimal, ".", "");
                $this->data['linedata'][$key]->reference_source_hdr_id = $value->w_materialissue_hdr_id;
                $this->data['linedata'][$key]->reference_source_line_id = $value->w_materialissue_line_id;
                $this->data['linedata'][$key]->reference_source = 'PACKINGMATERIALISSUE';
              } else {
                $this->data['linedata'][$key]->mtl_issue_qty = 0;
                $this->data['linedata'][$key]->qty = $value->component_qty;
                $this->data['linedata'][$key]->issue_qty = number_format(($value->component_qty) * ($this->data['job_qty']), $decimal, '.', '');
                $this->data['linedata'][$key]->receive_qty = number_format(($value->component_qty) * ($this->data['job_qty']), $decimal, '.', '');
                $this->data['linedata'][$key]->reference_source_hdr_id = $value->productionplan_hdr_id;
                $this->data['linedata'][$key]->reference_source_line_id = 0;
                $this->data['linedata'][$key]->reference_source = 'PACKINGMATERIALRECEIVE';
              }
            } else if ($key == 0 && $table[0]->bom_process != "PROCESS-1" && $table[0]->bom_process != "FINALPROCESS") {
              if (isset($value->mtl_issue_qty)) {
                $this->data['linedata'][$key]->mtl_issue_qty = $value->mtl_issue_qty;
                $this->data['linedata'][$key]->receive_qty = $value->mtl_issue_qty;
                $this->data['linedata'][$key]->reference_source_hdr_id = $value->w_materialissue_hdr_id;
                $this->data['linedata'][$key]->reference_source_line_id = $value->w_materialissue_line_id;
                $this->data['linedata'][$key]->reference_source = 'PACKINGMATERIALRECEIVE';
              } else {
                $this->data['linedata'][$key]->qty = $value->component_qty;
                $this->data['linedata'][$key]->issue_qty = number_format(($value->component_qty) * ($this->data['job_qty']), $decimal, '.', '');
                $this->data['linedata'][$key]->receive_qty = number_format(($value->component_qty) * ($this->data['job_qty']), $decimal, '.', '');
                $this->data['linedata'][$key]->reference_source_hdr_id = $value->productionplan_hdr_id;
                $this->data['linedata'][$key]->reference_source_line_id = 0;
                $this->data['linedata'][$key]->reference_source = 'PACKINGMATERIALRECEIVE';
              }
              $planlines = \DB::table('w_productionplan_lines_t')->where('parent_product', $table[0]->product_id)->where('product_id', $productid)->where('productionplan_hdr_id', $table[0]->reference_source_id)->get();
              if (count($planlines) > 0) {

                $this->data['linedata'][$key]->process_level = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $planlines[0]->process_level, 'and lookup_type="PROCESS_LEVEL"');
                $this->data['linedata'][$key]->process_name = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $planlines[0]->process_name, 'and lookup_type="PROCESS_NAME"');
              }
              $this->data['linedata'][$key]->mtl_issue_qty = 0;
              if ($productid == $table[0]->product_id) {
                unset($this->data['linedata'][$key]);
              }

            } else if ($key != 0 && $table[0]->bom_process != "PROCESS-1" && $table[0]->bom_process != "FINALPROCESS") {
              if (isset($value->mtl_issue_qty)) {
                $this->data['linedata'][$key]->mtl_issue_qty = number_format($value->mtl_issue_qty, $decimal, ".", "");
                $this->data['linedata'][$key]->qty = $value->qty;
                $this->data['linedata'][$key]->issue_qty = number_format($value->issue_qty, $decimal, ".", "");
                $this->data['linedata'][$key]->receive_qty = number_format($value->mtl_issue_qty, $decimal, ".", "");
                $this->data['linedata'][$key]->reference_source_hdr_id = $value->w_materialissue_hdr_id;
                $this->data['linedata'][$key]->reference_source_line_id = $value->w_materialissue_line_id;
                $this->data['linedata'][$key]->reference_source = 'PACKINGMATERIALISSUE';
              } else {
                $this->data['linedata'][$key]->mtl_issue_qty = 0;
                $this->data['linedata'][$key]->qty = 0;
                $this->data['linedata'][$key]->issue_qty = 0;
                $this->data['linedata'][$key]->receive_qty = number_format(($value->component_qty) * ($this->data['job_qty']), $decimal, '.', '');
                $this->data['linedata'][$key]->reference_source_hdr_id = $value->productionplan_hdr_id;
                $this->data['linedata'][$key]->reference_source_line_id = 0;
                $this->data['linedata'][$key]->reference_source = 'PACKINGMATERIALRECEIVE';
              }
              $planlines = \DB::table('w_productionplan_lines_t')->where('parent_product', $table[0]->product_id)->where('product_id', $productid)->where('productionplan_hdr_id', $table[0]->reference_source_id)->get();
              if (count($planlines) > 0) {

                $this->data['linedata'][$key]->process_level = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $planlines[0]->process_level, 'and lookup_type="PROCESS_LEVEL"');
                $this->data['linedata'][$key]->process_name = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $planlines[0]->process_name, 'and lookup_type="PROCESS_NAME"');
              } else {
                $bomprocess = \DB::table('m_material_bom_hdr_t')->leftjoin('m_material_bom_lines_t', 'm_material_bom_lines_t.material_bom_hdr_id', '=', 'm_material_bom_hdr_t.material_bom_hdr_id')->select('m_material_bom_lines_t.process_level', 'm_material_bom_lines_t.process_name')->where('m_material_bom_hdr_t.active', 'Yes')->where('m_material_bom_hdr_t.assembly_product_id', $table[0]->product_id)->groupBy('m_material_bom_lines_t.process_level')->orderBy('m_material_bom_lines_t.material_bom_line_id', 'asc')->get();
                $prcs = "";

                foreach ($bomprocess as $k => $v) {
                  $prcs .= "'" . $v->process_level . "',";
                }
                $prcs1 = trim($prcs, ",");
                $this->data['linedata'][$key]->process_level = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', '', 'and lookup_type="PROCESS_LEVEL" and lookup_code in(' . $prcs1 . ')');
                $this->data['linedata'][$key]->process_name = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', '', 'and lookup_type="PROCESS_NAME"');
              }
              if ($productid == $table[0]->product_id) {
                unset($this->data['linedata'][$key]);
              }
            } else if ($key == 0 && $table[0]->bom_process == "FINALPROCESS") {
              $this->data['linedata'][$key]->receive_qty = $value->job_adjusted_qty;
              $this->data['linedata'][$key]->mtl_issue_qty = 0;
              $this->data['linedata'][$key]->qty = 0;
              $this->data['linedata'][$key]->issue_qty = 0;
              $this->data['linedata'][$key]->reference_source_hdr_id = $value->w_jobs_hdr_id;
              $this->data['linedata'][$key]->reference_source_line_id = "0";
              $this->data['linedata'][$key]->reference_source = 'PACKING JOBCARD';
            }

            if ($_GET['source'] == "MATERIALRECEIVE") {
              $this->data['linedata'][$key]->reference_source = 'MATERIAL ISSUE';
            }

          }
        }

      }
    }
	  
    return view("materialreceive.form", $this->data);
  }


  public function save(Request $request)
  {
	  
		   \DB::beginTransaction();
		$product_group = \DB::table('m_products_t')->where('product_id', $_POST['product_id'])->get();

		/* Accounts Entry Start :Isac Naveen*/
		$products = \DB::table('m_products_t')->whereIn('product_id', $_POST['bulk_product_id'])->get();
		$collection = collect($products);
		$production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();

		$date = date('Y-m-d');
		$org = \Session::get('organization');
		$loc = \Session::get('location');
		$compy = \Session::get('companyid');
		$jobid = $_POST['w_jobs_hdr_id'];
		$job_no = \DB::select("SELECT `job_no` FROM `w_jobcard_hdr_t` WHERE `w_jobs_hdr_id`='$jobid'");
		$job_no = $job_no[0]->job_no;
		$journal_name = "Material Receive-" . $job_no;

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
			'process',
			'job_status',
			'qohcnt',
		]);
		// Normalize "bulk_" keys once
		$form = $this->normalizeLineFormKeys($form);

		// Build header + lines
		$data = $this->validatePost($form, $this->table, 'header');
		$lines_data = $this->validatePost($form, $this->subtable, 'lines');


		$data['mtl_receive_date'] = date("Y-m-d H:i:s", strtotime($_POST['mtl_receive_date']));
    $data['product_id'] = $_POST['product_id'];
    $data['uom_code_id'] = $_POST['uom_code_id'];
		unset($lines_data['removed_line_id']);
		unset($data['removed_line_id']);

		try {

			$id = $this->model->insertRow($data);
			/*deepika purpose:audit log*/
			$this->auditlog($id, "materialreceive", 'create', $data, "w_materialreceive_hdr_t");
			/*end*/
			$lid = $this->submodel->subgridSave($lines_data, $id);

			DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $_POST['w_jobs_hdr_id'])->update(['job_status' => 'MATERIAL RECEIVED']);
			DB::table('notifications_t')->where('reference_source_id', $_POST['w_jobs_hdr_id'])->where('reference_source', '=', '	
		MATERIALRECEIVE')->update(['read/unread' => 'read']);

			$job = \DB::select('select * from w_jobcard_hdr_t where w_jobs_hdr_id=' . $_POST['w_jobs_hdr_id']);
			$jobno = $job[0]->job_no;
			$job_qty = $job[0]->job_qty;
			$mtlhdr = \DB::table('w_materialissue_hdr_t')->where('w_jobs_hdr_id', $_POST['w_jobs_hdr_id'])->get();
			if (isset($mtlhdr)) {
				$mtlhdrid = "";
				foreach ($mtlhdr as $k => $v) {
					$mtlhdrid .= $v->w_materialissue_hdr_id . ",";
				}
				$mtlhdrid1 = rtrim($mtlhdrid, ",");
			}
			/*deepika purpose: to insert mtltrx & qoh*/
			$SQL = "SELECT * FROM w_materialreceive_line_t where w_materialreceive_hdr_id = '$id'";
			$result = \DB::select($SQL);

			$trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'MATERIAL RECEIVE')->get();
			$trsns = json_decode(json_encode($trsns), true);
			if ($_POST['bulk_reference_source'][0] == "PACKING JOBCARD") {
				\DB::table('i_reservation_detail_t')->where('reference_no', $job[0]->job_no)->delete();
			}

			foreach ($result as $key => $value) {
				$group = \DB::select("select product_group_id from m_products_t where product_id='" . $value->product_id . "'");
				if (count($group) > 0) {
					$prdgroup = $group[0]->product_group_id;
				} else {
					$prdgroup = "";
				}
				if ($value->reference_source_line_id != "" && $value->reference_source_line_id != "0") {
					$mtlln = \DB::Select('select * from w_materialissue_line_t where product_id=' . $value->product_id . ' and w_materialissue_hdr_id in(' . $mtlhdrid1 . ')');
					foreach ($mtlln as $k => $v) {
						DB::table('w_materialissue_line_t')->where('product_id', $v->product_id)->where('w_materialissue_hdr_id', $v->w_materialissue_hdr_id)->update(['receive_qty' => $v->issueqty, 'receive_status' => 1]);
					}

				}
				$mtlid ="0";
				$subinv = explode(",", $value->subinventory_id);
				$locid = explode(",", $value->locator_id);
				$issqty = explode(",", $value->issueqty);
				$receiveqty = explode(",", $value->receiveqty);
				$batchnumber = explode(",", $value->batchnumber);
				/* insert data into mtl transaction tbl */
				$data1['trx_source_type_id'] = $trsns[0]['transaction_source_id'];
				$data1['trx_action_id'] = $trsns[0]['transaction_action_id'];
				$data1['trx_type_id'] = $trsns[0]['transaction_type_id'];

				$data1['trx_source_hdr_id'] = $id;
				$data1['trx_source_line_id'] = $value->w_materialreceive_line_id;

				$data1['line_number'] = $value->line_no;
				$data1['product_id'] = $value->product_id;
				$data1['trx_uom'] = $value->uom_code_id;
				$data1['trx_date'] = date('Y-m-d');
				$data1['created_by'] = \Session::get('id');
				$data1['created_at'] = date('Y-m-d');
				$data1['organization_id'] = \Session::get('organization');
				$data1['location_id'] = \Session::get('location');
				$data1['company_id'] = \Session::get('companyid');
				/* insert data into qoh detail tbl */

				$dataqoh['product_id'] = $value->product_id;
				$dataqoh['qoh_uom_code_id'] = $value->uom_code_id;
				$dataqoh['created_at'] = date('Y-m-d H:i:s');
				$dataqoh['created_by'] = \Session::get('id');
				$dataqoh['qoh_source'] = "MATERIAL RECEIVE";
				$dataqoh['qoh_trx_date'] = date('Y-m-d');
				$dataqoh['qoh_source_id'] = $id;
				$dataqoh['organization_id'] = \Session::get('organization');
				$dataqoh['location_id'] = \Session::get('location');
				$dataqoh['company_id'] = \Session::get('companyid');

				foreach ($subinv as $k => $v) {
					$data1['subinventory_id'] = $v;
					$data1['locator_id'] = $locid[$k];
					if ($issqty[$k] != 0 && $value->reference_source != 'PACKING JOBCARD') {
						$trxqty = $issqty[$k] - $receiveqty[$k];
					} else {
						$trxqty = -$receiveqty[$k];
					}
					$data1['trx_qty'] = $trxqty;

					if ($data1['trx_qty'] != 0) {
						$mtlid = \DB::table('m_material_trx_t')->insertGetId($data1);

						$data1['trx_qty'] = -$job_qty;
						$mtlid1 = \DB::table('m_material_trx_t')->insertGetId($data1);
					}

					if ($issqty[$k] != 0 && $value->reference_source != 'PACKING JOBCARD') {
						$trxqty1 = $issqty[$k] - $receiveqty[$k];
					} else {
						$trxqty1 = -$receiveqty[$k];
					}
					$dataqoh['qoh_trx_qty'] = $trxqty1;
					$dataqoh['subinventory_id'] = $v;
					$dataqoh['create_trx_id'] = $mtlid;
					$dataqoh['locator_id'] = $locid[$k];
					$dataqoh['batch_number'] = $batchnumber[$k];
					$dataqoh['job_id'] = $_POST['w_jobs_hdr_id'];
					if ($trxqty1 != 0) {
						$qohid = \DB::table('i_qoh_detail_t')->insertGetId($dataqoh);
					}

				}

			}
			/*deepika purpose:update receive status based on lines receive status*/
			if ($_POST['bulk_reference_source'][0] != "PACKING JOBCARD" && $_POST['bulk_reference_source'][0] != "PACKINGMATERIALRECEIVE") {
				$mtlln = \DB::Select('select * from w_materialissue_line_t where w_materialissue_hdr_id in(' . $mtlhdrid1 . ')');

				$mtllnrcv = \DB::Select('select * from w_materialissue_line_t where receive_status=1 and w_materialissue_hdr_id in(' . $mtlhdrid1 . ')');
				$mtlln1 = count($mtlln);
				$mtllnrcv1 = count($mtllnrcv);
				if ($mtlln1 == $mtllnrcv1) {
					$mtlhdr = \DB::table('w_materialissue_hdr_t')->whereIn('w_materialissue_hdr_id', explode(",", $mtlhdrid1))->update(['receive_status' => '1']);
				}
			}
			/*end*/
			$compid = \Session::get('companyid');

			\DB::commit();

			return response()->json(array('status' => 'success', 'message' => 'Material Received Successfully', 'id' => $id, 'lid' => $lid));
		} catch (\Illuminate\Database\QueryException $e) {
			$message = explode('(', $e->getMessage());
			$dbCode = rtrim($message[0], ']');
			$dbCode = trim($dbCode, '[');
			dd($dbCode);
			\DB::rollback();
			return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
		}

  }

  /* purpose: subinventory receive details based on material issues*/
  public function prdsubinventoryreceivedetails($index1 = null, $pid = null)
  {
    $html = "";
    $product = $pid;
    $index = $index1;
    if (isset($_GET)) {

      $jobid = $_GET['jobid'];
      if ($_GET['src'] == 'mtlissue') {
        $mtlline = $_GET['refsrcline'];
        $this->data['mtl_receive_date'] = date("d-m-Y H:i:s");
        $mtlhdr = \DB::table('w_materialissue_hdr_t')->where('w_jobs_hdr_id', $jobid)->get();
        if (isset($mtlhdr)) {
          $mtlhdrid = "";
          foreach ($mtlhdr as $k => $v) {
            $mtlhdrid .= $v->w_materialissue_hdr_id . ",";
          }
          $mtlhdrid1 = rtrim($mtlhdrid, ",");
        }

        $group = \DB::table('m_products_t')->select('m_products_t.*', 'm_product_groups_t.*')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('m_products_t.product_id', $product)->get();
        if ($group[0]->group_name != "FINISHED GOODS") {

          $sql1 = \DB::Select("select product_id,w_materialissue_hdr_id,subinventory_id,locator_id,issueqty,batchnumber from w_materialissue_line_t where product_id=" . $product . " and  w_materialissue_hdr_id in(" . $mtlhdrid1 . ")");

        } else {
          $table = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $jobid)->get();
          $job_hdr_id = $jobid;
          if (($table[0]->bom_process != "") && ($table[0]->bom_process != '0')) {
            if ($table[0]->bom_process == "PROCESS-2") {
              $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-1' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");
            } else if ($table[0]->bom_process == "PROCESS-3") {
              $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-2' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");
            } else if ($table[0]->bom_process == "PROCESS-4") {
              $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-3' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");
            } else if ($table[0]->bom_process == "FINALPROCESS") {
              $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-4' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");
            } else if ($jobprs[0]->w_jobs_hdr_id == null) {
              $job = \DB::select("select * from w_jobcard_hdr_t where product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " group by bom_process order by w_jobs_hdr_id desc");
              $jobprs = \DB::select("select * from w_jobcard_hdr_t where bom_process='" . $job[0]->bom_process . "' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");

            } else {
              $jobprs = array();
              $job_hdr_id = 0;

            }
            if (count($jobprs) > 0) {
              $job_hdr_id = $jobprs[0]->w_jobs_hdr_id;
            } else {
              $job_hdr_id = 0;
            }

          }

          $sql1 = \DB::table('w_qa_submitstage_trx_t')->where('job_no', $job_hdr_id)->where('product_id', $product)->select('job_no', 'batch_no as batchnumber', 'product_id', 'production_qty as issueqty', 'subinventory_id', 'sublocator_id as locator_id')->get();

          if (count($sql1) > 0) {
            $sql1 = \DB::table('w_qa_submitstage_trx_t')->where('job_no', $job_hdr_id)->where('product_id', $product)->select('job_no', 'batch_no as batchnumber', 'product_id', 'production_qty as issueqty', 'subinventory_id', 'sublocator_id as locator_id')->get();
          } else {

            $jobid = $_GET['jobid'];
            $mtlhdr = \DB::table('w_materialissue_hdr_t')->where('w_jobs_hdr_id', $jobid)->get();
            if (isset($mtlhdr)) {
              $mtlhdrid = "";
              foreach ($mtlhdr as $k => $v) {
                $mtlhdrid .= $v->w_materialissue_hdr_id . ",";
              }
              $mtlhdrid1 = rtrim($mtlhdrid, ",");
            }

            $sql1 = \DB::Select("select product_id,w_materialissue_hdr_id,subinventory_id,locator_id,issueqty,batchnumber from w_materialissue_line_t where product_id=" . $product . " and  w_materialissue_hdr_id in(" . $mtlhdrid1 . ")");
          }
        }

        $bno = array();
        $batch_data = array();
        $issueqty = array();
        foreach ($sql1 as $key => $value) {

          $bno = explode(",", $value->batchnumber);
          $issueqty = explode(",", $value->issueqty);
          foreach ($bno as $k => $v) {
            if (isset($batch_data[$v])) {
              $batch_data[$v] = $batch_data[$v] + $issueqty[$k];

            } else {
              $batch_data[$v] = $issueqty[$k];
            }

          }

        }
        $batch_data1 = array();
        foreach ($batch_data as $k => $v) {
          $batch_data1[] = $v;
        }
        $subinv = explode(",", $sql1[0]->subinventory_id);
        $subloc = explode(",", $sql1[0]->locator_id);
        $issqty = explode(",", $sql1[0]->issueqty);
        $bno = explode(",", $sql1[0]->batchnumber);
        //dd($sql1);
        $html .= "<table class='table table-bordered clone_table1'>";
        $html .= "<thead class='table-light'><th >S.No</th><th >Lot Number</th><th style='display:none;'>Subinventory</th><th style='display:none;'>Locator</th><th style='width:150px;display:none;'>Qoh</th><th style=''>Qty</th><th style=''>Receive Qty</th><th></th></thead><tbody  class='clone_lines_body1'>";
        $subid = "";
        if (!empty($subinv)) {
          foreach ($subinv as $k => $v) {



            $sub = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', $v);
            $loc = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', $subloc[$k]);
            $batch = $this->jcustomselectcomp('i_qoh_detail_t', 'batch_number', 'batch_number', $bno[$k], 'and product_id=' . $product . ' and qoh_trx_qty>0 and batch_number!=""');
            $html .= "<tr class='subinv_clone rcopy1'>";
            $html .= "<td style='padding:2px'><input type='hidden' class='product' value=" . $product . " ><input type='hidden' class='index1' value=" . $index . " ><input type='text' class='form-control line_no' value='1'></td>
		<td class='batchno' style='padding:2px;pointer-events:none;'><select class='form-control batch_number'>" . $batch . "</select></td>";
            $html .= "<td style='padding:2px;pointer-events:none;display:none;'>
    	<select class='form-control subinventory_id' >" . $sub . "</select>
			
		</td>";
            $html .= "<td style='padding:2px;pointer-events:none;display:none;'>
	    <select class='form-control locator_id' >" . $loc . "</select>
			
			</td>";
            $html .= "<td style='padding:2px;display:none;'><input type='hidden' class='form-control qoh' value='' readonly></td>";

            $html .= "<td style='padding:2px;pointer-events:none;' class='mtlissqty'><input type='text' class='form-control mtlqty' value=" . $batch_data1[$k] . ">
				</td> 
				<td style='padding:2px;' class='mtlrcvqty'><input type='text' class='form-control receiveqty' value=" . $batch_data1[$k] . "></td>";

            $html .= "</tr>";


          }
        }
        $html .= "</tbody></table>";
      } else {
        $product = $product;
        $grp = $this->groupname($product);
        $comp = \Session::get('companyid');
        $sql = array();
        if ($grp != 0 || $grp != "") {
          if ($grp == 'SEMI FINISHED GOODS') {
            $sql = \DB::select("select * from(select *,round(sum(qoh_trx_qty),4) as qoh   from i_qoh_detail_t where product_id=" . $product . " and batch_number!='' and qualitystatus=1 and company_id=" . $comp . " group by batch_number ORDER BY `qoh_detail_id` desc) as f where f.qoh>0");

          } else if ($grp == 'RAW MATERIALS' || $grp == 'PACKING MATERIALS' || $grp == 'FINISHED GOODS') {

            $sql = \DB::select("select * from(select *,round(sum(qoh_trx_qty),4) as qoh   from i_qoh_detail_t where product_id=" . $product . " and batch_number!=''  and company_id=" . $comp . " group by batch_number ORDER BY `qoh_detail_id` desc) as f where f.qoh>0");

          }
        }


        if (count($sql) > 0) {

          $html .= "<table class='table table-bordered clone_table1'>";
          $html .= "<thead class='table-light'><th >S.No</th><th >Batch Number</th><th >Subinventory</th><th >Locator</th><th style='width:150px;'>Qoh</th><th >Receive Qty</th><th></th></thead><tbody  class='clone_lines_body1'>";
          $subid = "";
          $batchno = "";
          if (!empty($sql)) {
            foreach ($sql as $k => $v) {
              $subid .= $v->subinventory_id . ",";
              $batchno .= "'" . $v->batch_number . "',";
            }
          }
          $subinv_id = rtrim($subid, ",");
          $batchno1 = rtrim($batchno, ",");
          $str = implode(',', array_unique(explode(',', $subinv_id)));
          $bno1 = implode(',', array_unique(explode(',', $batchno1)));
          $sub = $this->jcustomselect('m_subinventory_t', 'subinventory_id', 'subinventory_name', '', 'and subinventory_id in(' . $str . ')');
          $loc = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', '', ' and subinventory_id=5');
          if ($_GET['src1'] == "PACKINGMATERIALRECEIVE" && $_GET['src'] == "mtlreceive") {
            $batch = $this->jcustomselectcomp1('i_qoh_detail_t', 'batch_number', 'batch_number', '', 'and product_id=' . $product . ' and batch_number in(' . $bno1 . ')  and subinventory_id=5', 'batch_number,subinventory_id');
          } else {
            $batch = $this->jcustomselectcomp1('i_qoh_detail_t', 'batch_number', 'batch_number', '', 'and product_id=' . $product . ' and batch_number in(' . $bno1 . ')  and subinventory_id=5', 'batch_number,subinventory_id');
          }
          $html .= "<tr class='subinv_clone rcopy1'>";
          $html .= "<td style='padding:2px'><input type='hidden' class='product' value=" . $product . " ><input type='hidden' class='index1' value=" . $index . " ><input type='text' class='form-control line_no' value='1' ></td>
				<td class='batchno'><select class='form-control batch_number'>" . $batch . "</select></td>
				
				";
          $html .= "<td>
	<select class='form-control subinventory_id' >" . $sub . "</select>
			
			</td>";
          $html .= "<td style='padding:2px;'>
	<select class='form-control locator_id'>" . $loc . "</select>
			
			</td>";
          $html .= "<td style='padding:2px'><input style='width:100px;' type='text' class='form-control qoh' value='' readonly></td>";
          $html .= "<td style='padding:2px' class='mtlrcvqty'><input type='text' class='form-control receiveqty' value='' ></td>                   <td class='text-center'>
                    <button type='button' class='btn btn-sm btn-danger remove-row'>
                      <i class='fas fa-minus-circle'></i>
                    </button>
                  </td>";
          $html .= "</tr>";
          $html .= "</tbody></table>";
        } else {
          $html .= "No QOH Available";
        }

      }

    }
    return $html;
  }

  public function getmaterialreceiveData()
  {
    $wh = '';

    $wh .= " and w_jobcard_hdr_t.qasubmit_status='0'";

    $wh .= $grid_data = $this->grid_statuscheck('w_jobcard_hdr_t', 'job_date', 'qasubmit_status', '=', 0);


    if (isset($_GET)) {
      if ($_GET['type'] == 'fg') {
        $wh .= " and m_product_groups_t.group_name='FINISHED GOODS' and (w_jobcard_hdr_t.job_status='OPEN'  or w_jobcard_hdr_t.job_status='MATERIAL ISSUED')";
      } else {
        $wh .= " and m_product_groups_t.group_name='SEMI FINISHED GOODS' and w_jobcard_hdr_t.job_status='MATERIAL ISSUED'";
      }
    }

    $sql = "SELECT
    w_jobcard_hdr_t.w_jobs_hdr_id,
    w_jobcard_hdr_t.job_no,
    w_jobcard_hdr_t.job_date,
    w_jobcard_hdr_t.job_adjusted_qty,
    w_jobcard_hdr_t.batch_no,
    w_jobcard_hdr_t.job_status,
    m_products_t.concatenated_product,
    m_products_t.product_code,
    m_uom_codes_t.uom_code,
	w_materialissue_hdr_t.receive_status,
	w_materialissue_hdr_t.w_materialissue_hdr_id,
	w_productionplan_hdr_t.plan_no,
	w_jobcard_hdr_t.job_process
FROM w_jobcard_hdr_t 
LEFT JOIN m_products_t ON (m_products_t.product_id = w_jobcard_hdr_t.product_id)
LEFT JOIN m_product_groups_t ON(m_products_t.product_group_id= m_product_groups_t.product_group_id)
LEFT JOIN m_uom_codes_t ON (m_uom_codes_t.uom_code_id = w_jobcard_hdr_t.uom_code_id) 
LEFT JOIN w_materialissue_hdr_t ON (w_materialissue_hdr_t.w_jobs_hdr_id = w_jobcard_hdr_t.w_jobs_hdr_id) 
LEFT JOIN w_materialissue_line_t ON (w_materialissue_hdr_t.w_materialissue_hdr_id = w_materialissue_line_t.w_materialissue_hdr_id) 
LEFT JOIN w_productionplan_hdr_t ON (w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id) 
	where 1=1  $wh  group by w_jobcard_hdr_t.w_jobs_hdr_id ORDER BY w_jobcard_hdr_t.w_jobs_hdr_id DESC";


    $result = \DB::select($sql);

    return DataTables::of($result)->make(true);

  }

  public function groupname($id = null)
  {

    $group = \DB::table('m_products_t')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('m_products_t.product_id', $id)->get();
    if (count($group) > 0) {
      $group_id = $group[0]->group_name;
      return $group_id;
    } else {
      return 0;
    }

  }
  public function getprcsdetails($pid = null, $fgpid = null)
  {
    $bomprocess = \DB::table('m_material_bom_hdr_t')->leftjoin('m_material_bom_lines_t', 'm_material_bom_lines_t.material_bom_hdr_id', '=', 'm_material_bom_hdr_t.material_bom_hdr_id')->select('m_material_bom_lines_t.process_level', 'm_material_bom_lines_t.process_name')->where('m_material_bom_hdr_t.active', 'Yes')->where('m_material_bom_hdr_t.assembly_product_id', $fgpid)->where('m_material_bom_lines_t.component_product_id', $pid)->get();
    $data = array();
    if (count($bomprocess) > 0) {
      $data['process_level'] = $bomprocess[0]->process_level;
      $data['process_name'] = $bomprocess[0]->process_name;
      return $data;
    } else {
      return 0;
    }

  }
}

