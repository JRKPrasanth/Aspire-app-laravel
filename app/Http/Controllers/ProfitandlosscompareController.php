<?php

namespace App\Http\Controllers;
use Log;
use App\Trialbalancesrpt;
use Illuminate\Http\Request;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
require 'vendor/autoload.php';


class ProfitandlosscompareController extends Controller
{
   public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }

	public function profitandlosscompare(){

          $this->data['ship_date'] = \DB::select("SELECT f_journal_entry_t.journal_date,f_journal_entry_lines_t.created_at FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id WHERE f_journal_entry_t.journal_name LIKE '%SHIP-SO%' ORDER BY f_journal_entry_t.journal_entry_id DESC LIMIT 1"); 
          
                $this->data['result']=[];
	        	return view('comparerpt.p&lcompare',$this->data);	
	
	}
	
   	public function getprofitandlosscompare($start_date=null,$end_date=null){
   	    
         $id=0;

        if($start_date)
        {
        $id=1;
        }
        else
        {
        $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?   date("Y-m-d", strtotime($_GET['start_date'])) : '';
        $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
        }
       // show previous year data purpose - VIGNESH M
        $year = date("Y", strtotime($_GET['start_date']));
        $fy_year = $year - 1 ;
        $eyear = date("Y", strtotime($_GET['end_date']));
        $fy_eyear = $eyear - 1 ;

        $pre_fysdate = "$fy_year-04-01";
        $pre_fyedate = "$year-03-31" ;
        // END 
    // compare previous year till select date purpose    
    $sdate = date("m-d", strtotime($_GET['start_date'])); 
    $edate = date("m-d", strtotime($_GET['end_date'])); 
    
    $pre_sdate = $fy_year."-".$sdate;
    $pre_edate = $fy_eyear."-".$edate;
      //  dd($comedate);
        
    $SQL ="SELECT v4.*
FROM (SELECT
    v3.*,
    f_account_structure_t.main_account_id,
    f_account_structure_t.sub_account_id,
    f_account_structure_t.future_reference1,
    f_account_structure_t.future_reference2
FROM
    (
        (
        SELECT
            v1.account_id,
            v1.main_account_code,
            v1.main,
            v1.sub1,
            v1.sub2,
            v1.sub3,
            v1.sub4,
            ROUND(SUM(v1.amount1), 2) AS amount1,
            ROUND(SUM(v1.amount2), 2) AS amount2,
            ROUND(SUM(v1.amount3), 2) AS amount3
        FROM
            (
            SELECT
                f.f_account_structure_id AS account_id,
                m.main_account_code,
                m.account_class_name AS main,
                r1.account_code_meaning AS sub1,
                IF(
                    f.future_reference1 > 0,
                    r2.account_code_meaning,
                    ''
                ) AS sub2,
                IF(
                    f.future_reference2 > 0,
                    r3.account_code_meaning,
                    ''
                ) AS sub3,
                IF(
                    f.sub_account4_id > 0,
                    r4.account_code_meaning,
                    ''
                ) AS sub4,
                0 AS amount1,
                0 AS amount2,
                0 AS amount3
            FROM
                f_account_structure_t AS f
            LEFT JOIN f_account_class_t AS m
            ON
                m.account_class_id = f.main_account_id
            LEFT JOIN f_account_codes_lines_t AS r1
            ON
                r1.account_codes_line_id = f.sub_account_id
            JOIN f_account_codes_lines_t AS r2
            ON
                r2.account_codes_line_id = f.future_reference1
            LEFT JOIN f_account_codes_lines_t AS r3
            ON
                r3.account_codes_line_id = f.future_reference2
            LEFT JOIN f_account_codes_lines_t AS r4
            ON
                r4.account_codes_line_id = f.sub_account4_id
            WHERE
                m.main_account_code = 600000
            UNION ALL
                (
                SELECT
                    f_journal_entry_lines_t.account_id,
                    f_account_class_t.main_account_code,
                    '' AS main,
                    '' AS sub1,
                    '' AS sub2,
                    '' AS sub3,
                    '' AS sub4,
                ROUND(SUM(CASE 
                    WHEN f_journal_entry_lines_t.journal_date BETWEEN '$pre_fysdate' AND '$pre_fyedate' 
                    THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
                    ELSE 0 END), 2) AS amount1,
            
                ROUND(SUM(CASE 
                    WHEN f_journal_entry_lines_t.journal_date BETWEEN '$pre_sdate' AND '$pre_edate' 
                    THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
                    ELSE 0 END), 2) AS amount2,
            
                ROUND(SUM(CASE 
                    WHEN f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date' 
                    THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
                    ELSE 0 END), 2) AS amount3
                FROM
                    `f_journal_entry_t`
                LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
                LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
                LEFT JOIN f_account_class_t ON f_account_class_t.account_class_id = f_account_structure_t.main_account_id
                WHERE
                    (
                        f_account_class_t.main_account_code = 600000
                    ) AND f_journal_entry_t.journal_date <= '2026-03-31'
                GROUP BY
                    f_journal_entry_lines_t.account_id
                ORDER BY
                    1
            )
        ) v1
    GROUP BY
        v1.account_id
    ORDER BY
        v1.main_account_code ASC
    )
UNION ALL
    (
    SELECT
        v2.account_id,
        v2.main_account_code,
        v2.main,
        v2.sub1,
        v2.sub2,
        v2.sub3,
        v2.sub4,
        ROUND(SUM(v2.amount1),2) AS amount1,
        ROUND(SUM(v2.amount2),2) AS amount2,
        ROUND(SUM(v2.amount3),2) AS amount3
    FROM
        (
        SELECT
            f.f_account_structure_id AS account_id,
            m.main_account_code,
            m.account_class_name AS main,
            r1.account_code_meaning AS sub1,
            IF(
                f.future_reference1 > 0,
                r2.account_code_meaning,
                ''
            ) AS sub2,
            IF(
                f.future_reference2 > 0,
                r3.account_code_meaning,
                ''
            ) AS sub3,
            IF(
                f.sub_account4_id > 0,
                r4.account_code_meaning,
                ''
            ) AS sub4,
            0 AS amount1,
            0 AS amount2,
            0 AS amount3
        FROM
            f_account_structure_t AS f
        LEFT JOIN f_account_class_t AS m
        ON
            m.account_class_id = f.main_account_id
        LEFT JOIN f_account_codes_lines_t AS r1
        ON
            r1.account_codes_line_id = f.sub_account_id
        JOIN f_account_codes_lines_t AS r2
        ON
            r2.account_codes_line_id = f.future_reference1
        LEFT JOIN f_account_codes_lines_t AS r3
        ON
            r3.account_codes_line_id = f.future_reference2
        LEFT JOIN f_account_codes_lines_t AS r4
        ON
            r4.account_codes_line_id = f.sub_account4_id
        WHERE
            m.main_account_code = 700000
        UNION ALL
            (
            SELECT
                f_journal_entry_lines_t.account_id,
                f_account_class_t.main_account_code,
                '' AS main,
                '' AS sub1,
                '' AS sub2,
                '' AS sub3,
                '' AS sub4,
        ROUND(SUM(CASE 
        WHEN f_journal_entry_lines_t.journal_date BETWEEN '$pre_fysdate' AND '$pre_fyedate' 
        THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
        ELSE 0 END), 2) * -1 AS amount1,

        ROUND(SUM(CASE 
            WHEN f_journal_entry_lines_t.journal_date BETWEEN '$pre_sdate' AND '$pre_edate' 
            THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
            ELSE 0 END), 2) * -1 AS amount2,
    
        ROUND(SUM(CASE 
            WHEN f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date' 
            THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
            ELSE 0 END), 2) * -1 AS amount3
            FROM
                `f_journal_entry_t`
            LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
            LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
            LEFT JOIN f_account_class_t ON f_account_class_t.account_class_id = f_account_structure_t.main_account_id
            WHERE
                (
                    f_account_class_t.main_account_code = 700000
                ) AND f_journal_entry_t.journal_date <= '2026-03-31'
            GROUP BY
                f_journal_entry_lines_t.account_id
            ORDER BY
                1
        )
    ) v2
GROUP BY
    v2.account_id
ORDER BY
    v2.main_account_code ASC
)
    ) v3
JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = v3.account_id
ORDER BY
    v3.main_account_code ASC)v4 GROUP BY v4.account_id";

$result1 = \DB::select( $SQL );
if($id==1)
{
//dd($result1);
$result=collect($result1);

$lia=$result->where('main_account_code','like','600000');
$lia->all();
$lia= json_decode(json_encode($lia));

$arr_new1 = array_sum(array_column($lia, 'amount1'));
$arr_new2= array_sum(array_column($lia, 'amount2'));
$arr_new3 = array_sum(array_column($lia, 'amount3'));



$arr_new_1 = array_sum(array_column($result1, 'amount1'));
$arr_new_2 = array_sum(array_column($result1, 'amount2'));
$arr_new_3 = array_sum(array_column($result1, 'amount3'));

$sum1 = $arr_new1-($arr_new_1-$arr_new1);
$sum2 = $arr_new1-($arr_new_2-$arr_new2);
$sum3 = $arr_new1-($arr_new_3-$arr_new3);

return $sum1;
return $sum2;
return $sum3;
}


if(isset($_GET['download']))
{
 $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    $data=''; 

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=data.xls');
$output = fopen('profitandlosscompare.xls', 'w');     

$rows=array();
$rows="Account Class\t";
$rows.="Account Type\t";
$rows.="Sub1\t";
$rows.="Sub2\t";
$rows.="Sub3\t";
$rows.="Sub4\t";
$rows.="Amount(Rs)($fy_year - $year)\t";
$rows.="Amount(Rs)($pre_sdate -  $pre_edate)\t";
$rows.="Amount(Rs)($start_date - $end_date)\n";

//fputcsv($output, $rows); 

foreach($result1 as  $row)
{
// $rows=array();
$rows.=$row['main_account_code']."\t";
$rows.=$row['main']."\t";
$rows.=$row['sub1']."\t";
$rows.=$row['sub2']."\t";
$rows.=$row['sub3']."\t";
$rows.=$row['sub4']."\t";
$rows.=$row['amount1']."\t";
$rows.=$row['amount2']."\t";
$rows.=$row['amount3']."\n";

//fputcsv($output, $rows); 

}
fwrite($output,$rows);

return 1;
}



$result=collect($result1);


$data=\DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (3,9)");

setlocale(LC_MONETARY, 'en_IN');
$total1=0;$total2=0;$total3=0;
error_reporting(0);
$html='<table class="table table-bordered table-striped table-hover align-middle" style="    width: 100%;"><thead><th>Particulars</th><th>Amount (Rs) (' . $fy_year . ' - ' . $year . ')</th><th>Amount (Rs) (' . $pre_sdate . ' - ' . $pre_edate . ')</th><th style="text-align: end !important;background:#3f8519">Amount (Rs) (' . $start_date . ' - ' . $end_date . ')</th></thead><tbody>';
foreach($data as $k=>$val)                                                                        
{


$id=$val->account_class_id;
$name0=ucwords(strtolower($val->account_class_name));

$filter=$result->where('main_account_id',$id);
$filter->all();
$filter0=collect($filter);
$filter=collect($filter)->map(function($x){ return (array) $x; })->toArray();

$arr_new1= round(array_sum(array_column($filter, 'amount1')),2);
$arr_new2= round(array_sum(array_column($filter, 'amount2')),2);
$arr_new3= round(array_sum(array_column($filter, 'amount3')),2);

if($arr_new1 ==0)
    $arr_new1='-';
//dd($arr_new2);
$html.="<tr class='heading'><td><b>$name0</b></td><td></td><td></td><td></td></tr><tr><td></td><td></td></tr>";

$sub1=\DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");

foreach($sub1 as $val1)
{
    $p_id=$id=$val1->account_codes_line_id;
    $name1=ucwords(strtolower($val1->account_code_meaning)); 
    $code1= $val1->account_code;
    $filter1=$filter0->where('sub_account_id',$id);
    $filter1->all();
    $filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new_1 = round(array_sum(array_column($filter1, 'amount1')),2);
    $arr_new_2 = round(array_sum(array_column($filter1, 'amount2')),2);
    $arr_new_3 = round(array_sum(array_column($filter1, 'amount3')),2);

    if($arr_new1 ==0 )
    $arr_new_1='-';
    
    $html.="<tr><td style='cursor: pointer;' class=parent' data='$id' col='0'>$code1  $name1</td>
    <td style='
    text-align: right;cursor: pointer;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new_1,2))."</td>
<td style='
    text-align: right;cursor: pointer;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new_2,2))."</td>
