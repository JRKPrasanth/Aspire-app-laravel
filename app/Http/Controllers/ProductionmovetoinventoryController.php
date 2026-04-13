<?php

namespace App\Http\Controllers;
use App\Productionmovetoinventory;
use App\Qualitycheck;
use Illuminate\Http\Request;
use yajra\datatables\datatables;
use DateTime;
use DB;
use Session;

class ProductionmovetoinventoryController extends Controller
{
	public function __construct()
	{
		$this->data = array(
			'pageModule' => 'promovetoinventory',
			'pageUrl' => url('promovetoinventory')
		);
		$this->data['urlmenu'] = $this->indexs();
		$this->data['pageMethod'] = \Request::route()->getName();
		$this->qamodel = new Qualitycheck();
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

		$companyid = \Session::get('companyid');
		$company = \DB::select('select * from m_company_t where company_id=' . $companyid);
		$this->data['company_code'] = $company_code = $company[0]->company_code;
		$this->data['pageMethod'];
		return view("productionmovetoinventory.table", $this->data);
	}

	public function create($id = null)
	{

		if ($_GET['status'] == "movetoinventoryqa") {
			$this->data['status'] = $_GET['status'];

			$linedatas = \DB::select("select * from w_qa_submitstage_line_t where qa_submitstage_trx_hdr_id='$id'");
			foreach ($linedatas as $key => $value) {
				if ($value->return_qty != 0 && $value->subinventory_id == "" && $value->sublocator_id == "") {
					$hdrdatas = \DB::select("SELECT w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id,w_qa_submitstage_trx_t.job_no,0 as qa_submitstage_trx_line_id, w_qa_submitstage_trx_t.product_id, w_qa_submitstage_trx_t.uom_code_id,w_qa_submitstage_trx_t.reference_no,w_qa_submitstage_trx_t.batch_no,w_qa_submitstage_trx_t.production_qty,0 as scrap_qty,w_qa_submitstage_trx_t.subinventory_id, w_qa_submitstage_trx_t.sublocator_id FROM w_qa_submitstage_trx_t WHERE w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id = '$value->qa_submitstage_trx_hdr_id'
				 UNION ALL select w_qa_submitstage_line_t.qa_submitstage_trx_line_id,0 as job_no,w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id,w_qa_submitstage_line_t.product_id, w_qa_submitstage_line_t.uom_code_id,0 as reference_no,0 as batch_no,w_qa_submitstage_line_t.return_qty,w_qa_submitstage_line_t.scrap_qty,w_qa_submitstage_line_t.subinventory_id AS line_subinventory_id, w_qa_submitstage_line_t.sublocator_id AS line_sublocator_id FROM w_qa_submitstage_line_t WHERE w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id = '$value->qa_submitstage_trx_hdr_id' AND w_qa_submitstage_line_t.return_qty != 0");
					//dd($hdrdatas);
				} else {
					$hdrdatas = \DB::select("select * from w_qa_submitstage_trx_t  where qa_submitstage_trx_hdr_id='$id'");

				}
			}

			$this->data['row'] = $job = \DB::select("select * from w_jobcard_hdr_t where w_jobs_hdr_id='" . $hdrdatas[0]->job_no . "'");
			$qc = \DB::select("select * from i_quality_spec_trx_hdr_t where qa_submitstage_trx_hdr_id='" . $id . "'");



			if (!empty($qc)) {
				$this->data['reference_no'] = $qc[0]->reference_no;
				$this->data['quality_spec_trx_hdr_id'] = $qc[0]->quality_spec_trx_hdr_id;
				if ($qc[0]->qc_type == "batchwise") {
					$this->data['accept_qty'] = $qc[0]->production_qty;
				} else {
					$this->data['accept_qty'] = $qc[0]->accepted_qty;
				}
			} else {
				$this->data['reference_no'] = $hdrdatas[0]->reference_no;
				$this->data['accept_qty'] = $hdrdatas[0]->production_qty;
				$this->data['quality_spec_trx_hdr_id'] = "";

			}

			$prddata = \DB::select("select * from m_products_t where product_id='" . $hdrdatas[0]->product_id . "'");


			foreach ($hdrdatas as $k => $v) {
				$this->data['lines_data'][$k] = (object) array();

				$this->data['lines_data'][$k]->plan_no = $hdrdatas[0]->batch_no;
				$this->data['lines_data'][$k]->qa_submitstage_trx_hdr_id = $v->qa_submitstage_trx_hdr_id;
				$this->data['lines_data'][$k]->qa_submitstage_trx_line_id = "";
				$qc1 = \DB::select("select * from i_quality_spec_trx_hdr_t where qa_submitstage_trx_hdr_id='" . $id . "' and product_id='" . $v->product_id . "'");
				if (!empty($qc1)) {
					$this->data['lines_data'][$k]->rejection_type = $qc1[0]->rejection_type;
				} else {
					$this->data['lines_data'][$k]->rejection_type = "";
				}
				$this->data['lines_data'][$k]->product_id = $this->jCombologin('m_products_t', 'product_id', 'product_code|concatenated_product', $v->product_id);
				$prddata = \DB::select("select m_products_t.subinventory_id,m_products_t.sublocator_id,m_products_t.product_id,m_product_groups_t.group_name,m_products_t.expiry_days from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where m_products_t.product_id='" . $v->product_id . "'");
				if ($prddata[0]->group_name == 'FINISHED GOODS' || $prddata[0]->group_name == 'SEMI FINISHED GOODS') {
					if ($prddata[0]->expiry_days != "") {
						$curdate = date('Y-m-01');
						$curdate = new DateTime($curdate);
						$curdate->modify('+' . $prddata[0]->expiry_days . ' day');
						$sesdate = \Session::get('p_date_format');
						$this->data['expiry_date'] = date($sesdate, strtotime($curdate->format('Y-m-d')));
						$this->data['manufacturer_date'] = "";
						if (count($job) > 0) {
							$jobdata = \DB::select('select * from w_jobcard_hdr_t where reference_source_id=' . $job[0]->reference_source_id . ' and product_id=' . $job[0]->product_id . ' and bom_process="PROCESS-1"');
							if (count($jobdata) > 0) {
								$qoh = \DB::select("select * from i_qoh_detail_t where product_id=" . $jobdata[0]->bom_product_id . " and batch_number='" . $jobdata[0]->batch_no . "' and qoh_source='PRODUCTION STORE MOVE' ORDER BY `qoh_detail_id` DESC");
								if (count($qoh) > 0) {
									if ($qoh[0]->manufacturer_date != "") {
										$this->data['manufacturer_date'] = $qoh[0]->manufacturer_date;
									}
								}
							}
						} else {
							$this->data['expiry_date'] = "";

						}
					} else {
						$this->data['expiry_date'] = "";
						$this->data['manufacturer_date'] = "";
					}
					//dd($v->uom_code_id);
					$this->data['lines_data'][$k]->uom_code_id = $this->jCombologin('m_uom_codes_t', 'uom_code_id', 'uom_code', $v->uom_code_id);
					//dd($this->data['lines_data'][$k]->uom_code_id);
					$this->data['lines_data'][$k]->qty = $v->production_qty;

					if (isset($prddata[0]->sublocator_id)) {
						if ($prddata[0]->sublocator_id == null) {
							$sublocator_id = "";

							$this->data['lines_data'][$k]->subinventory_id = $this->jCombologin('m_subinventory_t', 'subinventory_id', 'subinventory_name', '');
							$this->data['lines_data'][$k]->sublocator_id = $this->jcustomselecttool('m_sublocators_t', 'sublocator_id', 'locator_code', $sublocator_id, '');
						} else {
							$sublocator_id = $prddata[0]->sublocator_id;
							$this->data['lines_data'][$k]->subinventory_id = $this->jCombologin('m_subinventory_t', 'subinventory_id', 'subinventory_name', $prddata[0]->subinventory_id);
							$this->data['lines_data'][$k]->sublocator_id = $this->jcustomselecttool('m_sublocators_t', 'sublocator_id', 'locator_code', $sublocator_id, ' and subinventory_id=' . $prddata[0]->subinventory_id);
						}

					} else {
						$this->data['lines_data'][$k]->subinventory_id = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', '');
						$this->data['lines_data'][$k]->sublocator_id = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', '');


					}
				}
			}
		}

		return view("productionmovetoinventory.form", $this->data);



	}


	public function save(Request $request)
	{

		$acc_data = \DB::table('f_journal_entry_t')->join('f_journal_entry_lines_t', 'f_journal_entry_lines_t.journal_entry_id', '=', 'f_journal_entry_t.journal_entry_id')->select('f_journal_entry_lines_t.debit_amount')->where('f_journal_entry_t.journal_reference', $_POST['job_id'])->get();

		$debit_amount = '';
		if (isset($acc_data[0]->debit_amount)) {
			$debit_amount = $acc_data[0]->debit_amount / $_POST['accept_qty'];
		}

		if ($_POST['status'] == "movetoinventoryqa") {
			$product = $_POST['bulk_product_id'];
			foreach ($product as $key => $value) {
				if ($_POST['bulk_qa_submitstage_trx_line_id'][$key] == 0 && $_POST['bulk_rejection_type'][$key] == "") {
					$trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'STORE MOVE')->get();
				} else if ($_POST['bulk_qa_submitstage_trx_line_id'][$key] == 0 && $_POST['bulk_rejection_type'][$key] == "REWORK") {
					$trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'REWORK')->get();
				} else if ($_POST['bulk_qa_submitstage_trx_line_id'][$key] == 0 && $_POST['bulk_rejection_type'][$key] == "SCRAP") {
					$trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'SCRAP STORE MOVE')->get();
				} else {
					$trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'RETURN QTY STORE MOVE')->get();
				}
				$trsns = json_decode(json_encode($trsns), true);
				/* insert data into mtl transaction tbl */
				$mdata['trx_source_type_id'] = $trsns[0]['transaction_source_id'];
				$mdata['trx_action_id'] = $trsns[0]['transaction_action_id'];
				$mdata['trx_type_id'] = $trsns[0]['transaction_type_id'];
				$mdata['trx_source_hdr_id'] = $_POST['bulk_qa_submitstage_trx_hdr_id'][$key];
				$mdata['trx_source_line_id'] = $_POST['bulk_qa_submitstage_trx_line_id'][$key];
				$mdata['line_number'] = 1;
				$mdata['product_id'] = $value;
				$mdata['trx_qty'] = $_POST['bulk_qoh'][$key];
				$mdata['trx_uom'] = $_POST['bulk_uom_code_id'][$key];
				$mdata['trx_date'] = date('Y-m-d');
				$mdata['created_by'] = \Session::get('id');
				$mdata['created_at'] = date('Y-m-d');
				$mdata['subinventory_id'] = $_POST['bulk_subinventory_id'][$key];
				$mdata['locator_id'] = $_POST['bulk_sublocator_id'][$key];
				$mdata['organization_id'] = \Session::get('organization');
				$mdata['company_id'] = \Session::get('companyid');
				$mdata['location_id'] = \Session::get('loc_id');
				$mtlid = \DB::table('m_material_trx_t')->insertGetId($mdata);


