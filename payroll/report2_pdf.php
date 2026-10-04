<?php 

require '../config.php';
ini_set('max_execution_time', 0);
require_once("../libraries/TCPDF-main/tcpdf.php");

session_start();
$work_status = isset($_SESSION['work_status']) ? $_SESSION['work_status'] : '';
$company ="";
if(isset($_GET['cmpy']) && !empty($_GET['cmpy'])){
	$company = $_GET['cmpy'];
	$getCompNameQry = "SELECT company_name FROM company_master WHERE comp_id=".$company;
	$getCompNameQryExe = mysqli_query($conn, $getCompNameQry);
	$theCompNameResult = mysqli_fetch_assoc($getCompNameQryExe);
	$theCompName = $theCompNameResult['company_name'];
}

$year = isset($_GET['year']) ? $_GET['year'] : '';
$emp_id = $_GET['emp_id'];



global $salary_monthYr;
$salary_monthYr = '<div style="text-align:center; font-size: 12px;"><center>'.$theCompName.' <br> LIST OF SALARY PAYMENT FOR '.$year.' </center></div>';


class MYPDF extends TCPDF {

    //Page header
   public function Header() {
        global $salary_monthYr;
		//$this->Cell(0, 15, $salary_monthYr, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		//$this->writeHTML('asd');
		 $this->writeHTML($salary_monthYr, true, false, true, false, '');
    }

    // Page footer
    public function Footer() {
        
          $fhtml = '
            <table class="tblFooter" cellpadding="5">
            <tr>
                <td>
                    APPROVED BY:<br>REGIONAL MANAGER
                </td>
                <td align="center">
                    FINANCE MANAGER 
                </td>
                <td align="right">
                    BY:ACCOUNTANT
                </td>
            </tr>
        </table>
          ';
          
          $this->writeHTML($fhtml, true, false, true, false, '');
          
        }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(true);
$pdf->setPrintFooter(false);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setHeaderFont(array('', '', 13));
$pdf->setFooterFont(array('', '', 8));

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}


// add a new page for TOC
$pdf->addPage('l', 'A4');

//$getAllEmpSalQry = "SELECT e.*, (SELECT department_name FROM  department_master WHERE dep_id =e.division) as dep_name, (SELECT position_name FROM  position_master WHERE position_id =e.position) as position_name, ea.*, ep.* FROM employee e INNER JOIN employee_attendance ea ON e.emp_id = ea.ref_emp_id INNER JOIN employee_payroll ep ON ea.attendance_id = ep.attendance_id WHERE work_status = '$work_status' AND e.ref_comp_id = '$company' AND ea.month='$month' AND ea.year = '$year' AND e.employe_status = 0  ORDER BY e.emp_id";

$getAllEmpSalQry = "SELECT 
ep.*,e.emp_id,e.passport_number
FROM employee_payroll as ep 
JOIN employee e ON e.emp_id = ep.emp_id_ 
WHERE 
ep.ref_comp_id = '$company' AND 
-- ep.month='$month' AND 
ep.year = '$year'  AND 
e.emp_id = '$emp_id' AND
ep.work_status_ = '".$work_status."'
ORDER BY e.emp_id";
													
		$qryExe1 = mysqli_query($conn, $getAllEmpSalQry); 
		$dataRowCount1 = mysqli_num_rows($qryExe1); 
		$html = ""; 
		$html .='<div style="overflow-x: auto;"> <table width="100%"  cellpadding="2" cellspacing="0"><thead>
		
				<tr style="background-color: #FFFF00; color: #000;">
					<th align="center" style="width:50px; font-size:7px; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Date</th>
					<th align="center" style="width:30px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Employee Code</th>
					<th align="center" style="width:100px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Name</th>
					<th align="center" style="width:60px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Occupation</th>
					<th align="center" style="width:30px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">No of Working Days</th>
					<th align="center" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Basic Salary</th>
					<th align="center" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Allowance</th>
					<th align="center" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Conveyance Allowance</th>
					<th align="center" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Food Allowance</th>
					<th align="center" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Medical Allowance</th>
					<th align="center" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Housing Allowance</th>
					<th align="center" style="width:30px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Over Time</th>
					<th align="center" style="width:60px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Gross Total</th>
					<th align="center" style="width:60px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Deductions</th>
					<th align="center" style="width:60px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Net Salary</th>
				</tr>
			</thead>
			<tbody>';
			
$emp_code = $emp_name = $passport_number = $dep_name = $position = $no_of_wdays = $e_overtime = $salary = $salary_for_this_month = $overtime_sal_for_curent_month = $allowance = $food_allowance = $conveyance_allowance = $medical_allowance = $housing_allowance = $total_payable = $deduct_loan = $net_payable = "";			