<td style='
    text-align: right;cursor: pointer;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new_3,2))."</td></tr>";

//Sub2 
$sub2=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
if(count($sub2)>0){
foreach($sub2 as $val2)
{
  $p_id1=  $id=$val2->account_codes_line_id;
    $name2=ucwords(strtolower($val2->account_code_meaning)); 
    $code2= $val2->account_code;
    $filter1=$filter11->where('future_reference1',$id);
    $filter1->all();
    //$filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new2_1 = round(array_sum(array_column($filter1, 'amount1')),2);
    $arr_new2_2 = round(array_sum(array_column($filter1, 'amount2')),2);
    $arr_new2_3 = round(array_sum(array_column($filter1, 'amount3')),2);
    if($arr_new2_1==0)
    $arr_new2_1='-';
    
    $html.="<tr class='child child$p_id'><td class='parent' data='$id' col='0' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td>
    <td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new2_1,2))."</td>
    <td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new2_2,2))."</td>
    <td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new2_3,2))."</td></tr>";
//Sub3 

$sub3=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
if(count($sub3)>0){
foreach($sub3 as $val3)
{
    
    $id=$val3->account_codes_line_id;
    $p_id2=$val3->account_codes_line_id;
    $name3=ucwords(strtolower($val3->account_code_meaning)); 
    $code3= $val3->account_code;
    $filter1=$filter11->where('future_reference2',$id);
    $filter1->all();
    //$filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new2_1 = round(array_sum(array_column($filter1, 'amount1')),2);
    $arr_new2_2 = round(array_sum(array_column($filter1, 'amount2')),2);
    $arr_new2_3 = round(array_sum(array_column($filter1, 'amount3')),2);
    if($arr_new2_1==0)
    $arr_new2_1='-';
    
    $html.="<tr class='child child$p_id1'><td class='parent' data='$id' col='0' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td>
    <td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new2_1,2))."</td>
