<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mtltransactiontypes;

class CommonutilityController extends Controller
{
   
	public function Materialissue($id)
{
    // Header, job, job status
    $hdr = \DB::table('w_materialissue_hdr_t')->where('w_materialissue_hdr_id', $id)->first();
    if (!$hdr) {
        // nothing to do
        return true;
    }

    $job = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $hdr->w_jobs_hdr_id)->first();
    $jobno = $job ? $job->job_no : null;

    $jobstatus = \DB::table('i_quality_spec_trx_hdr_t')
        ->leftJoin('w_jobcard_hdr_t', 'w_jobcard_hdr_t.w_jobs_hdr_id', '=', 'i_quality_spec_trx_hdr_t.job_hdr_id')
        ->where('quality_spec_trx_hdr_id', $job->reference_source_id ?? 0)
        ->select('i_quality_spec_trx_hdr_t.job_hdr_id', 'w_jobcard_hdr_t.job_status')
        ->first();

    if ($jobno) {
        \DB::table('i_reservation_detail_t')->where('reference_no', $jobno)->delete();
    }

    // Lines
    $lines = \DB::table('w_materialissue_line_t')->where('w_materialissue_hdr_id', $id)->get();

    // Transaction type (MATERIAL ISSUE)
    $trx = \DB::table('m_transaction_types_t')
        ->where('transaction_type_name', 'MATERIAL ISSUE')
        ->first();
    if (!$trx) {
        throw new \RuntimeException('Transaction type "MATERIAL ISSUE" not configured.');
    }

    $today = date('Y-m-d');
    $now   = date('Y-m-d H:i:s');
    $org   = \Session::get('organization');
    $loc   = \Session::get('location');
    $comp  = \Session::get('companyid');
    $user  = \Session::get('id');

    foreach ($lines as $line) {
        // Fetch product group (as object access!)
        $prdRow = \DB::table('m_products_t')->where('product_id', $line->product_id)->first();
        $prdgroup = $prdRow->product_group_id ?? null;

        // Parse comma-separated fields safely
        $subinv      = strlen((string)$line->subinventory_id) ? explode(',', $line->subinventory_id) : [];
        $locid       = strlen((string)$line->locator_id)      ? explode(',', $line->locator_id)      : [];
        $issqty      = strlen((string)$line->issueqty)        ? explode(',', $line->issueqty)        : [];
        $batchnumber = strlen((string)$line->batchnumber)     ? explode(',', $line->batchnumber)     : [];

        // Normalize lengths to avoid undefined offsets
        $maxLen = max(count($subinv), count($locid), count($issqty), count($batchnumber));
        for ($i = 0; $i < $maxLen; $i++) {
            $sv  = $subinv[$i]      ?? null;
            $lv  = $locid[$i]       ?? null;
            $qv  = $issqty[$i]      ?? null;
            $bn  = $batchnumber[$i] ?? null;

            // Skip empty or zero qty rows
            if ($qv === null || $qv === '' || (float)$qv == 0) {
                continue;
            }

            // Build material trx row (RESET ARRAY PER ITERATION)
            $data = [
                'trx_source_type_id' => $trx->transaction_source_id,
                'trx_action_id'      => $trx->transaction_action_id,
                'trx_type_id'        => $trx->transaction_type_id,
                'trx_source_hdr_id'  => $id,
                'trx_source_line_id' => $line->w_materialissue_line_id,
                'line_number'        => $line->line_no,
                'product_id'         => $line->product_id,
                'trx_uom'            => $line->uom_code_id,
                'trx_date'           => $today,
                'created_by'         => $user,
                'created_at'         => $now,
                'organization_id'    => $org,
                'location_id'        => $loc,
                'company_id'         => $comp,
                'subinventory_id'    => $sv,
                'locator_id'         => $lv,
                'trx_qty'            => -(float)$qv, // ISSUE = negative
            ];

            // Insert material trx FIRST to get $mtlid
            $mtlid = \DB::table('m_material_trx_t')->insertGetId($data);

            // Build QOH detail trx row (RESET ARRAY PER ITERATION)
            $dataqoh = [
                'product_id'       => $line->product_id,
                'qoh_uom_code_id'  => $line->uom_code_id,
                'create_trx_id'    => $mtlid,               // now we have it
                'created_by'       => $user,
                'created_at'       => $now,
                'qualitystatus'    => 1,                    // as in your code
                'qoh_source'       => 'MATERIAL ISSUE',
                'organization_id'  => $org,
                'location_id'      => $loc,
                'company_id'       => $comp,
                'subinventory_id'  => $sv,
                'locator_id'       => $lv,
                'batch_number'     => $bn,
                'job_id'           => $hdr->w_jobs_hdr_id,
                'qoh_trx_date'     => date('Y-m-d', strtotime($hdr->mtl_issue_date)),
                'qoh_source_id'    => $id,
            ];

            // qty field according to job status
            if ($jobstatus && ($jobstatus->job_status === 'REWORK')) {
                $dataqoh['rework_qty'] = -(float)$qv;
                $dataqoh['qualitytype'] = 'rework';
            } else {
                $dataqoh['qoh_trx_qty'] = -(float)$qv;
            }

            \DB::table('i_qoh_detail_t')->insertGetId($dataqoh);
        }
    }

    return true;
}
	
}
