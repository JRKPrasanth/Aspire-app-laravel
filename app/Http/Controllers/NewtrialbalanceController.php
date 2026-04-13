<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class NewtrialbalanceController extends Controller
{


    public function __construct()
    {

        $this->data = array();

        $this->data['pageMethod'] = \Request::route()->getName();

    }

    public function index(Request $request)
    {



        // restrict illegal entry purpose for menus - VIGNESH M

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


        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');


        $this->data['trial_balance'] = \DB::select("SELECT
    journal_name,
    SUM(debit) AS debit,
    SUM(credit) AS credit,
    concatenated_segments,
    SUM(balance) AS opening_balance,
    ROUND(SUM(balance + debit - credit), 2) AS balance,
    FS,
    account_id
FROM (
    SELECT
        f_journal_entry_t.journal_name,
        ROUND(SUM(f_journal_entry_lines_t.debit_amount), 2) AS debit,
        ROUND(SUM(f_journal_entry_lines_t.credit_amount), 2) AS credit,
        f_account_structure_t.concatenated_segments,
        0 AS balance,
        f_journal_entry_lines_t.account_id,
        CASE 
            WHEN f_account_structure_t.concatenated_segments LIKE '%60000%' 
              OR f_account_structure_t.concatenated_segments LIKE '%70000%' 
            THEN 'P&L' ELSE 'BS'
        END AS FS
    FROM f_journal_entry_t
    LEFT JOIN f_journal_entry_lines_t 
        ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    LEFT JOIN f_account_structure_t 
        ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
    WHERE f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
    GROUP BY f_journal_entry_lines_t.account_id, f_journal_entry_t.journal_name, f_account_structure_t.concatenated_segments

    UNION ALL

    SELECT
        '' AS journal_name,
        0 AS debit,
        0 AS credit,
        f_account_structure_t.concatenated_segments,
        ROUND(SUM(f_journal_entry_lines_t.debit_amount - f_journal_entry_lines_t.credit_amount), 2) AS balance,
        f_journal_entry_lines_t.account_id,
        CASE 
            WHEN f_account_structure_t.concatenated_segments LIKE '%60000%' 
              OR f_account_structure_t.concatenated_segments LIKE '%70000%' 
            THEN 'P&L' ELSE 'BS'
        END AS FS
    FROM f_journal_entry_t
    LEFT JOIN f_journal_entry_lines_t 
        ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    LEFT JOIN f_account_structure_t 
        ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
    WHERE f_journal_entry_lines_t.journal_date < '$start_date' 
      AND f_journal_entry_lines_t.journal_date >= '2019-04-01'
    GROUP BY f_journal_entry_lines_t.account_id, f_account_structure_t.concatenated_segments
) AS v1
GROUP BY v1.account_id, v1.concatenated_segments, v1.FS HAVING v1.concatenated_segments!='' ");




        return view('trialbalnew.report', $this->data);

    }

    public function trialbalpopup(Request $request)
    {

        $accountName = $request->accountName;
        $start_date = $request->start_date1;
        $end_date = $request->end_date1;

        // dd($accountName);   

        $detail_summary = \DB::select("SELECT DATE_FORMAT(journal_date, '%b-%y') AS yr_month,
    journal_name,
    journal_date,
    SUM(debit) AS debit,
    SUM(credit) AS credit,
    concatenated_segments,
    SUM(balance) AS opening_balance,
    ROUND(SUM(balance + debit - credit), 2) AS balance

FROM (
    SELECT
        f_journal_entry_t.journal_name,
        f_journal_entry_t.journal_date,
        ROUND(SUM(f_journal_entry_lines_t.debit_amount), 2) AS debit,
        ROUND(SUM(f_journal_entry_lines_t.credit_amount), 2) AS credit,
        f_account_structure_t.concatenated_segments,
        0 AS balance
    FROM f_journal_entry_t
    LEFT JOIN f_journal_entry_lines_t 
        ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    LEFT JOIN f_account_structure_t 
        ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
    WHERE f_account_structure_t.concatenated_segments LIKE '%$accountName%' AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
    GROUP BY f_journal_entry_t.journal_date,f_journal_entry_t.journal_name
    UNION ALL
    SELECT
        'OPENING BALANCE' AS journal_name,
         f_journal_entry_t.journal_date,
        0 AS debit,
        0 AS credit,
        f_account_structure_t.concatenated_segments,
        ROUND(SUM(f_journal_entry_lines_t.debit_amount - f_journal_entry_lines_t.credit_amount), 2) AS balance
    FROM f_journal_entry_t
    LEFT JOIN f_journal_entry_lines_t 
        ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    LEFT JOIN f_account_structure_t 
        ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
    WHERE f_journal_entry_lines_t.journal_date < '$start_date' 
      AND f_journal_entry_lines_t.journal_date >= '2019-04-01' AND f_account_structure_t.concatenated_segments LIKE '%$accountName%'
    GROUP BY f_journal_entry_t.journal_date
) AS v1
GROUP BY v1.journal_date,v1.journal_name");

        $htmlTable = '<div style="height:400px; overflow-y:auto;">
<table id="popuptbl" class="table table-bordered table-striped" style="width:100%;">
<thead><tr class="sticky-row" style="position: sticky;top:0;">
    <th style="text-align: center;background: #48d36c;">Journal Date</th>
    <th style="text-align: center;background: #48d36c;">Journal Name</th>
    <th style="text-align: center;background: #48d36c;">Opening Balance</th>
    <th style="text-align: center;background: #48d36c;">Debit</th>
    <th style="text-align: center;background: #48d36c;">Credit</th>
    <th style="text-align: center;background: #48d36c;">Balance</th>
</tr></thead><tbody>';


        $totalOpening = 0;
        $totalCredit = 0;
        $totalDebit = 0;
        $totalBalance = 0;

        foreach ($detail_summary as $value) {
            $totalOpening += $value->opening_balance;
            $totalCredit += $value->credit;
            $totalDebit += $value->debit;
            $totalBalance += $value->balance;

            $htmlTable .= '<tr class="accordion-toggle">
        <td style="text-align: center;">' . $value->journal_date . '</td>
        <td style="text-align: center;">' . $value->journal_name . '</td>
        <td style="text-align: center;">' . number_format($value->opening_balance, 2) . '</td>
        <td style="text-align: center;">' . number_format($value->debit, 2) . '</td>
        <td style="text-align: center;">' . number_format($value->credit, 2) . '</td>
        <td style="text-align: center;">' . number_format($value->balance, 2) . '</td>
    </tr>';
        }

        // Grand total row
        $htmlTable .= '<tr style="position: sticky; bottom: 0; background-color: #ffd700; font-weight: bold; z-index: 1;">
    <td colspan="2" style="text-align:center;">Grand Total</td>
    <td style="text-align:center;">' . number_format($totalOpening, 2) . '</td>
    <td style="text-align:center;">' . number_format($totalCredit, 2) . '</td>
    <td style="text-align:center;">' . number_format($totalDebit, 2) . '</td>
    <td style="text-align:center;">' . number_format($totalBalance, 2) . '</td>
</tr>';


        return $htmlTable;

    }



}