<td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new2_2,2))."</td>
<td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new2_3,2))."</td></tr>";

foreach($filter1 AS $val4){
 $accid=$val4['account_id'];   
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4['account_id']);
$accountname=$accn[0]->account_name;
$amount4=$val4['amount1'];
$amount5=$val4['amount2'];
$amount6=$val4['amount3'];
 $html.="<tr class='child child$p_id2'><td><a style='cursor: pointer;' class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a>
 </td><td style='
    text-align: right;
'>".number_format($amount4, 2)."</td>
<td style='
    text-align: right;
'>".number_format($amount5, 2)."</td><td></td>
<td style='
    text-align: right;
'>".number_format($amount6, 2)."</td><td></td></tr>";

}
}
}else{
      foreach($filter1 AS $val4){
$accid=$val4['account_id'];
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4['account_id']);
$accountname=$accn[0]->account_name;
$amount4=$val4['amount1'];
$amount5=$val4['amount2'];
$amount6=$val4['amount3'];
 $html.="<tr class='child child$id'><td><a style='cursor: pointer;' class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td>
 <td style='
    text-align: right;cursor: pointer;
'>".number_format($amount4, 2)."</td>
 <td style='
    text-align: right;cursor: pointer;
'>".number_format($amount5, 2)."</td>
 <td style='
    text-align: right;cursor: pointer;
'>".number_format($amount6, 2)."</td></tr>";

}  
}
}
}else{
      foreach($filter1 AS $val4){
$accid=$val4['account_id'];
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4['account_id']);
$accountname=$accn[0]->account_name;
$amount4=$val4['amount1'];
$amount5=$val4['amount2'];
$amount6=$val4['amount3'];
 $html.="<tr class='child child$id'><td><a style='cursor: pointer;' class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td>
 <td style='
    text-align: right;cursor: pointer;
'>".money_format('%!i',$amount4)."</td>
<td style='
    text-align: right;cursor: pointer;
'>".money_format('%!i',$amount5)."</td>
<td style='
    text-align: right;cursor: pointer;
'>".money_format('%!i',$amount6)."</td></tr>";

}
}
// $html.="<tr><td><b>$code1  $name1 Total</b></td><td ></td><td style='
// text-align: right;
// '>".money_format('%!i',$arr_new1)."</td></tr><tr><td></td><td></td><td></td></tr>";

}
$html.="<tr><td><b>$name0 Total</b></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new1,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new2,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new3,2))."</b></td></tr>";
//dd($arr_new1);
if($k==0)
{
    $total1 = $arr_new1;
    $total2 = $arr_new2;
    $total3 = $arr_new3;
}
else{
    $total1 = floatval($total1) - floatval($arr_new1);
    $total2 = floatval($total2) - floatval($arr_new2);
    $total3 = floatval($total3) - floatval($arr_new3);
}

}

