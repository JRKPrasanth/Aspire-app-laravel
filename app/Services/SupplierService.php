<?php

namespace App\Services;

use DB;

class SupplierService
{
    public static function getBalance($supplier_id)
    {
        return DB::table('f_journal_entry_lines_t')
            ->where('reference_source', 'SUPPLIER')
            ->where('reference_id', $supplier_id)
            ->selectRaw('COALESCE(SUM(credit_amount - debit_amount),0) as balance')
            ->value('balance');
    }
}