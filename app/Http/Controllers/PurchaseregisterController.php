<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Purchaseregister;
use Illuminate\Http\Request;

class PurchaseregisterController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }

    public function index()
    {
        // dd($this->data);
        return view('purchaseregister.table', $this->data);
    }

    /* Purpose For Purchase Register*/
    public function getpurchaseregister(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $SQL = "SELECT * from (
    SELECT p_po_invoice_hdr_t.*,
                SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',-1) as other_tax_amt,
                round((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',-1)),2) as other_tax_value,
                other_tax_group_t.tax_group_name as other_tax_group_name,
                SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',-1) as transport_tax_amt,
                round((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',-1)),2) as transport_tax_value,
                transport_tax_group_t.tax_group_name as transport_tax_group_name,
                       (p_po_invoice_lines_t.line_total)-(p_po_invoice_lines_t.tax_amount) as subtotal,
                        m_supplier_t.supplier_name,
                        m_supplier_sites_t.gst_number as gst_number,
                        p_po_hdr_t.po_number as ponumber,
                        REPLACE(SUBSTRING_INDEX(p_po_hdr_t.po_number,'/',1),'APO-','') as finance_yr,
                        p_po_invoice_lines_t.qty as p_qty,
                        p_po_invoice_lines_t.unit_price as rate,
                        p_grn_hdr_t.grn_number as grnno,
                        p_grn_hdr_t.grn_date,
                        p_po_lines_t.promised_date,
                        MONTHNAME(p_grn_hdr_t.grn_date) as grnmonth,
                        CONCAT(LEFT(MONTHNAME(p_grn_hdr_t.grn_date),3),'-',YEAR(p_grn_hdr_t.grn_date))as monyr,
                       DATEDIFF(p_po_hdr_t.po_date,p_grn_hdr_t.grn_date) as LeadDay,
                        DATEDIFF(p_grn_hdr_t.grn_date,p_po_lines_t.promised_date)as LeadTime,
                        f_gst_code_hdr_t.classification_code,
                        m_tax_group_t.tax_group_name as tax_group_name,
                        m_products_t.concatenated_product,
                        m_products_t.tax_credit,
                        m_product_subcategory_t.subcategory_name,
                        m_product_category_t.category_name,
                        p_po_invoice_lines_t.line_total,
                        m_uom_codes_t.uom_code,
                        m_product_groups_t.group_name,
                        p_po_invoice_lines_t.tax_amount,
                        (case WHEN p_po_invoice_hdr_t.reverse_charge='1' THEN 'Yes' ELSE 'No' END ) as rcm,
                        tb_users.first_name
                        FROM p_po_invoice_hdr_t
                        left join p_po_invoice_lines_t on (p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id)
                        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                        left join m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
                        left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id =p_po_invoice_hdr_t.po_number)
                       left join p_po_lines_t on (p_po_invoice_hdr_t.po_number =p_po_lines_t.po_hdr_id and p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
                        left join p_grn_hdr_t on (p_grn_hdr_t.grn_id =p_po_invoice_hdr_t.grn_number)
                        left join f_gst_code_hdr_t on (f_gst_code_hdr_t.gst_code_hdr_id =p_po_invoice_lines_t.hsn_code)
                        left join m_tax_group_t on (m_tax_group_t.tax_group_id =p_po_invoice_lines_t.tax_group_id)
                        left join m_tax_group_t as other_tax_group_t on (other_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',1))
                        left join m_tax_group_t as transport_tax_group_t on (transport_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',1))
                        left join m_products_t on (m_products_t.product_id =p_po_invoice_lines_t.product_id)
                        left join m_product_subcategory_t on (m_product_subcategory_t.product_subcategory_id =m_products_t.product_subcategory_id)
                        left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id)
                        left join m_product_category_t on (m_product_category_t.product_category_id =m_products_t.product_category_id)
                        left join m_uom_codes_t on (m_uom_codes_t.uom_code_id =p_po_invoice_lines_t.uom_code_id)
                        LEFT JOIN tb_users ON (tb_users.id = p_po_invoice_hdr_t.created_by)
                        where 1=1  and p_po_invoice_hdr_t.po_invoice_status='APPROVED' and (m_tax_group_t.tax_group_name!='' or m_tax_group_t.tax_group_name IS NOT NULL) and date(p_po_invoice_hdr_t.invoice_date) BETWEEN  ? and ?) AS v1";

        $result = \DB::select($SQL, [$start_date, $end_date]);

        foreach ($result as $key => $value) {

            $result[$key]->tax_amount = (round($result[$key]->tax_amount, 2));
            $first = substr($result[$key]->tax_group_name, 0, 3);
            $result[$key]->sub_total = (round($result[$key]->subtotal, 2));
            $result[$key]->cgst = 0;
            $result[$key]->igst = 0;
            $result[$key]->sgst = 0;
            if ($first == "GST") {
                $rate1 = ($result[$key]->tax_amount / 2);
                $rate = (round($rate1, 2));
                $result[$key]->cgst = $rate;
                $result[$key]->sgst = $rate;
                $result[$key]->igst = 0;
            } elseif ($first == "IGS") {
                $result[$key]->igst = (round($result[$key]->tax_amount, 2));
                $result[$key]->cgst = 0;
                $result[$key]->sgst = 0;
            }

        }

        return response()->json(['data' => $result]);
    }


    public function purchaseregistertdsindex()
    {

        return view('purchaseregister.tdstable', $this->data);
    }

    /* Purpose For Purchase Register*/
    public function getpurchaseregistertds(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $SQL = "SELECT * from (
 SELECT p_po_invoice_hdr_t.*,
                SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',-1) as other_tax_amt,
                round((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',-1)),2) as other_tax_value,
                other_tax_group_t.tax_group_name as other_tax_group_name,
                SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',-1) as transport_tax_amt,
                round((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',-1)),2) as transport_tax_value,
                transport_tax_group_t.tax_group_name as transport_tax_group_name,
                       (p_po_invoice_lines_t.line_total)-(p_po_invoice_lines_t.tax_amount) as subtotal,
                        m_supplier_t.supplier_name,
                        m_supplier_sites_t.gst_number as gst_number,
                        p_po_hdr_t.po_number as ponumber,
                        REPLACE(SUBSTRING_INDEX(p_po_hdr_t.po_number,'/',1),'APO-','') as finance_yr,
                        p_po_invoice_lines_t.qty as p_qty,
                        p_po_invoice_lines_t.unit_price as rate,
                        p_grn_hdr_t.grn_number as grnno,
                        p_grn_hdr_t.grn_date,
                        p_po_lines_t.promised_date,
                        MONTHNAME(p_grn_hdr_t.grn_date) as grnmonth,
                        CONCAT(LEFT(MONTHNAME(p_grn_hdr_t.grn_date),3),'-',YEAR(p_grn_hdr_t.grn_date))as monyr,
                       DATEDIFF(p_po_hdr_t.po_date,p_grn_hdr_t.grn_date) as LeadDay,
                        DATEDIFF(p_grn_hdr_t.grn_date,p_po_lines_t.promised_date)as LeadTime,
                        f_gst_code_hdr_t.classification_code,
                        m_tax_group_t.tax_group_name as tax_group_name,
                        m_products_t.concatenated_product,
                        m_product_subcategory_t.subcategory_name,
                        m_product_category_t.category_name,
                        p_po_invoice_lines_t.line_total,
                        m_uom_codes_t.uom_code,
                        m_product_groups_t.group_name,
                        p_po_invoice_lines_t.tax_amount,
                        f_account_structure_t.concatenated_segments as tds_account_name
                        FROM p_po_invoice_hdr_t
                        left join p_po_invoice_lines_t on (p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id)
                        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                        left join m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
                        left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id =p_po_invoice_hdr_t.po_number)
                       left join p_po_lines_t on (p_po_invoice_hdr_t.po_number =p_po_lines_t.po_hdr_id and p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
                        left join p_grn_hdr_t on (p_grn_hdr_t.grn_id =p_po_invoice_hdr_t.grn_number)
                        left join f_gst_code_hdr_t on (f_gst_code_hdr_t.gst_code_hdr_id =p_po_invoice_lines_t.hsn_code)
                        left join m_tax_group_t on (m_tax_group_t.tax_group_id =p_po_invoice_lines_t.tax_group_id)
                        left join m_tax_group_t as other_tax_group_t on (other_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',1))
                        left join m_tax_group_t as transport_tax_group_t on (transport_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',1))
                        left join m_products_t on (m_products_t.product_id =p_po_invoice_lines_t.product_id)
                        left join m_product_subcategory_t on (m_product_subcategory_t.product_subcategory_id =m_products_t.product_subcategory_id)
             left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id)
                        left join m_product_category_t on (m_product_category_t.product_category_id =m_products_t.product_category_id)
                        left join m_uom_codes_t on (m_uom_codes_t.uom_code_id =p_po_invoice_lines_t.uom_code_id)
                        LEFT JOIN f_account_structure_t ON(f_account_structure_t.f_account_structure_id = p_po_invoice_hdr_t.tds_account_id)
                        where 1=1  and p_po_invoice_hdr_t.po_invoice_status='APPROVED' and p_po_invoice_hdr_t.tds_applicable = 'YES' and (m_tax_group_t.tax_group_name!='' or m_tax_group_t.tax_group_name IS NOT NULL) and date(p_po_invoice_hdr_t.po_date) BETWEEN  ? and ?) AS v1";

        $result = \DB::select($SQL, [$start_date, $end_date]);

        foreach ($result as $key => $value) {

            $result[$key]->tax_amount = (round($result[$key]->tax_amount, 2));
            $first = substr($result[$key]->tax_group_name, 0, 3);
            $result[$key]->sub_total = (round($result[$key]->subtotal, 2));
            $result[$key]->cgst = 0;
            $result[$key]->igst = 0;
            $result[$key]->sgst = 0;
            if ($first == "GST") {
                $rate1 = ($result[$key]->tax_amount / 2);
                $rate = (round($rate1, 2));
                $result[$key]->cgst = $rate;
                $result[$key]->sgst = $rate;
                $result[$key]->igst = 0;
            } elseif ($first == "IGS") {
                $result[$key]->igst = (round($result[$key]->tax_amount, 2));
                $result[$key]->cgst = 0;
                $result[$key]->sgst = 0;
            }

        }

        return response()->json(['data' => $result]);
    }


    public function credittakenindex()
    {

        return view('purchaseregister.credittakentable', $this->data);
    }



    public function getcredittaken(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $SQL = "SELECT *From(SELECT p_po_invoice_hdr_t.*,
                SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',-1) as other_tax_amt,
                round((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',-1)),2) as other_tax_value,
                other_tax_group_t.tax_group_name as other_tax_group_name,
                SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',-1) as transport_tax_amt,
                round((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',-1)),2) as transport_tax_value,
                transport_tax_group_t.tax_group_name as transport_tax_group_name,
                       (p_po_invoice_lines_t.line_total)-(p_po_invoice_lines_t.tax_amount) as subtotal,
                        m_supplier_t.supplier_name,
                        m_supplier_sites_t.gst_number as gst_number,
                        p_po_hdr_t.po_number as ponumber,
                        CONCAT(LEFT(MONTHNAME(p_po_invoice_hdr_t.credit_date),3),'-',YEAR(p_po_invoice_hdr_t.credit_date))as credit_month,
                        REPLACE(SUBSTRING_INDEX(p_po_hdr_t.po_number,'/',1),'APO-','') as finance_yr,
                        p_po_invoice_lines_t.qty as p_qty,
                        p_po_invoice_lines_t.unit_price as rate,
                        p_grn_hdr_t.grn_number as grnno,
                        p_grn_hdr_t.grn_date,
                        p_po_lines_t.promised_date,
                        MONTHNAME(p_grn_hdr_t.grn_date) as grnmonth,
                        CONCAT(LEFT(MONTHNAME(p_grn_hdr_t.grn_date),3),'-',YEAR(p_grn_hdr_t.grn_date))as monyr,
                       DATEDIFF(p_po_hdr_t.po_date,p_grn_hdr_t.grn_date) as LeadDay,
                        DATEDIFF(p_grn_hdr_t.grn_date,p_po_lines_t.promised_date)as LeadTime,
                        f_gst_code_hdr_t.classification_code,
                        m_tax_group_t.tax_group_name as tax_group_name,
                        m_products_t.concatenated_product,
                        m_product_subcategory_t.subcategory_name,
                        m_product_category_t.category_name,
                        p_po_invoice_lines_t.line_total,
                        m_uom_codes_t.uom_code,
                        m_product_groups_t.group_name,
                        p_po_invoice_lines_t.tax_amount              
                        FROM p_po_invoice_hdr_t
                        left join p_po_invoice_lines_t on (p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id)
                        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                        left join m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
                        left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id =p_po_invoice_hdr_t.po_number)
                       left join p_po_lines_t on (p_po_invoice_hdr_t.po_number =p_po_lines_t.po_hdr_id and p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
                        left join p_grn_hdr_t on (p_grn_hdr_t.grn_id =p_po_invoice_hdr_t.grn_number)
                        left join f_gst_code_hdr_t on (f_gst_code_hdr_t.gst_code_hdr_id =p_po_invoice_lines_t.hsn_code)
                        left join m_tax_group_t on (m_tax_group_t.tax_group_id =p_po_invoice_lines_t.tax_group_id)
                        left join m_tax_group_t as other_tax_group_t on (other_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',1))
                        left join m_tax_group_t as transport_tax_group_t on (transport_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',1))
                        left join m_products_t on (m_products_t.product_id =p_po_invoice_lines_t.product_id)
                        left join m_product_subcategory_t on (m_product_subcategory_t.product_subcategory_id =m_products_t.product_subcategory_id)
             left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id)
                        left join m_product_category_t on (m_product_category_t.product_category_id =m_products_t.product_category_id)
                        left join m_uom_codes_t on (m_uom_codes_t.uom_code_id =p_po_invoice_lines_t.uom_code_id)
                        where 1=1  and p_po_invoice_hdr_t.po_invoice_status='APPROVED' and p_po_invoice_hdr_t.credit_taken='Yes' and (m_tax_group_t.tax_group_name!='' or m_tax_group_t.tax_group_name IS NOT NULL) and date(p_po_invoice_hdr_t.credit_date) BETWEEN  '$start_date' and '$end_date')v1";

        $result = \DB::select($SQL);

        foreach ($result as $key => $value) {

            $result[$key]->tax_amount = (round($result[$key]->tax_amount, 2));
            $first = substr($result[$key]->tax_group_name, 0, 3);
            $result[$key]->sub_total = (round($result[$key]->subtotal, 2));
            if ($first == "GST") {

                $rate1 = ($result[$key]->tax_amount / 2);
                $rate = (round($rate1, 2));
                $result[$key]->cgst = $rate;
                $result[$key]->sgst = $rate;
            } elseif ($first == "IGS") {
                $result[$key]->igst = (round($result[$key]->tax_amount, 2));
            }

        }

        return response()->json(['data' => $result]);
    }


}