$html.="<tr class='heading'><td><b>Net Income</b></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total1,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total2,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total3,2))."</b></td></tr>";

   return $html; 

    }
    
  // balance sheet comparison  
    
    public function balancesheetcompare(){
    
$this->data['ship_date'] = \DB::select("SELECT f_journal_entry_t.journal_date,f_journal_entry_lines_t.created_at FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id WHERE f_journal_entry_t.journal_name LIKE '%SHIP-SO%' ORDER BY f_journal_entry_t.journal_entry_id DESC LIMIT 1");
                
                // / dd($this->data['ship_date']);
                
                $this->data['result']=[];
                
      return view('comparerpt.balsheetcompare',$this->data);  
  }
  
  
    public function getbalancesheetcomparedata(){

    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?   date("Y-m-d", strtotime($_GET['start_date'])) : '';
    $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';

     // show previous year data purpose - VIGNESH M
        $year = date("Y", strtotime($_GET['start_date']));
        $fy_year = $year - 1 ;
        
     
        $pre_fysdate = "$fy_year-04-01";
        $pre_fyedate = "$year-03-31" ;
        // END 
    // compare previous year till select date purpose    
    $sdate = date("m-d", strtotime($_GET['start_date'])); 
    $edate = date("m-d", strtotime($_GET['end_date'])); 
    
    $pre_sdate = $fy_year."-".$sdate;
    $pre_edate = $fy_year."-".$edate;
      //  dd($comedate);
      
  //  $profit=$this->getprofitandloss($start_date,$end_date);

       // dd($profit);
        
    $SQL ="select account_id,main_account_code,main,sub1,sub2,sub3,sub4,
amount1,
amount2,
amount3,
  `main_account_id`,
  `sub_account_id`,
  `future_reference1`,
  `future_reference2`,
  `sub_account4_id` 
 from ((select v1.account_id,v1.main_account_code,v1.main,v1.sub1,v1.sub2,v1.sub3,v1.sub4,
 round(sum(v1.amount1),2)  as amount1,
  round(sum(v1.amount2),2)  as amount2,
   round(sum(v1.amount3),2)  as amount3,
 v1.`main_account_id`,
 v1.`sub_account_id`,
 v1.`future_reference1`,
 v1.`future_reference2`,
 v1.`sub_account4_id` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4,
         0 as amount1,
         0 as amount2,
         0 as amount3,
        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`
    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=100000 or m.main_account_code=200000 or m.main_account_code=300000  union all (SELECT 
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
        ROUND(SUM(CASE 
        WHEN f_journal_entry_lines_t.journal_date BETWEEN '$pre_fysdate' AND '$pre_fyedate' 
        THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
        ELSE 0 END), 2) AS amount1,

        ROUND(SUM(CASE 
            WHEN f_journal_entry_lines_t.journal_date BETWEEN '$pre_sdate' AND '$pre_edate' 
            THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
            ELSE 0 END), 2) AS amount2,
    
        ROUND(SUM(CASE 
            WHEN f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date' 
            THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
            ELSE 0 END), 2) AS amount3,
'' as `main_account_id`,
        '' as `sub_account_id`,
        '' as `future_reference1`,
        '' as `future_reference2`,
        '' as `sub_account4_id`           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=100000 or f_account_class_t.main_account_code=200000 or f_account_class_t.main_account_code=300000 )
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v1 group by v1.account_id ORDER by v1.main_account_code asc) union all (select v2.account_id,v2.main_account_code,v2.main,v2.sub1,v2.sub2,v2.sub3,v2.sub4,
            round(sum(v2.amount1),2)  as amount1, 
            round(sum(v2.amount2),2)  as amount2, 
            round(sum(v2.amount3),2)  as amount3, 
            v2.`main_account_id`,
            v2.`sub_account_id`,
            v2.`future_reference1`,
            v2.`future_reference2`,
            v2.`sub_account4_id` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 
        0 as amount1,
         0 as amount2,
          0 as amount3,

        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`

    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=400000 or m.main_account_code=500000 or m.main_account_code=800000  union all (SELECT
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
        ROUND(SUM(CASE 
        WHEN f_journal_entry_lines_t.journal_date BETWEEN '$pre_fysdate' AND '$pre_fyedate' 
        THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
        ELSE 0 END), 2) AS amount1,

        ROUND(SUM(CASE 
            WHEN f_journal_entry_lines_t.journal_date BETWEEN '$pre_sdate' AND '$pre_edate' 
            THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
            ELSE 0 END), 2) AS amount2,
    
        ROUND(SUM(CASE 
            WHEN f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date' 
            THEN f_journal_entry_lines_t.credit_amount - f_journal_entry_lines_t.debit_amount 
            ELSE 0 END), 2) AS amount3,
            '' as `main_account_id`,
            '' as `sub_account_id`,
            '' as `future_reference1`,
            '' as `future_reference2`,
            '' as `sub_account4_id` 
           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=400000 or f_account_class_t.main_account_code=500000 or f_account_class_t.main_account_code=800000 )
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v2 group by v2.account_id ORDER by v2.main_account_code asc) )v3 order by v3.main_account_code asc";

        $result = \DB::select( $SQL );


 if(isset($_GET['download']))
    {
         $result1=collect($result)->map(function($x){ return (array) $x; })->toArray();
            $data=''; 
  
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=data.xls');
 $output = fopen('balancesheetcompare.xls', 'w');     

$rows=array();
$rows="Account Class\t";
$rows.="Account Type\t";
$rows.="Sub1\t";
$rows.="Sub2\t";
$rows.="Sub3\t";
$rows.="Sub4\t";
$rows.="Amount(Rs)($fy_year - $year)\t";
$rows.="Amount(Rs)($pre_sdate -  $pre_edate)\t";
$rows.="Amount(Rs)($start_date - $end_date)\n";

 //fputcsv($output, $rows); 
 
      foreach($result1 as  $row)
{
   // $rows=array();
    $rows.=$row['main_account_code']."\t";
    $rows.=$row['main']."\t";
    $rows.=$row['sub1']."\t";
    $rows.=$row['sub2']."\t";
    $rows.=$row['sub3']."\t";
    $rows.=$row['sub4']."\t";
    $rows.=$row['amount1']."\t";
    $rows.=$row['amount2']."\t";
    $rows.=$row['amount3']."\n";
  
    //fputcsv($output, $rows); 
   
}
fwrite($output,$rows);
 
return 1;

    }
    
$result=collect($result);
$data=\DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (11,12,2) order by main_account_code asc");

setlocale(LC_MONETARY, 'en_IN');
$total=0;
error_reporting(0);
$html='<table class="table" style="width: 100%;"><thead><th>Particulars</th><th>Amount (Rs) (' . $fy_year . ' - ' . $year . ')</th><th>Amount (Rs) (' . $pre_sdate . ' - ' . $pre_edate . ')</th><th style="text-align: end !important;background:#9f3100">Amount (Rs) (' . $start_date . ' - ' . $end_date . ')</th></thead><tbody>';
$html.="<tr class='heading'><td><b>EQUITY & LIABILITIES</b></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";
foreach($data as $k=>$val)
{


$id=$val->account_class_id;
$name0=ucwords(strtolower($val->account_class_name));

$filter=$result->where('main_account_id',$id);
$filter->all();
$filter0=collect($filter);
$filter=collect($filter)->map(function($x){ return (array) $x; })->toArray();

$arr_new1= round(array_sum(array_column($filter, 'amount1')),2);
$arr_new2= round(array_sum(array_column($filter, 'amount2')),2);
$arr_new3= round(array_sum(array_column($filter, 'amount3')),2);
if($arr_new1==0)
    $arr_new1='-';

$html.="<tr class='heading'><td><b>$name0</b></td><td></td><td></td><td></td></tr><tr><td></td><td></td></tr>";

$sub1=\DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");


//dd($sub1);

foreach($sub1 as $val1)
{
    $p_id=$id=$val1->account_codes_line_id;
    $name1=ucwords(strtolower($val1->account_code_meaning)); 
    $code1= $val1->account_code;
    $filter1=$filter0->where('sub_account_id',$id);
    $filter1->all();
    $filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new1_1 = round(array_sum(array_column($filter1, 'amount1')),2);
    $arr_new1_2 = round(array_sum(array_column($filter1, 'amount2')),2);
    $arr_new1_3 = round(array_sum(array_column($filter1, 'amount3')),2);
    

    if($arr_new1_1==0)
    $arr_new1_1='-';
    
        $html.="<tr><td class='parent' data='$p_id' col='0'>$code1  $name1</td><td style='
text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1_1,2))."</td>
<td style='
text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1_2,2))."</td>
<td style='
text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1_3,2))."</td></tr>"; 

