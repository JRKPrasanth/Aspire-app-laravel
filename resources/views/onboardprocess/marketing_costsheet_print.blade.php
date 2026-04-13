<?php
ob_start();

require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

class MYPDF extends TCPDF
{
    public $logoPath = null;

    public function setLogo($company_logo_path)
    {
        $this->logoPath = $company_logo_path;
    }

    // Header
    public function Header()
    {
        $image_file = $this->logoPath ?: (public_path() . '/images/letterhead.PNG');

        // Header image
        $this->Image($image_file, 10, 5, 190, 35, 'PNG', '', 'T', false, 300);

        // Move cursor to just below the header image (NO extra gap)
        $this->SetY($this->getImageRBY() + 2);
    }

    // Footer
    public function Footer()
    {
        $this->SetY(-10);
        $this->SetFont('helvetica', '', 8);
        $this->Cell(0, 8, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

/**
 * Make HTML compact & TCPDF friendly
 */
function cleanForTCPDF($html)
{
    // decode
    $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5);

    // remove mso/word junk
    $html = preg_replace('/mso-[^:]+:[^;"]+;?/i', '', $html);

    // remove thead/tfoot/colgroup (tcpdf can behave weird)
    $html = preg_replace('/<\/?(thead|tfoot|colgroup|col)[^>]*>/i', '', $html);

    // remove hard widths/heights
    $html = preg_replace('/\s(width|height)="[^"]*"/i', '', $html);
    $html = preg_replace('/(width|height):\s*\d+(pt|px|%);?/i', '', $html);

    // remove empty style attrs
    $html = preg_replace('/style="\s*"/i', '', $html);

    // remove empty paragraphs/divs
    $html = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $html);
    $html = preg_replace('/<div[^>]*>\s*<\/div>/i', '', $html);

    // reduce repeated <br>
    $html = preg_replace('/(<br\s*\/?>\s*){2,}/i', '<br />', $html);

    // remove empty table rows
    $html = preg_replace('/<tr[^>]*>\s*(<td[^>]*>\s*<\/td>\s*)+<\/tr>/i', '', $html);

    // compact + center table styles
    $tableStyle  = 'border:2px solid #000;border-collapse:collapse;width:100%;';
    $cellStyle   = 'border:1px solid #4b403f;padding:2px;font-size:10px;line-height:12px;text-align:center;';
    $headerStyle = 'padding:1px;font-size:9px;line-height:11px;font-weight:bold;text-align:center;background-color:#e6e6e6;';

    // Add styles (note: this overwrites existing inline styles; good for consistency)
    $html = preg_replace('/<table([^>]*)>/i', '<table$1 style="'.$tableStyle.'">', $html);
    $html = preg_replace('/<th([^>]*)>/i', '<th$1 style="'.$headerStyle.'">', $html);
    $html = preg_replace('/<td([^>]*)>/i', '<td$1 style="'.$cellStyle.'">', $html);

    // tcpdf doesn’t support float well
    $html = str_ireplace(['float:right', 'float: left', 'float:left'], '', $html);

    // convert bootstrap-ish alignment classes if present
    $html = preg_replace('/class="[^"]*\btext-center\b[^"]*"/i', 'align="center"', $html);
    $html = preg_replace('/class="[^"]*\btext-right\b[^"]*"/i', 'align="right"', $html);
    $html = preg_replace('/class="[^"]*\btext-left\b[^"]*"/i', 'align="left"', $html);

    // final wrapper (center everything)
    return '<div style="text-align:center;">'.$html.'</div>';
}

/**
 * Number to words (same as yours, kept)
 */
function convert_number_to_words($number)
{
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $only        = 'Only';

    $dictionary = array(
        0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five',
        6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten', 11 => 'eleven',
        12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen',
        17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'fourty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety',
        100 => 'hundred', 1000 => 'thousand', 100000 => 'lakh', 1000000 => 'million',
        1000000000 => 'billion', 1000000000000 => 'trillion',
        1000000000000000 => 'quadrillion', 1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) return false;

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos((string)$number, '.') !== false) {
        list($number, $fraction) = explode('.', (string)$number);
    }

    $number = (int)$number;

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;

        case $number < 100:
            $tens  = ((int)($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) $string .= $hyphen . $dictionary[$units];
            break;

        case $number < 1000:
            $hundreds  = (int)($number / 100);
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) $string .= $conjunction . convert_number_to_words($remainder);
            break;

        case $number < 100000:
            $thousands = (int)($number / 1000);
            $remainder = $number % 1000;
            $string = convert_number_to_words($thousands) . ' ' . $dictionary[1000];
            if ($remainder) $string .= $conjunction . convert_number_to_words($remainder);
            break;

