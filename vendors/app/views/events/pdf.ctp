<?php
header("Content-type: application/pdf");

App::import('Vendor','tcpdf/tcpdf');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

$tbl = "";
foreach($events as $event) {
$date = date("M d, Y", strtotime($event["CalendarsEvent"]["start_date_time"]) );

if ($event["CalendarsEvent"]["start_date_time"] < $event["CalendarsEvent"]["end_date_time"]) {
	$date .= " - " . date("M d, Y", strtotime($event["CalendarsEvent"]["end_date_time"]));
}
$date .= "<br />" . date("g:i A", strtotime($event["CalendarsEvent"]["start_date_time"]) ) . " - " . date("g:i A",strtotime($event["CalendarsEvent"]["end_date_time"]));
	
	
	

$tbl .= <<<EOD
<table cellpadding="0" border="0" style="border-collapse: separate">
        <tbody>
             <tr style="font-size:25px">
                <td colspan="1"><strong>$date</strong></td>
                <td colspan="1" style="padding: 2px"><strong>{$event["Event"]["title"]}</strong></td>  
            </tr>
            <tr style="font-size:22px">
            	<td colspan="2" style="border-bottom: 1px solid gray;"><div style="line-height:1.5em">{$event["Event"]["description"]}</div>
            
            	</td>	
            </tr>
        </tbody>
        
    </table>
EOD;
}
$pdf->writeHTML($tbl, false, false, false, false, '');


// Print text using writeHTMLCell()
//$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('calendar_event.pdf', 'I');
?>