//Sub2 
$sub2=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");

 if(count($sub2)>0)
 {
//dd($filter11);
foreach($sub2 as $val2)
{
    $id=$subid=$val2->account_codes_line_id;
    $name2=ucwords(strtolower($val2->account_code_meaning)); 
    $code2= $val2->account_code;
    $filter1=$filter11->where('future_reference1',$id);
    $filter1->all();
    //$filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new2_1 = round(array_sum(array_column($filter1, 'amount1')),2);
    $arr_new2_2 = round(array_sum(array_column($filter1, 'amount2')),2);
    $arr_new2_3 = round(array_sum(array_column($filter1, 'amount3')),2);
    
    if($arr_new2_1==0)
    $arr_new2_1='-';
    
    
    
    $html.="<tr class='child child$p_id'><td  class='parent' data='$id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2_1,2))."</td>
<td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2_1,2))."</td>
<td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2_1,2))."</td></tr>";




 $sub3=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
 if(count($sub3)>0)
 {
 foreach($sub3 as $val3)
 {
//   // dd($val3);
   $id= $sub3id=$val3->account_codes_line_id;
      $name3=ucwords(strtolower($val3->account_code_meaning)); 
     $code3= $val3->account_code;
     $subfilter1=$filter11->where('future_reference2',$id);
     $subfilter1->all();
     $subfilter12=collect($subfilter1);
   $subfilter2= $subfilter1=collect($subfilter1)->map(function($x){ return (array) $x; })->toArray();
    
     $arr_new3_1 = round(array_sum(array_column($subfilter1, 'amount1')),2);
     $arr_new3_2 = round(array_sum(array_column($subfilter1, 'amount2')),2);
     $arr_new3_3 = round(array_sum(array_column($subfilter1, 'amount3')),2);
     if($arr_new3_1==0)
     $arr_new3_1='-';
   
   $html.="<tr class='child child$subid'><td class='parent' data='$sub3id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td><td style='
     text-align: right;
 '>".money_format('%!i',$arr_new3_1)."</td>
 <td style='
     text-align: right;
 '>".money_format('%!i',$arr_new3_2)."</td>
 <td style='
     text-align: right;
 '>".money_format('%!i',$arr_new3_3)."</td></tr>";
  

   foreach($subfilter12 AS $val4){
 $accid=$val4->account_id;   
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4->account_id);
$accountname=$accn[0]->account_name;
$amount4_1=$val4->amount1;
$amount4_2=$val4->amount2;
$amount4_3=$val4->amount3;
 $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>".money_format('%!i',$amount4_1)."</td>
<td style='
    text-align: right;
'>".money_format('%!i',$amount4_2)."</td>
<td style='
    text-align: right;
'>".money_format('%!i',$amount4_3)."</td></tr>";

}
 }
}
 else
 {
    foreach($filter11 AS $val4){
    
 $accountname=''; 
if($val4->account_id!=''){
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4->account_id);
$accountname=$accn[0]->account_name;
}
$amount4_1=$val4->amount1;
$amount4_2=$val4->amount2;
$amount4_3=$val4->amount3;
  $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>".money_format('%!i',$amount4_1)."</td>
<td style='
    text-align: right;
'>".money_format('%!i',$amount4_2)."</td>
<td style='
    text-align: right;
'>".money_format('%!i',$amount4_3)."</td></tr>";

} 
 }

}
}
 else
 {
    foreach($filter1 AS $val4){
    
   

$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4->account_id);
$accountname=$accn[0]->account_name;
 $amount4_1=$val4->amount1;
 $amount4_2=$val4->amount2;
 $amount4_3=$val4->amount3;
  $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
     text-align: right;
 '>".money_format('%!i',$amount4_1)."</td>
 <td style='
     text-align: right;
 '>".money_format('%!i',$amount4_2)."</td>
 <td style='
     text-align: right;
 '>".money_format('%!i',$amount4_3)."</td>
 </tr>";

}
 }