        case $number < 10000000:
            $lakhs = (int)($number / 100000);
            $remainder = $number % 100000;
            $string = convert_number_to_words($lakhs) . ' ' . $dictionary[100000];
            if ($remainder) $string .= $conjunction . convert_number_to_words($remainder) . ' ' . $only;
            break;

        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string)$fraction) as $n) {
            $words[] = $dictionary[(int)$n];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

// ------------------------ PDF INIT ------------------------
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HR');
$pdf->SetTitle('Cost Sheet');

$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);

// Use dynamic logo if you want
// $pdf->setLogo(public_path('images/'.$logo));

// TIGHT SETTINGS to fit 2 pages
$pdf->SetFont('helvetica', '', 7.5);
$pdf->SetMargins(10, 42, 10);
$pdf->SetHeaderMargin(2);
$pdf->SetFooterMargin(8);

$pdf->SetAutoPageBreak(true, 8);
$pdf->setCellPaddings(1, 1, 1, 1);
$pdf->setCellHeightRatio(1.0);

// Remove default HTML vertical spacing
$pdf->setHtmlVSpace([
    'p' => [0, 0],
    'div' => [0, 0],
    'br' => [0, 0],
    'tr' => [0, 0],
    'table' => [0, 0],
    'h1' => [0, 0],
    'h2' => [0, 0],
]);

$pdf->AddPage();

// ------------------------ YOUR DATA ------------------------
// These variables must come from controller (same as your code)
$salary_words  = convert_number_to_words($ctc_pay);
$current_date  = date("d-m-Y");

// If your letter HTML comes from DB:
$content = $letter_content[0]->body_content ?? '';

$find = [
    "[logo]", "[company_name]", "[company_address]", "[current_date]", "[position]", "[employee_name]",
    "[gross_salary]", "[gross_text]", "[pf_amount]", "[esi_amount]", "[HR name]", "[grade]", "[class_of_hq]",
    "[location]", "[id]", "[prefix]", "[da]", "[basic_pay]", "[hra]", "[ctc_pay]", "[net_pay]",
    "[other_allowance]", "[conveyance]", "[transportation_allowance]", "[education_allowance]",
    "[rail_bus_pass_allowance]", "[gross_pay_month]", "[vehicle_allowance]", "[mobileint_allowance]",
    "[railbus_allowance]", "[other_allow_exp]", "[tot_allow_month]", "[annual_allowance]",
    "[pf_amount_annual]", "[gratuity]", "[esi_amount_annual]", "[hqdaily_allow_hq_a]",
    "[hqdaily_allow_exhq_a]", "[hqdaily_allow_os_a]", "[hqdaily_allow_hq_b]", "[hqdaily_allow_exhq_b]",
    "[hqdaily_allow_os_b]", "[hill_allowance]", "[jw_allowance]", "[jw_allowance_applicable]",
    "[travel_allowance]", "[pa_premium]", "[mi_premium]", "[medical_allowance]", "[furnishing_allowance]",
    "[leavetravel_allowance]", "[ctc_per_annum]", "[ctc_per_month]"
];

// NOTE: Use absolute path for image in TCPDF
$logoImg = '<img src="' . public_path('images/' . $logo) . '" width="110" />';

$replace = [
    $logoImg, $company_name, $company_address, $current_date, $description_name, $name_of_the_candidate,
    $gross_pay, $salary_words, $pf_amount, $esi_amount, $hr_name, $grade_name, $class_of_hq, $location,
    $randid, $prefix, $da, $basic_pay, $hra, $ctc_pay, $net_pay, $other_allowance, $conveyance,
    $transportation_allowance, $education_allowance, $rail_bus_pass_allowance, $gross_pay_month,
    $vehicle_allowance, $mobileint_allowance, $railbus_allowance, $other_allow_exp, $tot_allow_month,
    $annual_allowance, $pf_amount_annual, $gratuity, $esi_amount_annual, $hqdaily_allow_hq_a,
    $hqdaily_allow_exhq_a, $hqdaily_allow_os_a, $hqdaily_allow_hq_b, $hqdaily_allow_exhq_b,
    $hqdaily_allow_os_b, $hill_allowance, $jw_allowance, $jw_allowance_applicable, $travel_allowance,
    $pa_premium, $mi_premium, $medical_allowance, $furnishing_allowance, $leavetravel_allowance,
    $ctc_per_annum, $ctc_per_month
];

// Replace placeholders
$finalHtml = str_replace($find, $replace, $content);

// Clean + compact HTML for TCPDF (IMPORTANT)
$finalHtml = cleanForTCPDF($finalHtml);

// Write HTML
$pdf->writeHTML($finalHtml, true, false, true, false, '');

// finish
$pdf->lastPage();

ob_end_clean();

// Output
if ($print === "PRINT") {
    $pdf->Output('Cost_Sheet_' . $name_of_the_candidate . '.pdf', 'I');
    $pdf->close();
    exit;
} else {
    $pdf->Output('Uploads/offetletter/C_' . $candidate_id . '.pdf', 'F');
}
?>
