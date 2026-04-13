<?php

namespace App\Http\Controllers;
use session;    
use Illuminate\Http\Request;

class MarketingAttritionrptController extends Controller
{

    public function index(Request $request)
    {


$from_date = $request->input('start_date');
$to_date   = $request->input('end_date');

$this->data['attrition_summary'] = \DB::select("
WITH RECURSIVE month_list AS (
    SELECT 
        DATE(?) AS month_start,
        LAST_DAY(DATE(?)) AS month_end
    UNION ALL
    SELECT 
        DATE_ADD(month_start, INTERVAL 1 MONTH),
        LAST_DAY(DATE_ADD(month_start, INTERVAL 1 MONTH))
    FROM month_list
    WHERE month_start < DATE(?)
)

SELECT
    DATE_FORMAT(m.month_start, '%b %Y') AS report_month,
    z.zone_name,
    s.state_name,

    SUM(
        CASE 
            WHEN e.date_of_joining < m.month_start
             AND (e.date_of_leaving IS NULL OR e.date_of_leaving >= m.month_start)
            THEN 1 ELSE 0
        END
    ) AS opening_balance,

    SUM(
        CASE 
            WHEN e.date_of_joining BETWEEN m.month_start AND m.month_end
            THEN 1 ELSE 0
        END
    ) AS joined_count,

    SUM(
        CASE 
            WHEN e.date_of_leaving BETWEEN m.month_start AND m.month_end
            THEN 1 ELSE 0
        END
    ) AS resigned_count,

    SUM(
        CASE 
            WHEN e.date_of_joining <= m.month_end
             AND (e.date_of_leaving IS NULL OR e.date_of_leaving > m.month_end)
            THEN 1 ELSE 0
        END
    ) AS closing_balance,

    ROUND(
        (
            SUM(
                CASE 
                    WHEN e.date_of_leaving BETWEEN m.month_start AND m.month_end
                    THEN 1 ELSE 0
                END
            ) * 100.0
        ) / NULLIF(
            SUM(
                CASE 
                    WHEN e.date_of_joining < m.month_start
                     AND (e.date_of_leaving IS NULL OR e.date_of_leaving >= m.month_start)
                    THEN 1 ELSE 0
                END
            ), 0
        ),
        2
    ) AS attrition_percentage

FROM month_list m
CROSS JOIN hr_employee_t e
LEFT JOIN a_zone_master_t z 
    ON z.zone_id = e.zone_id
LEFT JOIN hr_emp_contact c 
    ON c.employee_id = e.employee_id
LEFT JOIN m_states_t s 
    ON s.state_id = c.permanent_state

WHERE e.employee_type IN ('231', '248', '275')
  AND e.date_of_joining <= LAST_DAY(DATE(?))
  AND (e.date_of_leaving IS NULL OR e.date_of_leaving >= DATE(?))

GROUP BY
    m.month_start,
    z.zone_name,
    s.state_name

ORDER BY
    z.zone_name,
    s.state_name,
    m.month_start", [$from_date, $from_date, $to_date, $to_date, $from_date]);




    
    return view('marketingattrition.report', $this->data);

    }








}