// $html.="<tr><td><b>$code1  $name1 Total</b></td><td ></td><td style='
// text-align: right;
// '>".money_format('%!i',$arr_new1)."</td></tr><tr><td></td><td></td><td></td></tr>";

}
$html.="<tr><td><b>$name0 Total</b></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new_1,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new_2,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new_3,2))."</b></td></tr>";

    $total1=$total1+$arr_new_1;
    $total2=$total2+$arr_new_2;
    $total3=$total3+$arr_new_3;


}

$html.="<tr class='heading'><td><b>EQUITY & LIABILITIES Total</b></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total1,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total2,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total3,2))."</b></td></tr>";



$data=\DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (10,5,13) order by main_account_code asc");
//dd($data);
setlocale(LC_MONETARY, 'en_IN');
$total=0;
error_reporting(0);

$html.="<tr class='heading'><td><b>ASSEST</b></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td></td><td></td></tr>";
foreach($data as $k=>$val)
{


$id=$val->account_class_id;
$name0=ucwords(strtolower($val->account_class_name));

$filter=$result->where('main_account_id',$id);
$filter->all();
$filter0=collect($filter);
$filter=collect($filter)->map(function($x){ return (array) $x; })->toArray();

$arr_new_1= round(array_sum(array_column($filter, 'amount1')),2);
$arr_new_2= round(array_sum(array_column($filter, 'amount2')),2);
$arr_new_3= round(array_sum(array_column($filter, 'amount3')),2);
if($arr_new_1==0)
    $arr_new_1='-';

$html.="<tr class='heading'><td><b>$name0</b></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td></td><td></td></tr>";

$sub1=\DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");

foreach($sub1 as $val1)
{
    $p_id=$id=$val1->account_codes_line_id;
    $name1=ucwords(strtolower($val1->account_code_meaning)); 
    $code1= $val1->account_code;
    $filter1=$filter0->where('sub_account_id',$id);
    $filter1->all();
    $filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new1_1 = round(array_sum(array_column($filter1, 'amount1')),2);
    $arr_new1_2 = round(array_sum(array_column($filter1, 'amount2')),2);
    $arr_new1_3 = round(array_sum(array_column($filter1, 'amount3')),2);

    if($arr_new1==0)
    $arr_new1='-';
    
    
 $html.="<tr><td class='parent' data='$p_id' col='0'>$code1  $name1</td><td style='
text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1_1,2))."</td>
<td style='
text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1_2,2))."</td>
<td style='
text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1_3,2))."</td></tr>"; 
//Sub2 
$sub2=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");

