<?php

namespace App\Http\Controllers;

use App\Salesreturn;
use App\Salesreturnlines;
use App\Salesinvoice;
use App\Salesinvoicelines;
use Illuminate\Http\Request;
use DB, Session;
use App\Deliveryterms;
use Yajra\DataTables\DataTables;

class SalesreturnController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->table = "so_rma_hdr_t";
        $this->subtable = "so_rma_lines_t";
        $this->pageModule = "salesreturn";
        $this->model = new Salesreturn;
        $this->submodel = new Salesreturnlines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        if ($this->data['pageMethod'] == "salesreturncreate") {
            $this->data['pageurl'] = "salesreturn";
            $this->data['source'] = 'SALESRETURN';
            $this->data['url'] = 'salesreturn';
            $this->data['status'] = '';
        } else if ($this->data['pageMethod'] == "salesreturnfrominvoice") {
            $this->data['status'] = 'INVOICE';
            $this->data['pageurl'] = "salesreturnfrominvoice";
            $this->data['url'] = 'salesreturnfrominvoice';
            $this->data['source'] = 'SALESRETURNFROMINVOICE';
        } else if ($this->data['pageMethod'] == "salesreturnapprovalcreate") {
            $this->data['pageurl'] = "salesreturnapproval";
            $this->data['url'] = 'salesreurnapproval';
            $this->data['source'] = 'SALESRETURNAPPROVAL';
            $this->data['status'] = '';
            $this->data['pageFormtype'] = 'ajax';
        }

        $this->data['urlmenu'] = $this->indexs();
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

        return view('salesreturn.table', $this->data);

    }


    public function returnData(Request $request)
    {

        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');

        $table = \DB::table('so_rma_hdr_t')->get();
        $this->data['datas'] = $table;

        $this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
        $wh = '';
        $compy = \Session::get('companyid');
        $wh .= " AND DATE(so_rma_hdr_t.return_date) BETWEEN '$grid_date' AND '$gridenddate' ";

        $SQL = "SELECT so_rma_hdr_t.so_rma_hdr_id,so_rma_hdr_t.rma_ref_no,so_rma_hdr_t.return_status,so_rma_hdr_t.reference_no,so_rma_hdr_t.return_source,
                 so_rma_hdr_t.return_date,so_rma_hdr_t.total_amount,s_invoice_hdr_t.invoice_number,m_customers_t.customer_name FROM `so_rma_hdr_t` 
                 left join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=so_rma_hdr_t.reference_source_id left join m_customers_t on m_customers_t.customer_id=so_rma_hdr_t.customerid 
                where 1=1  $wh ORDER BY so_rma_hdr_t.so_rma_hdr_id desc";


        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }



    function getFreight($id)
    {
        $sql = \DB::SELECT("select * from m_frieghtcarriers_hdr_t where ar_frieghtcarriers_hdr_id='" . $id . "'");
        if (!empty($sql))
            return $sql[0]->carrier_name;
        else
            return '';
    }

    /*deepika purpose:print function*/
    public function getPrint($id = null, $invoice = null)
    {
        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
        $salesreturn = DB::table('so_rma_hdr_t')->leftjoin('m_customers_t', 'm_customers_t.customer_id', '=', 'so_rma_hdr_t.customerid')->select('m_customers_t.customer_id', 'm_customers_t.customer_name', 'm_customers_t.customer_number', 'm_customers_t.delivery_terms_id', 'm_customers_t.ar_frieghtcarriers_hdr_id', 'm_customers_t.default_payment_terms_id', 'so_rma_hdr_t.*')->where('so_rma_hdr_id', $id)->get();
        if (!empty($salesreturn)) {
            $this->data['lr_no'] = "";
            $this->data['eway_billno'] = "";
            $this->data['pack_weight'] = '';
            $this->data['packaging_qty'] = '';
            $this->data['rma_ref_no'] = $salesreturn[0]->rma_ref_no;
            $this->data['so_rma_hdr_id'] = $salesreturn[0]->so_rma_hdr_id;
            $this->data['return_date'] = date('d-m-Y', strtotime($salesreturn[0]->return_date));
            $this->data['invoice_type'] = "";
            $this->data['trade_discount'] = "";
            $this->data['trade_discount_pre'] = "";
            $this->data['round_off'] = "";

            $this->data['reference_number'] = $salesreturn[0]->reference_no;
            if ($salesreturn[0]->return_source == "INVOICE") {
                $invdata = \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $salesreturn[0]->reference_source_id)->get();
                $invdispatchdata = \DB::select("SELECT s_invoice_hdr_t.invoice_hdr_id,s_dispatch_hdr_t.dispatch_number,s_dispatch_hdr_t.dispatch_date  FROM `s_invoice_hdr_t` left join s_dispatch_hdr_t on(s_dispatch_hdr_t.so_dispatch_hdr_id=s_invoice_hdr_t.reference_source_id and s_invoice_hdr_t.source='DISPATCH') where s_invoice_hdr_t.invoice_hdr_id=" . $salesreturn[0]->reference_source_id);
                $this->data['invoice_no'] = $invdata[0]->invoice_number;
                $this->data['invoice_date'] = date('d-m-Y', strtotime($invdata[0]->invoice_date));
                $this->data['dispatch_no'] = $invdispatchdata[0]->dispatch_number;
                $this->data['dispatch_date'] = date('d-m-Y', strtotime($invdispatchdata[0]->dispatch_date));
            } else {
                $this->data['invoice_no'] = "";
                $this->data['invoice_date'] = "";
                $this->data['dispatch_no'] = "";
                $this->data['dispatch_date'] = "";
            }
            $this->data['remarks'] = $salesreturn[0]->remarks;
            $deliveryterm = Deliveryterms::where('delivery_terms_id', $salesreturn[0]->delivery_terms_id)->select('delivery_term_name', 'remarks')->get();

            if (count($deliveryterm) > 0) {
                $this->data['deliveryterm'] = $deliveryterm[0]->delivery_term_name;
                $this->data['del_remarks'] = $deliveryterm[0]->remarks;
            } else {
                $this->data['deliveryterm'] = '';
                $this->data['del_remarks'] = '';
            }
            // dd(count($deliveryterm));
            $this->data['despatch_through'] = $this->getFreight($salesreturn[0]->ar_frieghtcarriers_hdr_id);
            $comp = \DB::table('m_company_t')->where('company_id', $salesreturn[0]->company_id)->get();
            // dd($comp[0]);
            if ($comp->isNotEmpty()) {
                $this->data['company_name'] = $comp[0]->company_name;
                $this->data['cmp_gst_no'] = $comp[0]->gst_no;
                $this->data['pan_no'] = $comp[0]->pan_no;
                $this->data['email_id'] = $comp[0]->email_id;
                $this->data['cin_no'] = $comp[0]->cin_no;
                $this->data['excise_registration_no'] = $comp[0]->excise_registration_no;
                $this->data['tax_reg_no'] = $comp[0]->tax_reg_no;
                $this->data['website_address'] = $comp[0]->website_address;
            } else {
                $this->data['company_name'] = "";
                $this->data['cmp_gst_no'] = "";
                $this->data['pan_no'] = "";
                $this->data['email_id'] = "";
                $this->data['cin_no'] = "";
                $this->data['excise_registration_no'] = "";
                $this->data['tax_reg_no'] = "";
                $this->data['website_address'] = "";
            }
            $this->data['customer_po_number'] = '';
            $this->data['sales_order_no'] = '';
            $this->data['sales_order_date'] = '';
            $this->data['dispatch_no'] = '';
            $this->data['dispatch_date'] = '';
            $this->data['packing_qty'] = '';
            $this->data['packing_weight'] = '';

            /*   if($salesreturn[0]->source  =="DISPATCH")
               {
                   $this->data['customer_po_number'] = '';
                   $disp = $this->dispno($salesreturn[0]->reference_source_id);
                   $this->data['dispatch_no'] =$disp['number'];
                   $this->data['dispatch_date'] =$disp['date'];
                   $this->data['packing_qty'] =$disp['packingqty'];
                   $this->data['packing_weight'] =$disp['packweight'];
               }*/

            $this->data['payment_term_name'] = $this->getPaymentterm($salesreturn[0]->default_payment_terms_id);
        } else {
            $this->data['payment_term_name'] = "";
            $this->data['trade_discount'] = "";
            $this->data['trade_discount_pre'] = "";
            $this->data['round_off'] = "";
            $this->data['despatch_through'] = '';
            $this->data['lr_no'] = '';
            $this->data['eway_billno'] = '';
            $this->data['pack_weight'] = '';
            $this->data['packaging_qty'] = '';
            $this->data['invoice_no'] = '';
            $this->data['invoice_date'] = '';
            $this->data['invoice_type'] = '';
            $this->data['reference_number'] = '';
            $this->data['sales_order_no'] = '';
            $this->data['sales_order_date'] = '';
            $this->data['despatch_through'] = '';
            $this->data['remarks'] = '';
            $this->data['invoice_hdr_id'] = '';
            $this->data['deliveryterm'] = '';
            $this->data['gst_no'] = "";
            $this->data['company_name'] = "";
            $this->data['cmp_gst_no'] = "";
            $this->data['pan_no'] = "";
            $this->data['email_id'] = "";
            $this->data['cin_no'] = "";
            $this->data['excise_registration_no'] = "";
            $this->data['tax_reg_no'] = "";
            $this->data['website_address'] = "";
            $this->data['customer_po_number'] = "";
            $this->data['dispatch_no'] = "";
            $this->data['dispatch_date'] = "";
            $this->data['packing_qty'] = '';
            $this->data['packing_weight'] = "";
            $this->data['invoice_no'] = "";
            $this->data['invoice_date'] = "";
            $this->data['dispatch_no'] = "";
            $this->data['dispatch_date'] = "";
        }

        $lid = $salesreturn[0]->so_rma_hdr_id;
        /******************** current Date ********************/
        $this->data['date'] = date('d/m/Y');
        /****************** End *******************************/

        /************************* location based addrss ************************/

        $address = $this->getLocationwiseaddress($salesreturn[0]->location_id);


        if ($address != 0) {
            $location_name = $address[0]->location_name;
            $this->data['location_name'] = $location_name;
            $address1 = $address[0]->address;
            $this->data['address'] = $address1;
            $street = $address[0]->street_name;
            $this->data['street_name'] = $street;
            $location = $address[0]->location_name;
            $this->data['location'] = $location;
            $area = $address[0]->area;
            $this->data['area'] = $area;
            //GET COMPANY ADDRESS
            $this->data['company_gst_no'] = $address[0]->gst_no;
            $this->data['pan_no'] = $address[0]->pan_no;
            $city = $this->data['city'] = $this->getCity($address[0]->city_id);
            $state = $this->data['state'] = $this->getState($address[0]->state_id);

            $state_no = $this->data['state_no'] = $state = $this->data['state'][0]->state_code_no;
            $state_name = $this->data['state_name'] = $state = $this->data['state'][0]->state_name;
            $state_code = $this->data['state_code'] = $state = $this->data['state'][0]->state_code;
            $country = $this->data['country'] = $this->getCountry($address[0]->country_id);
            $this->data['gst_no'] = $address[0]->gst_no;
            $this->data['e_mail'] = $address[0]->e_mail;
            $this->data['state_code'] = $this->data['state'][0]->state_code;
        } else {
            $this->data['location_name'] = "";
            $this->data['street_name'] = "";
            $this->data['state_name'] = "";
            $this->data['area'] = "";
            $this->data['gst_no'] = '';
            $this->data['e_mail'] = "";
            $this->data['state_code'] = "";
            $this->data['address'] = "";
            $this->data['location'] = '';
            $this->data['state_no'] = "";
            $this->data['company_gst_no'] = "";
            $this->data['pan_no'] = "";
            $city = "";
            $country = "";
        }

        $this->data['company_address'] = $this->data['address'] . "," . $this->data['street_name'] . "" . $this->data['area'];
        $this->data['company_city'] = $city . "," . $this->data['state_name'] . "," . $country;


        /************************* Customer ************************************/
        $cus_id = $salesreturn[0]->customerid;
        $cust = $this->getCustomer($salesreturn[0]->customerid);
        if ($cus_id != 0) {
            if (!empty($cust)) {
                $this->data['customer_name'] = $cust[0]->customer_name;
                $this->data['cus_gst_no'] = "";
            } else {
                $this->data['customer_name'] = '';
                $this->data['cus_gst_no'] = '';
            }
        } else {
            //      $emp=$this->getEmployee($salesreturn[0]->employee_id);
            //   $this->data['customer_name']=$emp[0]->empname;

        }
        /*************************** end ***************************************/

        /************************** bill  and ship to address  ****************/

        //dd($cus_id);

        $bill_to_ship_address = $this->getBill_to_address($cus_id, $salesreturn[0]->ship_to_address_id);
        //   $emp_address=$this->getemployee_to_address($salesreturn[0]->employee_id);
        //   dd($bill_to_ship_address);
        if ($bill_to_ship_address != 0) {
            foreach ($bill_to_ship_address as $key => $value) {

                if ($value->site_type == "SHIP_TO") {
                    $this->data['address'] = $value->address;
                    $this->data['city'] = $this->getCity($value->city);
                    //$this->data['city_bill']=$this->data['city'][0]->city_name;
                    $dest = \DB::SELECT('select * from m_location_t where location_id=' . $bill_to_ship_address[0]->location_id . '');
                    $this->data['destination'] = $dest[0]->location_name;
                    $states = $this->getState($value->state);
                    if ($states != 0) {
                        $this->data['state_id_ship'] = $states[0]->state_code_no;
                        $this->data['state_name_ship'] = $states[0]->state_name;
                        $this->data['state_code_bill'] = $states[0]->state_code;
                    } else {
                        $this->data['state_id_ship'] = $states;
                        $this->data['state_name_ship'] = $states;
                        $this->data['state_code_bill'] = $states;
                    }
                    $this->data['country'] = $this->getCountry($value->country);
                    //$this->data['country_bill']=$this->data['country'][0]->country_name;
                    $this->data['pincode'] = $value->pincode;
                    $this->data['contact_number'] = $value->contact_number;
                    $this->data['contact_person'] = $value->contact_person;
                    $this->data['ship_gst_no'] = $value->gst_no;

                    $this->data['ship_to_address_1'] = $this->data['address'] . "," . $this->data['city'] . "," . $this->data['state_name_ship'] . "," . $this->data['country'] . "," . $this->data['pincode'];

                    //dd($this->data['ship_to_address_1']);            
                }
            }
        } else {
            $this->data['ship_to_address_1'] = "";
            $this->data['contact_person'] = "";
            $this->data['contact_number'] = "";
            $this->data['state_id_ship'] = "";
            $this->data['state_name_ship'] = "";
            $this->data['ship_gst_no'] = "";
            $this->data['state_id_ship'] = "";
            $this->data['state_name_ship'] = "";
        }
        $this->data['company_logo'] = \Session::get('companylogo');
        /*************************** End *********************************/

        $so_table = \DB::table('so_rma_lines_t')->where('so_rma_hdr_id', $lid)->get();
        if (count($so_table) > 0) {

            $this->data['tax'] = $so_table[0]->tax_group_id;
            $tax = $so_table[0]->tax_group_id;
            $tax = \DB::table('m_tax_group_t')->where('tax_group_id', $so_table[0]->tax_group_id)->get();
            if (count($tax) > 0) {
                $this->data['tax_id'] = $tax[0]->tax_group_name;
            } else {
                $this->data['tax_id'] = "";
            }
        } else {
            $this->data['tax'] = '';
            $this->data['tax_id'] = "";
        }



        $linetable = DB::table('so_rma_lines_t')->where('so_rma_hdr_id', $id)->get();
        //$this->data['linetable']=$linetable;

        //************** Tax calculation ****************//

        $tax_cal = DB::table('m_tax_group_t')->leftjoin('m_tax_group_lines_t', 'm_tax_group_lines_t.tax_group_id', '=', 'm_tax_group_t.tax_group_id')->leftjoin('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'm_tax_group_t.*', 'f_tax_code_t.*')->where('m_tax_group_t.tax_group_id', $linetable[0]->tax_group_id)->get();

        if (count($tax_cal) > 0) {
            $this->data['tax_group'] = $tax_cal[0]->tax_group_name;
            $this->data['tax_group_name'] = $tax_cal[0]->display_name;
            $taxcode = "";
            foreach ($tax_cal as $k => $v) {
                $taxcode .= $v->tax_code_name . ",";
            }
            $taxcode1 = rtrim($taxcode, ",");
            $this->data['taxcode1'] = explode(",", $taxcode1);
        } else {
            $this->data['tax_group'] = "";
            $this->data['tax_group_name'] = "";
            $this->data['taxcode1'] = "";
        }
        $tax_split = $this->data['tax_group_name'];
        $split = preg_split('#(?<=\d)(?=[a-z])#i', $tax_split);
        $this->data['tax_value'] = $split;

        $this->data['subgrid'] = $linetable;
        $subtotal = 0;
        $sub_total = 0;
        $tax_amount = 0;
        $X = 0;
        $Y = 0;
        $total = 0;
        $tot_desc = 0;
        $key = 0;
        $qtytotal = 0;
        foreach ($this->data['subgrid'] as $ke => $value) {
            $date = $value->manufracture_date;
            $time = strtotime($value->expiry_date);

            // $expdate = date('M-Y',$time);
            $expdate = $value->expiry_date;
            $mfgtime = strtotime($value->manufracture_date);
            //   $time = date('Y-d-m',$time);
            //  $time = strtotime($time);
            // $mfgdate = date('M-Y',$mfgtime);
            $mfgdate = $value->manufracture_date;


            $polines[$key]['batch_mfg'] = "(Batch No." . $value->batch_number . ",Mfg Dt." . $mfgdate . ",Exp Dt." . $expdate . ")";
            $tax = \DB::table("m_tax_group_t")->select('display_name', 'tax_group_name')->where('tax_group_id', $value->tax_group_id)->get();

            $polines[$key]['line_no'] = $value->line_no;
            $polines[$key]['qty'] = $value->return_qty;
            if (count($tax) > 0) {
                $polines[$key]['gst_tax'] = $tax[0]->display_name;
                $polines[$key]['gst_per'] = $tax[0]->tax_group_name;
                $polines[$key]['gst_per_gst'] = explode(" ", $tax[0]->tax_group_name);
                $polines[$key]['gst_igst'] = $polines[$key]['gst_per_gst'][0];
            } else {
                $polines[$key]['gst_tax'] = "";
                $polines[$key]['gst_per'] = "";
                $polines[$key]['gst_per_gst'] = "";
                $polines[$key]['gst_igst'] = "";
            }

            if ($value->product_id != '0') {
                $invoice = new SalesinvoiceController();
                $arr = $invoice->getProduct($value->product_id);
                $polines[$key]['product'] = $arr['concat_segment'];
                $polines[$key]['product_code'] = $arr['product_code'];
                $polines[$key]['hsn_code'] = $arr['hsn_code'];
                $polines[$key]['batch_mfg'] = $polines[$key]['batch_mfg'] . " HSN:" . $polines[$key]['hsn_code'];
                $polines[$key]['batch_number'] = $value->batch_number;
                $polines[$key]['mfg_date'] = $mfgdate;
                $polines[$key]['exp_date'] = $expdate;
                $polines[$key]['uom_code'] = $arr['primary_uom_code'];
            } else {
                $polines[$key]['product'] = '';
                $polines[$key]['hsn_code'] = '0';
                $polines[$key]['product_code'] = '';
                $polines[$key]['batch_number'] = '';
                $polines[$key]['mfg_date'] = '';
                $polines[$key]['exp_date'] = '';
            }
            $rate = (float) $value->rate;
            $qty  = (float) $value->return_qty;
            $amount1 = $qty * $rate;
            if ($polines[$key]['gst_tax'] != 0) {
                $gstPercent = (float) preg_replace('/[^0-9.]/', '', $polines[$key]['gst_tax']);
                $tax_amount = ($amount1 * $gstPercent) / 100;
            } else {
                $tax_amount = 0;
            }
            if ($polines[$key]['gst_tax'] == "28") {
                $X += $tax_amount;
                $tot_tax_amt_x = $X / 2;
            } else {
                $Y += $tax_amount;
                $tot_tax_amt_y = $Y / 2;
            }

            $date = date('Y-m-d');
            $polines[$key]['unit_price'] = $value->rate;
            $polines[$key]['discount_amount'] = $value->discount_amount;
            $tot_desc += $polines[$key]['discount_amount'];
            $polines[$key]['discount_per'] = $value->buy_discount;
            //dd($tax_amount);
            $polines[$key]['tax_amount'] = $tax_amount;
            $polines[$key]['tax_amount_1'] = $polines[$key]['tax_amount'] / 2;
            $polines[$key]['tax_amount_2'] = $polines[$key]['tax_amount'] / 2;
            $total += $value->rate * $value->return_qty;
            $total1 = ($value->rate * $value->return_qty) - (($value->return_qty * $value->rate) * ($value->buy_discount / 100));
            $polines[$key]['amount'] = $total1;

            $dis_amt = ($value->rate) - ($value->rate * ($value->buy_discount / 100));

            $polines[$key]['taxable_amount'] = $polines[$key]['amount'] - $polines[$key]['discount_amount'];
            $amount = $dis_amt * $value->return_qty;
            //dd($polines[$key]['taxable_amount']);	
            $subtotal = $amount + $subtotal;
            $sub_total = $sub_total + $amount;
            $tot_tax_amt = $X + $Y;
            $qtytotal += $value->return_qty;
            $key++;
        }

        if (!empty($tot_tax_amt_y)) {
            $this->data['tot_tax_amt_y'] = $tot_tax_amt_y;
        } else {
            $this->data['tot_tax_amt_y'] = '';
        }

        $this->data['gst_amt_x'] = $X;
        $this->data['gst_amt_y'] = $Y;
        if (!empty($tot_tax_amt_x)) {
            $this->data['tot_tax_amt_x'] = $tot_tax_amt_x;
        } else {
            $this->data['tot_tax_amt_x'] = '';
        }
        $this->data['tot_amt_aftr_tax'] = $polines[$ke]['amount'] + $tot_tax_amt - $tot_desc;
        $this->data['total'] = $polines[$ke]['amount'];
        $this->data['tot_desc'] = $tot_desc;
        $this->data['tot_tax_amt'] = $tot_tax_amt;
        //$this->data['total_amount_after_tax']=$total_amount_after_tax;
        $this->data['linedata'] = $polines;
        $this->data['sub_total'] = $subtotal;
        $this->data['qtytotal'] = $qtytotal;
        $line_total = $subtotal + $tot_tax_amt;
        $this->data['line_total'] = $line_total;

        $gstdata = \DB::select("select product_id,tax_group_id,sum((rate*return_qty)-discount_amount) as amount from so_rma_lines_t where so_rma_hdr_id='" . $id . "' and tax_group_id!='0' group by tax_group_id");
        //dd($gstdata);
        $this->trd = "";

        $gstvalue = array();
        $sgst = 0;
        $cgst = 0;
        $gsttotal = 0;
        $grand_total = 0;
        //dd($gstdata);
        foreach ($gstdata as $gst_key => $gst_value) {

            $arr_sgcgst = array('sgst', 'cgst');
            $invoice = new SalesinvoiceController();
            $arr = $invoice->getProduct($gst_value->product_id);

            $tax = \DB::table("m_tax_group_t")->select('display_name', 'tax_group_name')->where('tax_group_id', $gst_value->tax_group_id)->get();
            //dd($tax);
            if (strpos($tax[0]->tax_group_name, 'IGST') !== false) {
                $gstvalue[$gst_key]['gsttype'] = "IGST";
            } else {
                $gstvalue[$gst_key]['gsttype'] = "GST";
            }
            if ($tax->isNotEmpty()) {
                $display_name = $tax[0]->display_name;
                $gstvalue[$gst_key]['tax_group_name'] = $tax[0]->tax_group_name;/* important */
            } else {
                $gstvalue[$gst_key]['tax_group_name'] = '';
            }
            //dd($id);
            $gst = $invoice->getGst($gst_value->tax_group_id, $arr['hsn_code'], $id);
            // dd($gst);
            $gstvalue[$gst_key]['gst'][] = $display_name;/* important */


            $gstvalue[$gst_key]['sgcgst'] = $display_name / 2;

            $gstvalue[$gst_key]['hsn'] = $arr['hsn_code'];

            $gstvalue[$gst_key]['amounttax'] = $gst_value->amount;
            $gstvalue[$gst_key]['gst_id'] = $gst_value->tax_group_id;
            $gstPercent = (float) preg_replace('/[^0-9.]/', '', $display_name);
            $gstvalue[$gst_key]['gst_val'] = $gst_value->amount * ($gstPercent / 100); /* important */

            $gstvalue[$gst_key]['sgst_val'] = $gst_value->amount * ($gstPercent / 100) / 2;

            $gstvalue[$gst_key]['cgst_val'] = $gst_value->amount * ($gstPercent / 100) / 2;

            $this->data['sgcgstt'] = $arr_sgcgst;
            //dd($gstvalue[$gst_key]['cgst_val']);
            $gsttotal = $gstvalue[$gst_key]['gst_val'] + $gsttotal;

        }
        $ka = $gsttotal / 2;

        $grand_total = $gsttotal + $sub_total;
        // dd($gstvalue);
        //Maruthu Purpose to GST calculation End
        $this->data['gst'] = $gstvalue;
        $this->data['gsttotal'] = $gsttotal;
        $this->data['value'] = $polines;
        $this->data['sub_total'] = $subtotal;
        $this->data['subtotal'] = $sub_total;
        $this->data['id'] = $id;

        /*------------------------end -------------------------------------->*/

        /*----------------------- Sales Order Details -----------------------------*/

        $sales_order_details = \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $cus_id)->get();

        if ($sales_order_details->isNotEmpty()) {
            $this->data['so_order'] = $sales_order_details[0]->sales_order_no;
            $this->data['so_date'] = date('d-m-Y', strtotime($sales_order_details[0]->sales_order_date));
            $this->data['so_ref_no'] = $sales_order_details[0]->so_ref_no;
            $this->data['ref_no'] = $sales_order_details[0]->reference_number;
            $this->data['customer_po'] = $sales_order_details[0]->customer_po;
        } else {
            $this->data['so_order'] = '';
            $this->data['so_date'] = '';
            $this->data['so_ref_no'] = '';
            $this->data['ref_no'] = '';
            $this->data['customer_po'] = '';
        }

        /**************************** End ***************************************/

        if (in_array($salesreturn[0]->return_source, ['DISPATCH', 'REPLACEMENT', 'EXPORT INVOICE'])) {
            // $lines = \DB::table('so_rma_lines_t')->leftjoin('s_dispatch_lines_t','s_dispatch_lines_t.so_dispatch_line_id','=','s_invoice_lines_t.reference_line_id')->join('s_dispatched_qty_t','s_dispatch_lines_t.so_dispatch_line_id','=','s_dispatched_qty_t.so_dispatch_line_id')->where('s_dispatched_qty_t.issue_qoh','!=','0')->where('s_invoice_lines_t.invoice_hdr_id',$id)->groupBy('s_dispatched_qty_t.batch_no','s_dispatch_lines_t.product_id')->get();
        }


        $this->data['print'] = "PRINT";
        $this->data['print_val'] = '1';
        $this->data['linedata'] = $polines;
        $terms_condition = \DB::table('a_rpt_displayelements_hdr_t')->leftjoin('a_rpt_displayelements_lines_t', 'a_rpt_displayelements_lines_t.rpt_displayelements_hdr_id', '=', 'a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id')->select('a_rpt_displayelements_lines_t.element_content')->where('a_rpt_displayelements_hdr_t.report_source', 121)->where('a_rpt_displayelements_lines_t.element_name', 'TERMS&CONDITIONS')->get();
        if (count($terms_condition) > 0) {
            $this->data['terms_condition'] = $terms_condition;
        } else {
            $this->data['terms_condition'] = [];
        }


        $this->data['print'] = "PRINT";
        $this->data['print_val'] = '1';
        //dd($this->data);
        if (isset($_GET['mails'])) {

            $this->data['print'] = "PRINTS";

            return view('salesinvoice.printform', $this->data);
        }

        return view('salesreturn.printform', $this->data);
    }
    /*end*/

    function getPaymentterm($payment_id = null)
    {
        $payment_term = \DB::table('m_payment_terms_t')->where('payment_term_id', $payment_id)->get();
        if (($payment_term->isNotEmpty())) {
            return $payment_term[0]->payment_term_name;
        } else {
            return '';
        }
    }
    function getLocationwiseaddress($location = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select * from m_location_t where location_id=' . $location . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    function getCity($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select city_id,city_name from m_cities_t where city_id=' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->city_name;
        } else {
            return 0;
        }

    }
    function getState($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select state_id,state_name,state_code,state_code_no from m_states_t where state_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;

        } else {
            return 0;
        }

    }
    function getCountry($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select country_id,country_name from m_countries_t where country_id =' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->country_name;
        } else {
            return 0;
        }
    }

    function getCompany($company = null)
    {

        $company = \DB::SELECT("select company_name,gst_no from m_company_t where company_id='$company'");
        if (!empty($company)) {
            return $company;
        } else {
            return 0;
        }
    }
    function getCustomer($id = null)
    {

        $sql = array();
        $sql = \DB::SELECT('select * from m_customers_t where customer_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }

    }
    function getemployee_to_address($id = null)
    {
        $empaddress = array();
        $empaddress = \DB::select("select * from hr_emp_contact where employee_id=" . $id . " ");
        if (!empty($empaddress)) {
            return $empaddress;
        } else {
            return 0;
        }
    }
    function getBill_to_address($bill_to_id = null, $shiptocusid = null)
    {
        //dd($bill_to_id);
        $bill_to_address = array();
        $bill_to_address = \DB::select("select * from m_customer_sites_t where customer_id=" . $bill_to_id . " and customer_site_id=" . $shiptocusid);
        // $bill_to_address=\DB::select("SELECT * FROM m_customer_sites_t WHERE customer_id=".$bill_to_id." and customer_site_number!=''");
        // dd($bill_to_address);
        if (!empty($bill_to_address)) {
            return $bill_to_address;
        } else {
            return 0;
        }
    }

    function getSuppliersite($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select * from m_supplier_sites_t where supplier_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    function getEmployee($id = null)
    {

        $sql = array();

        $sql = \DB::SELECT('select concat(employee_number,"-",first_name) as empname from hr_employee_t where employee_id=' . $id . '');

        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }

    }

    function getHsncode($hsn_id = null)
    {
        $hsn_code = \DB::select("select classification_code from f_gst_code_hdr_t where gst_code_hdr_id='$hsn_id'");
        if (!empty($hsn_code)) {
            return $hsn_code[0]->classification_code;
        } else {
            return 0;
        }
    }
    public function approvalindex(Request $request)
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

        return view('salesreturn.approvaltable', $this->data);
    }

    public function returndataApproval(Request $request)
    {


        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');
        $table = \DB::table('so_rma_hdr_t')->get();
        $this->data['datas'] = $table;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
        $wh = '';
        $wh .= " AND DATE(so_rma_hdr_t.return_date) BETWEEN '$grid_date' AND '$gridenddate' ";
        $compy = \Session::get('companyid');

        $SQL = "SELECT so_rma_hdr_t.so_rma_hdr_id,so_rma_hdr_t.rma_ref_no,so_rma_hdr_t.return_status,so_rma_hdr_t.reference_no,so_rma_hdr_t.return_source,
      so_rma_hdr_t.return_date,s_invoice_hdr_t.invoice_number,m_customers_t.customer_name FROM `so_rma_hdr_t` left join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=so_rma_hdr_t.reference_source_id left join m_customers_t on m_customers_t.customer_id=so_rma_hdr_t.customerid 
       where 1=1 and so_rma_hdr_t.return_status='INITIATED' $wh ORDER BY so_rma_hdr_t.rma_ref_no desc";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }


    public function invoiceindex(Request $request)
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

        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');
        $table = \DB::table('so_rma_hdr_t')->get();
        $this->data['datas'] = $table;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
        $wh = '';
        $compy = \Session::get('companyid');

        //  $wh.=$grid_data=$this->grid_check('s_invoice_hdr_t','invoice_date');
        $wh .= " AND DATE(s_invoice_hdr_t.invoice_date) BETWEEN '$grid_date' AND '$gridenddate' ";

        $SQL = "SELECT
                s_invoice_hdr_t.`invoice_hdr_id`,
                s_invoice_hdr_t.`invoice_type`,
                s_invoice_hdr_t.`invoice_number`,
                s_invoice_hdr_t.`invoice_date`,
                s_invoice_hdr_t.remarks,
                s_invoice_hdr_t.invoice_status,
                s_invoice_hdr_t.savestatus,
                i_pricelist_hdr_t.pricelist_name,
                m_customers_t.customer_name
               from (SELECT s_invoice_hdr_t.* FROM `s_invoice_hdr_t` LEFT JOIN s_dispatch_hdr_t ON s_dispatch_hdr_t.so_dispatch_hdr_id=s_invoice_hdr_t.reference_source_id where s_invoice_hdr_t.invoice_status='APPROVED' AND s_dispatch_hdr_t.dispatch_status='SHIPPED' and s_invoice_hdr_t.return_status=0 and s_invoice_hdr_t.company_id=$compy UNION ALL SELECT s_invoice_hdr_t.*  FROM `s_dispatch_hdr_t` left join s_invoice_hdr_t on  s_invoice_hdr_t.invoice_hdr_id=s_dispatch_hdr_t.reference_source_id WHERE s_dispatch_hdr_t.dispatch_source='INVOICE' AND dispatch_status='SHIPPED'and s_invoice_hdr_t.return_status=0 and s_invoice_hdr_t.company_id=$compy)s_invoice_hdr_t
                JOIN m_customers_t  on(
                m_customers_t.customer_id=s_invoice_hdr_t.`ship_to_customer_id`
                )
                 left join i_pricelist_hdr_t  on (
                    i_pricelist_hdr_t.pricelist_hdr_id=s_invoice_hdr_t.invoice_pricelist_id) where 1=1 $wh ORDER BY s_invoice_hdr_t.invoice_hdr_id DESC ";

        $result = \DB::select($SQL);
        $this->data['result'] = json_encode($result);
        return view('salesreturn.invoicetable', $this->data);

    }


    public function inn(Request $request)
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

        $this->data['pageMethod'] = "salesreturnview";
        $this->data['pageModule'] = "salesreturnview";

        return view('salesreturn.tableview', $this->data);
    }


    public function salesinvoicereturndata()
    {

        $wh = '';
        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');
        $compy = \Session::get('companyid');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');


        $SQL = \DB::select("select * from(SELECT
                s_invoice_hdr_t.`invoice_hdr_id`,
                s_invoice_hdr_t.`invoice_type`,
                s_invoice_hdr_t.`invoice_number`,
                s_invoice_hdr_t.`invoice_date`,
                s_invoice_hdr_t.remarks,
                s_invoice_hdr_t.invoice_status,
                s_invoice_hdr_t.savestatus,
                i_pricelist_hdr_t.pricelist_name,
                m_customers_t.customer_name
               from (SELECT s_invoice_hdr_t.* FROM `s_invoice_hdr_t` LEFT JOIN s_dispatch_hdr_t ON s_dispatch_hdr_t.so_dispatch_hdr_id=s_invoice_hdr_t.reference_source_id where s_invoice_hdr_t.invoice_status='APPROVED' AND s_dispatch_hdr_t.dispatch_status='SHIPPED' and s_invoice_hdr_t.return_status=0 and s_invoice_hdr_t.company_id=$compy UNION ALL SELECT s_invoice_hdr_t.*  FROM `s_dispatch_hdr_t` left join s_invoice_hdr_t on  s_invoice_hdr_t.invoice_hdr_id=s_dispatch_hdr_t.reference_source_id WHERE s_dispatch_hdr_t.dispatch_source='INVOICE' AND dispatch_status='SHIPPED'and s_invoice_hdr_t.return_status=0 and s_invoice_hdr_t.company_id=$compy)s_invoice_hdr_t
                JOIN m_customers_t  on(
                m_customers_t.customer_id=s_invoice_hdr_t.`ship_to_customer_id`
                )
                 left join i_pricelist_hdr_t  on (
                    i_pricelist_hdr_t.pricelist_hdr_id=s_invoice_hdr_t.invoice_pricelist_id)

                where 1=1 AND DATE(s_invoice_hdr_t.invoice_date) BETWEEN '$grid_date' AND '$gridenddate')v1 where 1=1  $wh ORDER by v1.invoice_hdr_id DESC");



        $result = $SQL;
        return DataTables::of($result)->make(true);

    }

    public function salesreturnviewdata()
    {
        $wh = '';
        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');

        $com = \Session::get('companyid');

        $wh .= $grid_data = $this->grid_check('so_rma_hdr_t', 'return_date');

        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');


        $SQL = "SELECT so_rma_hdr_t.so_rma_hdr_id,so_rma_hdr_t.rma_ref_no,so_rma_hdr_t.return_status,so_rma_hdr_t.return_date,s_invoice_hdr_t.invoice_number,m_customers_t.customer_name FROM `so_rma_hdr_t` left join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=so_rma_hdr_t.reference_source_id left join m_customers_t on m_customers_t.customer_id=so_rma_hdr_t.customerid 

                where 1=1  AND DATE(so_rma_hdr_t.return_date) BETWEEN '$grid_date' AND '$gridenddate' $wh and so_rma_hdr_t.company_id=$com";



        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);
    }

    public function getSalesreturnData()
    {
        $wh = '';
        if ($_GET['_search'] == 'true') {
            $wh = $this->jqgridsearch('s_invoice_hdr_t', $_GET['filters']);
        }
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $wh .= $grid_data = $this->grid_check('s_invoice_hdr_t', 'invoice_date');
        $result = \DB::select("SELECT COUNT(invoice_hdr_id) AS count FROM s_invoice_hdr_t where 1=1 $wh");
        $count = $result[0]->count;
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;

        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');


        $SQL = "SELECT
                s_invoice_hdr_t.`invoice_hdr_id`,
                s_invoice_hdr_t.`invoice_type`,
                s_invoice_hdr_t.`invoice_number`,
                s_invoice_hdr_t.`invoice_date`,
                s_invoice_hdr_t.remarks,
                s_invoice_hdr_t.invoice_status,
                s_invoice_hdr_t.savestatus,
                i_pricelist_hdr_t.pricelist_name,
                m_customers_t.customer_name
               from (SELECT s_invoice_hdr_t.* FROM `s_invoice_hdr_t` LEFT JOIN s_dispatch_hdr_t ON s_dispatch_hdr_t.so_dispatch_hdr_id=s_invoice_hdr_t.reference_source_id where s_invoice_hdr_t.invoice_status='APPROVED' and s_invoice_hdr_t.source='DISPATCH' AND s_dispatch_hdr_t.dispatch_status='SHIPPED' and s_invoice_hdr_t.return_status=0 and s_invoice_hdr_t.company_id=$compy UNION ALL SELECT s_invoice_hdr_t.*  FROM `s_dispatch_hdr_t` left join s_invoice_hdr_t on  s_invoice_hdr_t.invoice_hdr_id=s_dispatch_hdr_t.reference_source_id WHERE s_dispatch_hdr_t.dispatch_source='INVOICE' AND dispatch_status='SHIPPED'and s_invoice_hdr_t.return_status=0 and s_invoice_hdr_t.company_id=$compy)s_invoice_hdr_t
                JOIN m_customers_t  on(
                m_customers_t.customer_id=s_invoice_hdr_t.`ship_to_customer_id`
                )
                 left join i_pricelist_hdr_t  on (
                    i_pricelist_hdr_t.pricelist_hdr_id=s_invoice_hdr_t.invoice_pricelist_id)

                where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";


        $result = \DB::select($SQL);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
    }

    public function create($id = null)
    {

        if (isset($_GET['status'])) {
            if ($_GET['status'] == "INVOICE") {
                $salesinvoice = Salesinvoice::find($id);

                $seqno = $this->Seqnoe('RMA', 'so_rma_hdr_t', '', 'rma_count');
                $this->data['so_rma_hdr_id'] = "";
                $this->data['rma_ref_no'] = "";
                $this->data['rma_ref_no'] = "";
                $this->data['return_date'] = date('Y-m-d');
                $this->data['return_source'] = 'INVOICE';
                $this->data['return_status'] = '';
                $this->data['remarks'] = "";
                $this->data['organization_id'] = $this->jcombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
                $this->data['customerid'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', $salesinvoice->ship_to_customer_id);
                $this->data['invoice_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $salesinvoice->invoice_currency);
                $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $salesinvoice->tds_account_id);
                $this->data['tds_applicable'] = $salesinvoice->tds_applicable;
                $this->data['tds_prcnt'] = $salesinvoice->tds_prcnt;
                $this->data['tds_amount'] = $salesinvoice->tds_amount;
                $this->data['remarks'] = "";
                $this->data['total_amount'] = "";
                $this->data['reference_source_id'] = $id;
                $soorder = new SoorderController();
                $address = $soorder->getaddress($salesinvoice->ship_to_customer_id);
                //dd($address);
                $billto_add_id = explode('~', $address[0]);
                $shipto_add_id = explode('~', $address[1]);

                /*             * ** */
                $this->data['bill_to_address_id'] = $billto_add_id[1];
                $this->data['ship_to_address_id'] = $shipto_add_id[1];
                $this->data['bill_to_address'] = $billto_add_id[0];
                $this->data['ship_to_address'] = $shipto_add_id[0];
                /*             * * */


                $sql = \DB::select("select invoice_number,invoice_hdr_id from s_invoice_hdr_t where invoice_hdr_id in ($id)");
                $soinvno = "";
                foreach ($sql as $key => $value) {
                    $soinvno .= $value->invoice_number . ",";
                }
                $soinvoiceno = rtrim($soinvno, ',');
                $this->data['reference_no'] = $soinvoiceno;

                $tablelines = \DB::table('s_invoice_lines_t')->where('invoice_hdr_id', $id)->orderby('invoice_line_id', 'asc')->get();
                //dd($tablelines);
                $this->data['linedata'] = $tablelines;
                $decimal = \Session::get('decimal');
                foreach ($this->data['linedata'] as $key => $value) {

                    $rmline = \DB::table('so_rma_lines_t')->leftjoin('so_rma_hdr_t', 'so_rma_lines_t.so_rma_hdr_id', 'so_rma_hdr_t.so_rma_hdr_id')->where('product_id', $value->product_id)->where('reference_line_id', $value->invoice_line_id)->where('so_rma_hdr_t.return_status', 'APPROVED')->count();

                    $rmline_qty = \DB::table('so_rma_lines_t')->leftjoin('so_rma_hdr_t', 'so_rma_lines_t.so_rma_hdr_id', 'so_rma_hdr_t.so_rma_hdr_id')->where('product_id', $value->product_id)->where('reference_line_id', $value->invoice_line_id)->where('so_rma_hdr_t.return_status', 'APPROVED')->sum('so_rma_lines_t.return_qty');

                    if ($rmline == 0) {
                        $this->data['linedata'][$key] = (object) array();
                        $this->data['linedata'][$key]->so_rma_hdr_id = "";
                        $this->data['linedata'][$key]->so_rma_line_id = "";
                        $this->data['linedata'][$key]->line_no = $key + 1;
                        $this->data['linedata'][$key]->product_id = $this->jCombocomp('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id);
                        $this->data['linedata'][$key]->taxgroup_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);

                        $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                        $this->data['linedata'][$key]->reference_line_id = $value->invoice_line_id;
                        $invoice = number_format($value->qty, $decimal);
                        $qty = str_replace(",", "", $invoice);
                        $this->data['linedata'][$key]->invoice_qty = $qty;
                        $this->data['linedata'][$key]->return_qty = '';
                        $this->data['linedata'][$key]->returnqty = '';
                        $this->data['linedata'][$key]->discount_amount = '';
                        $this->data['linedata'][$key]->manufracture_date = date('Y-m-d');
                        $this->data['linedata'][$key]->expiry_date = date('Y-m-d');
                        $this->data['linedata'][$key]->rate = $value->unit_price;
                        $this->data['linedata'][$key]->buy_discount = "";
                        $this->data['linedata'][$key]->total_amount = "";
                        $this->data['linedata'][$key]->returned_qty = "0";
                        $this->data['linedata'][$key]->reason_code = "";
                        $this->data['linedata'][$key]->line_return_status = "";
                        $this->data['linedata'][$key]->reason_comments = "";
                        $this->data['linedata'][$key]->batch_number = $value->batch_number;
                    } else {
                        $return_qty = $value->qty - $rmline_qty;

                        if ($return_qty < 0) {
                            $return_qty = 0;
                        }
                        if ($return_qty > 0) {
                            $this->data['linedata'][$key]->so_rma_hdr_id = "";
                            $this->data['linedata'][$key]->so_rma_line_id = "";
                            $this->data['linedata'][$key]->line_no = $key + 1;
                            $this->data['linedata'][$key]->product_id = $this->jCombosalreturn('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id);
                            $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                            $this->data['linedata'][$key]->taxgroup_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
                            $this->data['linedata'][$key]->reference_line_id = $value->invoice_line_id;
                            $this->data['linedata'][$key]->invoice_qty = $value->qty;
                            $this->data['linedata'][$key]->return_qty = '';
                            $this->data['linedata'][$key]->returnqty = '';
                            $this->data['linedata'][$key]->discount_amount = '';
                            $this->data['linedata'][$key]->returned_qty = $rmline_qty;
                            $this->data['linedata'][$key]->reason_comments = "";
                            $this->data['linedata'][$key]->batch_number = $value->batch_number;
                            $this->data['linedata'][$key]->manufracture_date = date('Y-m-d');
                            $this->data['linedata'][$key]->expiry_date = date('Y-m-d');
                            $this->data['linedata'][$key]->rate = $value->unit_price;
                            $this->data['linedata'][$key]->buy_discount = "";
                            $this->data['linedata'][$key]->total_amount = "";
                            $this->data['linedata'][$key]->reason_code = "";
                            $this->data['linedata'][$key]->line_return_status = "";
                        } else {
                            unset($this->data['linedata'][$key]);
                        }
                    }

                }
                $this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
                $this->data['custypeopt'] = $this->jqgridselect('m_customer_types_t', 'customer_type_id', 'customer_type');
                $this->data['country'] = $this->jqgridselect('m_countries_t', 'country_id', 'country_name');
                $this->data['state'] = $this->jqgridselect('m_states_t', 'state_id', 'state_name');
                $this->data['city'] = $this->jqgridselect('m_cities_t', 'city_id', 'city_name');
            }
        } else {
            $this->data['pageMethod'] = \Request::route()->getName();
            // dd($this->data['pageMethod']);
            if ($id == "") {

                $this->data['pagemode'] = 'create';
                $this->data['so_rma_hdr_id'] = "";
                $this->data['rma_ref_no'] = "";
                $this->data['return_date'] = date('Y-m-d');
                $this->data['return_source'] = 'DIRECT';
                $this->data['total_amount'] = '';
                $this->data['return_status'] = '';
                $this->data['tds_applicable'] = '';
                $this->data['tds_prcnt'] = "";
                $this->data['tds_amount'] = "";
                $this->data['remarks'] = "";
                $this->data['organization_id'] = $this->jcombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
                $this->data['customerid'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', '');
                $this->data['invoice_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '');
                $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
                $this->data['remarks'] = "";
                $this->data['reference_source_id'] = $id;
                $this->data['bill_to_address_id'] = "";
                $this->data['ship_to_address_id'] = "";
                $this->data['bill_to_address'] = "";
                $this->data['ship_to_address'] = "";
                $this->data['reference_no'] = "";
                $this->data['product_id'] = $this->jCombosalreturn('m_products_t', 'product_id', 'product_code|concatenated_product', '');
                $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
                $this->data['taxgroup_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
                $this->data['linedata'] = array();

            } else {

                $this->data['pageMethod'] = \Request::route()->getName();
                $source = \DB::table('so_rma_hdr_t')->select('so_rma_hdr_t.*')

                    ->where('so_rma_hdr_id', $id)->get();
                //  dd($source);
                //dd($source[0]->customerid);
                $this->data['customerid'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', $source[0]->customerid);
                //dd($this->data['customerid']);
                $this->data['so_rma_hdr_id'] = $id;
                $this->data['pagemode'] = 'edit';
                // dd($source[0]);
                $this->data['invoice_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $source[0]->invoice_currency);
                $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $source[0]->tds_account_id);
                $this->data['rma_ref_no'] = $source[0]->rma_ref_no;
                $this->data['total_amount'] = $source[0]->total_amount;
                $sesdate = \Session::get('p_date_format');
                $this->data['return_date'] = date($sesdate, strtotime($source[0]->return_date));
                $this->data['return_source'] = $source[0]->return_source;
                $this->data['return_status'] = $source[0]->return_status;
                $this->data['remarks'] = $source[0]->remarks;
                $this->data['tds_applicable'] = $source[0]->tds_applicable;
                $this->data['tds_prcnt'] = $source[0]->tds_prcnt;
                $this->data['tds_amount'] = $source[0]->tds_amount;
                $this->data['organization_id'] = $this->jcombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));

                $this->data['reference_source_id'] = $source[0]->reference_source_id;
                if ($source[0]->return_source == "INVOICE") {
                    $salesinvoice = Salesinvoice::find($source[0]->reference_source_id);
                    $soorder = new SoorderController();

                    $address = $soorder->getaddress($salesinvoice->ship_to_customer_id);
                    $billto_add_id = explode('~', $address[0]);
                    $shipto_add_id = explode('~', $address[1]);
                    $this->data['bill_to_address_id'] = $billto_add_id[1];
                    $this->data['ship_to_address_id'] = $shipto_add_id[1];
                    $this->data['bill_to_address'] = $billto_add_id[0];
                    $this->data['ship_to_address'] = $shipto_add_id[0];
                } else {

                    $soorder = new SalesreturnController();
                    $address = $soorder->getaddress($source[0]->customerid);
                    $billto_add_id = explode('~', $address[0]);
                    $shipto_add_id = explode('~', $address[1]);
                    $this->data['bill_to_address_id'] = $billto_add_id[1];
                    $this->data['ship_to_address_id'] = $shipto_add_id[1];
                    $this->data['bill_to_address'] = $billto_add_id[0];
                    $this->data['ship_to_address'] = $shipto_add_id[0];

                }
                $this->data['reference_no'] = $source[0]->reference_no;

                $lines = \DB::table('so_rma_lines_t')->select('so_rma_lines_t.*')
                    ->where('so_rma_lines_t.so_rma_hdr_id', $id)->get();
                $this->data['linedata'] = $lines;

                foreach ($lines as $key => $value) {
                    $this->data['linedata'][$key]->reason_comments = $value->reason_comments;
                    $this->data['linedata'][$key]->product_id = $this->jCombocomp('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id);
                    //dd($this->data['linedata'][$key]->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                    $this->data['linedata'][$key]->invoice_qty = $value->invoice_qty;
                    $this->data['linedata'][$key]->return_qty = $value->return_qty;
                    //  dd($value);  
                    $this->data['linedata'][$key]->returned_qty = $value->returned_qty;
                    $this->data['linedata'][$key]->batch_number = $value->batch_number;
                    $this->data['linedata'][$key]->buy_discount = $value->buy_discount;
                    $this->data['linedata'][$key]->discount_amount = $value->discount_amount;
                    //dd((($value->return_qty*$value->rate)*($value->buy_discount/100)));
                    $this->data['linedata'][$key]->taxgroup_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
                    if ($value->manufracture_date != '0000-00-00') {
                        //$this->data['linedata'][$key]->manufracture_date =date($sesdate,strtotime($value->manufracture_date));
                        $this->data['linedata'][$key]->manufracture_date = $value->manufracture_date;
                    } else {
                        $this->data['linedata'][$key]->manufracture_date = '';
                    }
                    if ($value->expiry_date != '0000-00-00') {
                        $this->data['linedata'][$key]->expiry_date == date($sesdate, strtotime($value->expiry_date));
                    } else {
                        $this->data['linedata'][$key]->expiry_date = '';
                    }

                    $this->data['linedata'][$key]->rate = $value->rate;
                }

            }


            $decimal = \Session::get('decimal');

        }
        return view('salesreturn.form', $this->data);
    }

    public function save(Request $request)
    {
        
        $id = '';
        $form = $request->all();
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'customer_site_id',
            'customer_number',
            'customer_name',
            'customer_type',
            'choosefile',
            'existing_file',
            'billing_to_address_txt',
            'shipping_to_address_txt',
            'enable-masterdetail',
            'customer_id',
            'pincode',
            'customer_site_name',
            'site_type',
            'address',
            'city_name',
            'state_name',
            'country_name',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');

        if ($_POST['rma_ref_no'] == "") {
            $seqno = $this->Seqnoe('SOR', 'so_rma_hdr_t', '', 'rma_count');
            $data['rma_ref_no'] = ltrim($seqno[0]);
            $data['rma_count'] = $seqno[1];
        } else {
            $seqno = ltrim($_POST['rma_ref_no']);
        }
        \DB::beginTransaction();
        try {
            $data['return_date'] = date("Y-m-d", strtotime($data['return_date']));
            $data['balance_amount'] = $_POST['total_amount'];
            foreach ($_POST['bulk_product_id'] as $key => $value) {
                if ($lines_data['manufracture_date'][$key] != '') {
                    //$lines_data['manufracture_date'][$key]=date("Y-m-d",strtotime($lines_data['manufracture_date'][$key]));
                    $lines_data['manufracture_date'][$key] = $lines_data['manufracture_date'][$key];
                } else {
                    $lines_data['manufracture_date'][$key] = '';
                }
                if ($lines_data['expiry_date'][$key] != '') {
                    $lines_data['expiry_date'][$key] = date("Y-m-d", strtotime($lines_data['expiry_date'][$key]));
                } else {
                    $lines_data['expiry_date'][$key] = '';
                }

            }
   
            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            $source = \DB::table('so_rma_hdr_t')->select('return_source')->where('so_rma_hdr_id', $id)->get();

            if ($_POST['return_source'] != "DIRECT" && ($_POST['return_status'] != 'APPROVED' && $_POST['return_status'] != 'REJECTED')) {

                $update_invocie = \DB::SELECT("select sum(return_qty) as return_qty,so_rma_lines_t.invoice_qty from so_rma_hdr_t  left join so_rma_lines_t on so_rma_lines_t.so_rma_hdr_id=so_rma_hdr_t.so_rma_hdr_id where so_rma_hdr_t.reference_source_id=" . $_POST['reference_source_id'] . " group by so_rma_lines_t. product_id");
                //    dd($update_invocie);
                $check = 0;
                foreach ($update_invocie as $k => $v) {
                    if ($v->invoice_qty != $v->return_qty) {
                        $check++;
                    }
                }
                if ($check == 0) {
                    \DB::update("update s_invoice_hdr_t set return_status=1 where invoice_hdr_id=" . $_POST['reference_source_id']);
                }

            }
            /*deepika purpose:journal entry*/
            if ($_POST['return_status'] == "APPROVED") {
                $rmano = $data['rma_ref_no'];
                $rmano1 = trim($rmano);
                $returndate = $data['return_date'];
                $org = \Session::get('organization');
                $loc = \Session::get('location');
                $compy = \Session::get('companyid');

                if (isset($id)) {
                    //Journal Header Insert for sales return
                    $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$rmano1','SALES RETURN','$returndate','$id','APPROVED','$compy','$loc','$org')");
                    $jid = DB::getPdo()->lastInsertId();

                    //dd($_POST);
                    if ($_POST['invoice_currency'] == 37) {
                        $convertion_value = 1;
                    } else {
                        $data = \DB::table('f_account_exchangerates_t')->whereDate('from_date', '<=', $returndate)->whereDate('to_date', '>=', $returndate)->where('from_currency_id', $_POST['invoice_currency'])->where('to_currency_id', 37)->where('active', 'Yes')->where('company_id', $compy)->select('*')->get();
                        if (count($data) > 0) {
                            $convertion_value = $data[0]->conversion_rate;
                        }
                    }
                    //get values
                    $tds_applicable = $_POST['tds_applicable'];
                    $tds_account = $_POST['tds_account_id'];
                    $tds_amt = $_POST['tds_amount'];
                    $cid = $_POST['bill_to_address_id'];
                    $custid = $_POST['customerid'];
                    $custable = \DB::table('m_customers_t')->join('m_customer_types_t', 'm_customer_types_t.customer_type_id', '=', 'm_customers_t.customer_type_id')->where('customer_id', '=', $custid)->select('m_customers_t.account_structure_id', 'm_customer_types_t.account_id')->get();

                    $account_structure_id = $custable[0]->account_structure_id;


                    $accntsettings = \DB::table('f_account_setting_t')->where('module_name', '=', 'salesaccount')->select('sales_account_id', 'cogs_account_id')->get();

                    $sales_account_id = $custable[0]->account_id;
                    $cogs_account_id = $accntsettings[0]->cogs_account_id;
                    $tkey = 0;
                    //dd($_POST['bulk_rate']);

                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $returndate;
                    $journal_lines_data[$tkey]['reference_source'] = "CUSTOMER";
                    $journal_lines_data[$tkey]['reference_id'] = $custid;
                    $journal_lines_data[$tkey]['product_qty'] = '';
                    $journal_lines_data[$tkey]['batch_number'] = '';
                    $journal_lines_data[$tkey]['account_id'] = $account_structure_id;
                    $journal_lines_data[$tkey]['debit_amount'] = '';
                    $journal_lines_data[$tkey]['credit_amount'] = floatval($_POST['total_amount']) * $convertion_value;
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    foreach ($_POST['bulk_product_id'] as $key => $value) {
                        $disc_acc_code = \DB::table('m_products_t')->where('product_id', '=', $value)->select('disc_account_code')->get();
                        $disc_acc_id = $disc_acc_code[0]->disc_account_code;
                        $qty = $_POST['bulk_return_qty'][$key];
                        $batch = $_POST['bulk_batch_number'][$key];
                        if (($_POST['bulk_discount_amount'][$key] != '' && $_POST['bulk_discount_amount'][$key] > 0)) {
                            $tkey++;

                            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                            $journal_lines_data[$tkey]['journal_date'] = $returndate;
                            $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
                            $journal_lines_data[$tkey]['reference_id'] = $value;
                            $journal_lines_data[$tkey]['product_qty'] = $qty;
                            $journal_lines_data[$tkey]['batch_number'] = $batch;
                            $journal_lines_data[$tkey]['account_id'] = $disc_acc_id;

                            $journal_lines_data[$tkey]['debit_amount'] = '';
                            $journal_lines_data[$tkey]['credit_amount'] = $_POST['bulk_discount_amount'][$key] * $convertion_value;
                            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                            $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                            $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                            ;
                            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        }
                    }


                    if ($_POST['tds_applicable'] != "NO") {
                        $tkey++;
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $returndate;
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = "";
                        $journal_lines_data[$tkey]['product_qty'] = '';
                        $journal_lines_data[$tkey]['batch_number'] = '';
                        $journal_lines_data[$tkey]['account_id'] = $_POST['tds_account_id'];
                        $journal_lines_data[$tkey]['debit_amount'] = $_POST['tds_amount'] * $convertion_value;
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    }
                    $tax_details = array();
                    foreach ($_POST['bulk_product_id'] as $key => $value) {
                        //dd($_POST['bulk_qty']);
                        $tot = $_POST['bulk_return_qty'][$key] * $_POST['bulk_rate'][$key];
                        $qty = $_POST['bulk_return_qty'][$key];
                        $batch = $_POST['bulk_batch_number'][$key];
                        //  dd($tot);
                        $tkey++;
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $returndate;
                        $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
                        $journal_lines_data[$tkey]['reference_id'] = $value;
                        $journal_lines_data[$tkey]['product_qty'] = $qty;
                        $journal_lines_data[$tkey]['batch_number'] = $batch;
                        $journal_lines_data[$tkey]['account_id'] = $sales_account_id;
                        $journal_lines_data[$tkey]['debit_amount'] = $tot * $convertion_value;
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

                        $tax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $_POST['bulk_tax_group_id'][$key])->get();
                        foreach ($tax_details as $taxval) {
                            $tkey++;
                            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                            $journal_lines_data[$tkey]['journal_date'] = $returndate;
                            $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
                            $journal_lines_data[$tkey]['reference_id'] = $value;
                            $journal_lines_data[$tkey]['product_qty'] = $qty;
                            $journal_lines_data[$tkey]['batch_number'] = $batch;
                            $journal_lines_data[$tkey]['account_id'] = $taxval->output_tax_account_id;
                            $rate = $tot - $_POST['bulk_discount_amount'][$key];

                            $trade_discount = isset($_POST['trade_discount_pre']) && $_POST['trade_discount_pre'] !== '' ? $_POST['trade_discount_pre'] : 0;


                            $amount = round(($rate * $trade_discount) / 100, 2);
                            $rate = $rate - $amount;

                            $taxval = (float) $taxval->tax_code_percent;
                            $rate = round(($rate * $taxval) / 100, 2);


                            $journal_lines_data[$tkey]['debit_amount'] = $rate * $convertion_value;
                            $journal_lines_data[$tkey]['credit_amount'] = '';
                            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                            $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                            $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                            ;
                            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        }
                    }

                    $rw = 1;
                 
                }
            }
            /*end*/
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id, 'lid' => $lid));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();

            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }
    //public function getaddress(Request $request,$id =null)
    public function getaddress($id = null)
    {
        // dd("fgf");
        $SQL = "SELECT m_customer_sites_t.site_type,m_customer_sites_t.address,m_customer_sites_t.contact_number,m_customer_sites_t.customer_site_id,m_customer_sites_t.country,m_customer_sites_t.state,m_customer_sites_t.city
        ,m_customer_sites_t.customer_site_name,m_customer_sites_t.pincode,m_countries_t.country_name,m_states_t.state_name,m_cities_t.city_name
        FROM m_customers_t 
        inner join m_customer_sites_t ON
        m_customer_sites_t.customer_id=m_customers_t.customer_id
        join m_countries_t on m_countries_t.country_id = m_customer_sites_t.country
        join m_states_t on m_states_t.state_id = m_customer_sites_t.state
        join m_cities_t on m_cities_t.city_id = m_customer_sites_t.city
        WHERE m_customer_sites_t.customer_site_id IN (
           SELECT MAX(customer_site_id)
           FROM m_customer_sites_t where customer_id ='$id'
           GROUP BY m_customer_sites_t.site_type
        )";
        $result = \DB::select($SQL);
        $output = array();

        if (count($result) > 0 && count($result) == 1) {
            foreach ($result as $key => $value) {
                if ($value != '') {
                    if ($value->site_type == "BILL_TO") {
                        $output[0] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
                        $output[1] = '';
                    } else if ($value->site_type == "SHIP_TO") {
                        $output[0] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
                        $output[1] = '';
                    } else {
                        $output[0] = '';
                        $output[1] = '';
                    }
                }
            }

        } else if (count($result) > 0 && count($result) == 2) {
            foreach ($result as $key => $value) {
                if ($value != '') {
                    if ($value->site_type == "BILL_TO") {
                        $output[$key] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
                    } else if ($value->site_type == "SHIP_TO") {
                        $output[$key] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
                    }

                }
            }

        } else if (count($result) > 2) {
            foreach ($result as $key => $value) {
                if ($value != '') {
                    if ($value->site_type == "BILL_TO") {
                        $output[$key] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
                    } else if ($value->site_type == "SHIP_TO") {
                        $output[$key] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
                    }

                }
            }
        } else {
            $output[0] = '';
            $output[1] = '';
        }
        return $output;


    }


    public function show($id)
    {
        $headerdata = \DB::SELECT("SELECT so_rma_hdr_t.*,s_invoice_hdr_t.invoice_number,m_customers_t.customer_name,f_account_structure_t.concatenated_segments,f_account_currency_t.currency_code FROM `so_rma_hdr_t` left join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=so_rma_hdr_t.reference_source_id left join m_customers_t on m_customers_t.customer_id=so_rma_hdr_t.customerid  left join f_account_structure_t on so_rma_hdr_t.tds_account_id=f_account_structure_t.f_account_structure_id
            left join f_account_currency_t on so_rma_hdr_t.invoice_currency =f_account_currency_t.account_currency_id where so_rma_hdr_t.so_rma_hdr_id=$id   ");
        $linesdata = \DB::table('so_rma_hdr_t as ih')
            ->leftjoin('so_rma_lines_t as il', 'ih.so_rma_hdr_id', '=', 'il.so_rma_hdr_id')
            ->leftjoin('m_products_t as pr', 'il.product_id', '=', 'pr.product_id')
            ->leftjoin('m_uom_codes_t as uom', 'il.uom_code_id', '=', 'uom.uom_code_id')
            ->leftjoin('m_tax_group_t', 'il.tax_group_id', '=', 'm_tax_group_t.tax_group_id')
            ->select('pr.concatenated_product', 'uom.uom_code', 'm_tax_group_t.tax_group_name', 'il.*', 'ih.*')
            ->where('ih.so_rma_hdr_id', $id)
            ->get();
        $this->data['headerdata'] = $headerdata[0];
        $custaddress1 = new SoorderController;
        $this->data['bill_to_address'] = $custaddress1->sobilladdress($headerdata[0]->bill_to_address_id);
        $this->data['ship_to_address'] = $custaddress1->soshipaddress($headerdata[0]->ship_to_address_id);
        $this->data['vlinesdata'] = $linesdata;

        return view('salesreturn.view', $this->data);
    }

    public function salesviewdata($id)
    {


    }
}