if($dataRowCount1 > 0){ 

	$sl_no = $page_no =1;
	$this_month_salary_total = $amount_of_overtime_total = $allowance_amount_total = $total_payable_total = $advance_loan_total = $net_pay_total = 0;
	
	$salary_grand_total = $overt_time_grand_total = $allowance_grand_total = $total_payable_grand_total = $advance_loan_grand_total = $net_pay_grand_total = 0;

	$total_allowance_column = $total_conveyance = $total_food = $total_medical = $total_house = 0;
	$grand_allowance_column = $grand_conveyance = $grand_food = $grand_medical = $grand_house = 0;

	
	$total_rows_count = mysqli_num_rows($qryExe1);
	while($row = mysqli_fetch_assoc($qryExe1)){ 
		//echo "<pre>";print_r($row);exit;
		$emp_code = $row['emp_code_'];
		$emp_name = $row['emp_name_'];
		$passport_number = $row['passport_number'];
		$overtime_sal_for_curent_month = $row['overtime_sal_for_curent_month'];
		$dep_name = $row['dep_name'];
		$position = $row['position_name_'];
		$no_of_wdays = $row['no_of_wdays_'];
		$e_overtime = $row['e_overtime_']; 
		$salary = $row['salary_'];
		$over_time_hour_rate = $row['over_time_hour_rate_'];
		$salary_for_this_month = round($row['salary_for_this_month']);
		$overtime_sal_for_curent_month = $row['overtime_sal_for_curent_month'];
		$allowance = $row['allowance'];
		$food_allowance = $row['food_a'];
		
		/*$perDay_conveyance_allowance = $row['conveyance_a']/$total_days;
		$conveyance_allowance = round($perDay_conveyance_allowance * $row['no_of_wdays']); */
		$conveyance_allowance = $row['conveyance_a'];
		
		
		$medical_allowance = $row['medical_a'];
		$housing_allowance = $row['housing_a'];
		$total_payable = $row['total_payable'];
		$deduct_loan = $row['deduct_loan'];
		
		if($salary_for_this_month == 0 && $overtime_sal_for_curent_month == 0){
			$tot_allowance = 0;
		}else{
			$tot_allowance = $allowance + $food_allowance + $conveyance_allowance + $medical_allowance + $housing_allowance;
			
		}
	
		if($tot_allowance == '' || $tot_allowance == 0){
		   $tot_allowance = $housing_allowance;
		   //$salary_for_this_month = $tot_allowance;
		}
		 
		
		$net_payable = round($row['net_payable']);
		
		$this_month_salary_total = $salary_for_this_month + $this_month_salary_total;
		
		$amount_of_overtime_total = $overtime_sal_for_curent_month + $amount_of_overtime_total;
		
		
		
		$total_payable_total = $total_payable + $total_payable_total;
		
		$advance_loan_total = $deduct_loan + $advance_loan_total;
		
		$net_pay_total = $net_pay_total + $net_payable;
		
		

		


			$month_ = $row['month'];
			if(strlen($row['month']) == 1){
				$month_ = '0'.$row['month'];
			}

            if($row['year'].$month_ >= '202310'){
			$allowence_a 		= $allowance;
			$conveyance_a 	= $conveyance_allowance;
			$food_a 		= $food_allowance;
			$medical_a 		= $medical_allowance;
			$housing_a 		= $housing_allowance;

			$allowance_amount_total = $allowance_amount_total + $row['allowance'];
			$total_conveyance 		= $total_conveyance + $conveyance_a;
			$total_food 			= $total_food + $food_a;
			$total_medical 			= $total_medical + $medical_a;
			$total_house 			= $total_house + $housing_allowance;
		}else{
			$allowence_a 		= $tot_allowance;
			$conveyance_a 	= '';
			$food_a 		= '';
			$medical_a 		=  '';
			$housing_a 		= '';

			$allowance_amount_total = $tot_allowance + $allowance_amount_total;
			$total_conveyance 		= '';
			$total_food 			= '';
			$total_medical 			= '';
			$total_house 			= '';
		}

		// $no_of_wdays = 0;
		// if(isset($row['month']) && !empty($row['month']) && isset($_GET['year']) && !empty($_GET['year'])){
		// 	$no_of_wdays = cal_days_in_month(CAL_GREGORIAN,$row['month'],$year);											
		// }

				
		
		
		$html .= '<tr nobr="true">
					<td align="center" valign="bottom" style="width:50px; font-size:7px; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. date('M-Y', strtotime($row['year'] . '-' . $row['month'])) .'</td>
					<td align="center" valign="bottom" style="width:30px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $emp_code .'</td>
					<td align="center" valign="bottom" style="width:100px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $emp_name .'</td>
					<td align="center" valign="bottom" style="width:60px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $position .'</td>
					<td align="center" valign="bottom" style="width:30px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $no_of_wdays .'</td>
					<td align="center" valign="bottom" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $salary .'</td>
					<td align="center" valign="bottom" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $allowence_a .'</td>
					<td align="center" valign="bottom" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $conveyance_a .'</td>
					<td align="center" valign="bottom" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $food_a .'</td>
					<td align="center" valign="bottom" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $medical_a .'</td>
					<td align="center" valign="bottom" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $housing_a .'</td>
					<td align="center" valign="bottom" style="width:30px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $overtime_sal_for_curent_month .'</td>
					<td align="center" valign="bottom" style="width:60px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $total_payable .'</td>
					<td align="center" valign="bottom" style="width:60px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $deduct_loan .'</td>
					<td align="center" valign="bottom" style="width:60px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 25px;">'. $net_payable .'</td>
				</tr>';
		
	
		if($sl_no ==  25){
			//break;
		}
		$sl_no++;
	}
}


		
$html .='</tbody>
	</table></div>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('ListOfSalary.pdf', 'I');
?>