if(count($sub2)>0)
 {
foreach($sub2 as $val2)
{
    $id=$val2->account_codes_line_id;
    $name2=ucwords(strtolower($val2->account_code_meaning)); 
    $code2= $val2->account_code;
    $filter1=$filter11->where('future_reference1',$id);
    $filter1->all();
    //$filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new2_1 = round(array_sum(array_column($filter1, 'amount1')),2);
    $arr_new2_2 = round(array_sum(array_column($filter1, 'amount2')),2);
    $arr_new2_3 = round(array_sum(array_column($filter1, 'amount3')),2);
    if($arr_new2_1==0)
    $arr_new2_1='-';
    
    $html.="<tr class='child child$p_id'><td class='parent' data='$id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2_1,2))."</td>
<td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2_2,2))."</td>
<td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2_3,2))."</td></tr>";
$sub3=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
 if(count($sub3)>0)
 {
 foreach($sub3 as $val3)
 {
//   // dd($val3);
    $sub3id=$val3->account_codes_line_id;
      $name3=ucwords(strtolower($val3->account_code_meaning)); 
     $code3= $val3->account_code;
     $subfilter1=$filter11->where('future_reference2',$sub3id);
     $subfilter1->all();
     $subfilter12=collect($subfilter1);
   $subfilter2= $subfilter1=collect($subfilter1)->map(function($x){ return (array) $x; })->toArray();
    
     $arr_new3_1 = round(array_sum(array_column($subfilter1, 'amount1')),2);
     $arr_new3_2 = round(array_sum(array_column($subfilter1, 'amount2')),2);
     $arr_new3_3 = round(array_sum(array_column($subfilter1, 'amount3')),2);
     if($arr_new3_1 ==0)
     $arr_new3_1 ='-';
   
   $html.="<tr class='child child$id'><td class='parent' data='$sub3id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td><td style='
     text-align: right;
 '>".money_format('%!i',$arr_new3_1)."</td>
 <td style='
     text-align: right;
 '>".money_format('%!i',$arr_new3_2)."</td>
 <td style='
     text-align: right;
 '>".money_format('%!i',$arr_new3_3)."</td>
 </tr>";
  

   foreach($subfilter12 AS $val4){
 $accid=$val4->account_id;   
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4->account_id);
$accountname=$accn[0]->account_name;
$amount4_1 =$val4->amount1;
$amount4_2 =$val4->amount2;
$amount4_3 =$val4->amount3;
 $html.="<tr class='child child$sub3id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>".money_format('%!i',$amount4_1)."</td>
<td style='
    text-align: right;
'>".money_format('%!i',$amount4_2)."</td>
<td style='
    text-align: right;
'>".money_format('%!i',$amount4_3)."</td></tr>";

}
 }
}
 else
 {
    foreach($filter1 AS $val4){
  //  dd($filter1);
 $accountname=''; 
$accid=$val4['account_id'];
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4['account_id']);
$accountname=$accn[0]->account_name;

 $amount4_1 = $val4['amount1'];
  $amount4_2 = $val4['amount2'];
   $amount4_3 = $val4['amount3'];
  $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
     text-align: right;
 '>".money_format('%!i',$amount4_1)."</td>
 <td style='
     text-align: right;
 '>".money_format('%!i',$amount4_2)."</td>
 <td style='
     text-align: right;
 '>".money_format('%!i',$amount4_3)."</td></tr>";

} 

}
}
} else
 {
    foreach($filter1 AS $val4){
    
   

$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4->account_id);
$accountname=$accn[0]->account_name;
 $amount4_1=$val4->amount1;
  $amount4_2=$val4->amount2;
   $amount4_3=$val4->amount3;
  $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
     text-align: right;
 '>".money_format('%!i',$amount4_1)."</td>
 <td style='
     text-align: right;
 '>".money_format('%!i',$amount4_2)."</td>
 <td style='
     text-align: right;
 '>".money_format('%!i',$amount4_3)."</td></tr>";

}
 }
}
$html.="<tr><td><b>$name0 Total</b></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new_1,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new_2,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new_3,2))."</b></td></tr>";

    $total1 =$total1+$arr_new_1;
    $total2 =$total2+$arr_new_2;
    $total3 =$total3+$arr_new_3;


}

$html.="<tr class='heading'><td><b>ASSEST Total</b></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total1,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total2,2))."</b></td>
<td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total3,2))."</b></td></tr>";



   return $html;     


    }
    
  
    
    
    }