				/* insert data into qoh detail tbl */

				$dataqoh['product_id'] = $value;
				$dataqoh['qoh_uom_code_id'] = $_POST['bulk_uom_code_id'][$key];
				$dataqoh['create_trx_id'] = $mtlid;
				$dataqoh['created_by'] = \Session::get('id');
				$dataqoh['created_at'] = date('Y-m-d H:i:s');
				$dataqoh['subinventory_id'] = $_POST['bulk_subinventory_id'][$key];
				$dataqoh['locator_id'] = $_POST['bulk_sublocator_id'][$key];
				$dataqoh['job_id'] = $_POST['job_id'];
				$dataqoh['qoh_trx_date'] = date('Y-m-d', strtotime($_POST['job_date']));
				$dataqoh['qoh_source_id'] = $_POST['bulk_qa_submitstage_trx_hdr_id'][$key];
				$dataqoh['batch_number'] = $_POST['plan_no'][$key];


				if ($_POST['bulk_qa_submitstage_trx_line_id'][$key] == "" && $_POST['bulk_rejection_type'][$key] == "") {
					$dataqoh['qoh_source'] = "PRODUCTION STORE MOVE";
					$dataqoh['qoh_trx_qty'] = $_POST['bulk_qoh'][$key];
					$dataqoh['scrap_qty'] = 0;
				} else if ($_POST['bulk_qa_submitstage_trx_line_id'][$key] == "" && $_POST['bulk_rejection_type'][$key] == "REWORK") {
					$dataqoh['qoh_source'] = "REWORK STORE MOVE";
					$dataqoh['qoh_trx_qty'] = $_POST['bulk_qoh'][$key];
					$dataqoh['scrap_qty'] = 0;
				} else if ($_POST['bulk_qa_submitstage_trx_line_id'][$key] == "" && $_POST['bulk_rejection_type'][$key] == "SCRAP") {
					$dataqoh['qoh_source'] = "SCRAP STORE MOVE";
					$dataqoh['scrap_qty'] = $_POST['bulk_qoh'][$key];
				} else {
					$dataqoh['qoh_source'] = "RETURN QTY STORE MOVE";
					$dataqoh['qoh_trx_qty'] = $_POST['bulk_qoh'][$key];
					$dataqoh['scrap_qty'] = 0;
				}
				$dataqoh['organization_id'] = \Session::get('organization');
				$dataqoh['product_expire_date'] = $_POST['product_expire_date'];
				if ($key == 0) {
					$dataqoh['cost'] = $debit_amount;
				}

				$group = \DB::select("select m_products_t.*,m_product_groups_t.group_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where product_id='" . $value . "'");
				if ($group[0]->group_name == "SEMI FINISHED GOODS") {
					$manuf_date = explode("-", $_POST['job_date']);
					$manuf_date1 = $manuf_date[0];
					$manuf_date2 = $manuf_date[1];
					$dataqoh['manufacturer_date'] = $manuf_date2 . "/" . $manuf_date1;
				} else if ($group[0]->group_name == "FINISHED GOODS") {
					$date = date('Y-m-d');
					$org = \Session::get('organization');
					$loc = \Session::get('loc_id');
					$compy = \Session::get('companyid');
					$jobid = $_POST['job_id'];
					$journal_name = "FG Move-" . $_POST['job_no'];
					/*Journal Header Insert*/
					$journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','JOB CARD CLOSE','$date','$jobid','APPROVED','$compy','$loc','$org')");
					$jid = \DB::getPdo()->lastInsertId();
					$tkey = 0;

					$journal_lines_data[$tkey]['journal_entry_id'] = $jid;
					$journal_lines_data[$tkey]['journal_date'] = date('Y-m-d');
					$journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
					$journal_lines_data[$tkey]['reference_id'] = $value;
					$journal_lines_data[$tkey]['account_id'] = $group[0]->account_code_id;
					$journal_lines_data[$tkey]['debit_amount'] = $acc_data[0]->debit_amount;
					$journal_lines_data[$tkey]['credit_amount'] = '';
					$journal_lines_data[$tkey]['line_no'] = $tkey + 1;
					$journal_lines_data[$tkey]['created_by'] = \Session::get('id');
					$journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
					$journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
					$journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
					;
					$journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
					$journal_lines_data[$tkey]['company_id'] = \Session::get('location');

					$tkey++;
					$journal_lines_data[$tkey]['journal_entry_id'] = $jid;
					$journal_lines_data[$tkey]['journal_date'] = date('Y-m-d');
					$journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
					$journal_lines_data[$tkey]['reference_id'] = $value;
					$journal_lines_data[$tkey]['account_id'] = $group[0]->control_account_id;
					$journal_lines_data[$tkey]['debit_amount'] = '';
					$journal_lines_data[$tkey]['credit_amount'] = $acc_data[0]->debit_amount;
					$journal_lines_data[$tkey]['line_no'] = $tkey + 1;
					$journal_lines_data[$tkey]['created_by'] = \Session::get('id');
					$journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
					$journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
					$journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
					;
					$journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
					$journal_lines_data[$tkey]['company_id'] = \Session::get('loc_id');

					\DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);
					$dataqoh['manufacturer_date'] = $_POST['manufacturer_date'];
				}
				$dataqoh['company_id'] = \Session::get('companyid');
				$dataqoh['location_id'] = \Session::get('loc_id');
				$mdata['created_by'] = \Session::get('id');
				$mdata['last_updated_by'] = \Session::get('id');
				$qohid = \DB::table('i_qoh_detail_t')->insertGetId($dataqoh);
				$job = \DB::select('select * from w_jobcard_hdr_t where w_jobs_hdr_id=' . $_POST['job_id']);
				$qasql = \DB::select("SELECT * FROM `w_qa_submitstage_trx_t` WHERE job_no=" . $_POST['job_id']);
				if (count($qasql) > 0) {
					if ($qasql[0]->quality_check == 'No') {
						if ($group[0]->group_name == "FINISHED GOODS" && $job[0]->bom_process == 'FINALPROCESS') {
							$qualitystatus = 1;
						} else if ($group[0]->group_name == "SEMI FINISHED GOODS") {
							$qualitystatus = 1;
						} else {
							$qualitystatus = 0;
						}
						\DB::table('i_qoh_detail_t')->where('job_id', $_POST['job_id'])
							->update(['qualitystatus' => $qualitystatus, 'qualitytype' => 'noqccheck']);
						/*deepika purpose: plan qty Updation in plan*/

						$plan = \DB::select('select * from w_productionplan_hdr_t where productionplan_hdr_id=' . $job[0]->reference_source_id);
						$planqty = $plan[0]->plan_qty + $_POST['accept_qty'];
						$pendqty = $plan[0]->pending_qty - $_POST['accept_qty'];
						if ($pendqty == 0) {
							$plan_status = "CLOSED";
						} else {
							$plan_status = $plan[0]->plan_status;
						}
						if (count($job) > 0) {
							if ($job[0]->bom_process == 'FINALPROCESS') {
								\DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('product_id', $_POST['bulk_product_id'][0])->update(['plan_qty' => $planqty, 'pending_qty' => $pendqty, 'plan_status' => $plan_status]);
							}
							$planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('product_id', $_POST['bulk_product_id'][0])->get();
							$qty = $planlines[0]->qty;
							$prdqty = $planlines[0]->production_qty + $_POST['accept_qty'];
							$pendingqty = $qty - $prdqty;
							if ($pendingqty < 0) {
								$pendingqty = 0;
							} else {
								$pendingqty = $pendingqty;
							}
							\DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('product_id', $_POST['bulk_product_id'][0])->update(['production_qty' => $prdqty, 'pending_qty' => $pendingqty]);
						}
						/*end*/
					} else if ($qasql[0]->quality_check == 'Yes' && $qasql[0]->qc_status == 1) {
						if ($group[0]->group_name == "FINISHED GOODS" && $job[0]->bom_process == 'FINALPROCESS') {
							$qualitystatus = 1;
						} else if ($group[0]->group_name == "SEMI FINISHED GOODS") {
							$qualitystatus = 1;
						} else {
							$qualitystatus = 0;
						}
						\DB::table('i_qoh_detail_t')->where('job_id', $_POST['job_id'])
							->update(['qualitystatus' => $qualitystatus, 'qualitytype' => 'qccheck']);
					}
				}
				\DB::table('w_jobcard_hdr_t')
					->where('w_jobs_hdr_id', $_POST['job_id'])
					->update(['job_status' => 'STORE MOVED']);

				\DB::table('i_quality_spec_trx_hdr_t')
					->where('quality_spec_trx_hdr_id', $_POST['quality_spec_trx_hdr_id'])
					->update(['storemove_status' => '1']);
				\DB::table('w_qa_submitstage_trx_t')
					->where('qa_submitstage_trx_hdr_id', $_POST['bulk_qa_submitstage_trx_line_id'][$key])
					->update(['storemove_status' => '1']);
			}
		}

		\DB::table('notifications_t')->where('reference_source_id', $_POST['quality_spec_trx_hdr_id'])->where('reference_source', '=', 'QA APPROVAL')->update(['read/unread' => 'read']);

		return response()->json(array('status' => 'success', 'message' => 'Saved Successfully'));

	}


	public function qasubmitstageappData(Request $request)
	{
		$loc = Session::get('loc_id');
		$compy = Session::get('companyid');
		$org = Session::get('organization');

		$wh = $this->grid_check('w_qa_submitstage_trx_t', 'job_date');

		$query = DB::table('w_qa_submitstage_trx_t')
			->select([
				'w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id',
				'w_qa_submitstage_trx_t.reference_no',
				'w_qa_submitstage_trx_t.qa_status',
				'i_quality_spec_trx_hdr_t.created_at',
				'w_jobcard_hdr_t.job_no',
				'w_jobcard_hdr_t.w_jobs_hdr_id as w_jobs_id',
				'w_qa_submitstage_trx_t.batch_no',
				'w_jobcard_hdr_t.job_date',
				'm_products_t.concatenated_product',
				'm_products_t.product_code',
				'w_qa_submitstage_trx_t.production_qty',
				'w_qa_submitstage_trx_t.remarks',
				'w_qa_submitstage_trx_t.organization_id'
			])
			->leftJoin('w_jobcard_hdr_t', 'w_jobcard_hdr_t.w_jobs_hdr_id', '=', 'w_qa_submitstage_trx_t.job_no')
			->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'w_qa_submitstage_trx_t.product_id')
			->leftJoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_id')
			->leftJoin('i_quality_spec_trx_hdr_t', function ($join) {
				$join->on('i_quality_spec_trx_hdr_t.job_hdr_id', '=', 'w_jobcard_hdr_t.w_jobs_hdr_id');
			})
			->whereDate('i_quality_spec_trx_hdr_t.qatrx_date', '>=', '2024-04-01')
			->where(function ($query) {
				$query->where(function ($q) {
					$q->where('w_qa_submitstage_trx_t.store_move', '=', 'Yes')
						->whereIn('w_qa_submitstage_trx_t.qa_status', ['INITIATED', 'APPROVED']);
				});
			})
			->whereIn('w_jobcard_hdr_t.job_status', ['QA SUBMITTED', 'REWORK', 'SCRAP'])
			->where('w_qa_submitstage_trx_t.storemove_status', 0);

		// Apply date filter if needed
		// if (!empty($wh)) {
		//    $query->whereRaw($wh);
		//  }

		return DataTables::of($query)->make(true);
	}




}
