<?php 

require '../config.php';
ini_set('max_execution_time', 0);
require_once("../libraries/TCPDF-main/tcpdf.php");

$work_status = $_SESSION['work_status'];
$company ="";
if(isset($_GET['cmpy']) && !empty($_GET['cmpy'])){
	$company = $_GET['cmpy'];
	$getCompNameQry = "SELECT company_name FROM company_master WHERE comp_id=".$company;
	$getCompNameQryExe = mysqli_query($conn, $getCompNameQry);
	$theCompNameResult = mysqli_fetch_assoc($getCompNameQryExe);
	$theCompName = $theCompNameResult['company_name'];
}

$month = $_GET['month'];
$year = $_GET['year'];

$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$monthNum  = $month;
$dateObj   = DateTime::createFromFormat('!m', $monthNum);

global $salary_monthYr;
$salary_monthYr = '<div style="text-align:center; font-size: 12px;"><center>'.$theCompName.' <br> LIST OF SALARY PAYMENT FOR '.strtoupper($dateObj->format('F')).' '.$year.' </center></div>';


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
$pdf->setPrintFooter(true);

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
ep.*,e.passport_number
FROM employee_payroll as ep 
JOIN employee e ON e.emp_id = ep.emp_id_ 
WHERE 
ep.ref_comp_id = '$company' AND 
ep.month='$month' AND 
ep.year = '$year'  AND 
ep.work_status_ = '".$work_status."'
ORDER BY e.emp_id";
											
$qryExe1 = mysqli_query($conn, $getAllEmpSalQry); 
$dataRowCount1 = mysqli_num_rows($qryExe1); 
$html = ""; 
$html .='<div style="overflow-x: auto;"> <table width="100%"  cellpadding="2" cellspacing="0"><thead>
		
				<tr style="background-color: #FFFF00; color: #000;">
					<th align="center" style="width:25px; font-size:7px; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">S/NO</th>
					<th align="center" style="width:40px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">CODE NO</th>
					<th align="center" style="width:100px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">FULL NAME</th>
					<th align="center" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">PASSPORT NO</th>
					<th align="center" style="width:60px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">DESIGNATION</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">NUMBER OF DATES</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">OVERTIME (HOUR)</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">MONTHLY SALARY</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">OVERTIME FOR AN HOUR</th> 
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">SALARY FOR THIS MONTH</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">AMOUNT OF OVERTIME</th> 
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">ALLOWANCE</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">CONVEYANCE</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">FOOD</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">MEDICAL</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">HOUSING</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">TOTAL PAYABLE</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">ADVANCE/  LOAN</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">NET PAYABLE</th> 
				</tr>
			</thead>
			<tbody>';
			
$emp_code = $emp_name = $passport_number = $dep_name = $position = $no_of_wdays = $e_overtime = $salary = $salary_for_this_month = $overtime_sal_for_curent_month = $allowance = $food_allowance = $conveyance_allowance = $medical_allowance = $housing_allowance = $total_payable = $deduct_loan = $net_payable = "";			

if($dataRowCount1 > 0){ 

	$sl_no = $page_no =1;
	$this_month_salary_total = $amount_of_overtime_total = $allowance_amount_total = $total_payable_total = $advance_loan_total = $net_pay_total = 0;
	
	$salary_grand_total = $overt_time_grand_total = $allowance_grand_total = $total_payable_grand_total = $advance_loan_grand_total = $net_pay_grand_total = 0;
	
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
		
		$allowance_amount_total = $tot_allowance + $allowance_amount_total;
		
		$total_payable_total = $total_payable + $total_payable_total;
		
		$advance_loan_total = $deduct_loan + $advance_loan_total;
		
		$net_pay_total = $net_pay_total + $net_payable;
		
		


		if($row['year'].$row['month'] >= '202310'){
			$allowence_a 		= $allowance;
			$conveyance_a 	= $conveyance_allowance;
			$food_a 		= $food_allowance;
			$medical_a 		= $medical_allowance;
			$housing_a 		= $housing_allowance;
		}else{
			$allowence_a 		= $tot_allowance;
			$conveyance_a 	= '';
			$food_a 		= '';
			$medical_a 		=  '';
			//$housing_a 		= $housing_allowance;
		}
		
		$html .= '<tr nobr="true">
					<td align="center" valign="bottom" style="width:25px; font-size:9px; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px;">'. $sl_no .'</td>
					<td align="center" valign="bottom" style="width:40px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px;">'. $emp_code .'</td>
					<td align="center" valign="bottom" style="width:100px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px;">'. $emp_name .'</td>
					<td align="center" valign="bottom" style="width:50px;  font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; line-height: 30px;">'. $passport_number .'</td>
					<td align="center" valign="bottom" style="width:60px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px;">'. $position .'</td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; color: #095779; height: 35px; line-height: 30px;"><b>'. $no_of_wdays .'</b></td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; color: #095779; height: 35px; line-height: 30px;"><b>'. $e_overtime .'</b></td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; color: #095779; height: 35px; line-height: 30px;"><b>'. $salary .'</b></td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px; line-height: 30px;">'. $over_time_hour_rate .'</td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px; line-height: 30px;">'. $salary_for_this_month .'</td> 
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px; line-height: 30px;">'. $overtime_sal_for_curent_month .'</td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px; line-height: 30px;">'. $allowence_a .'</td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px; line-height: 30px;">'. $conveyance_a .'</td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px; line-height: 30px;">'. $food_a .'</td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px; line-height: 30px;">'. $medical_a .'</td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px; line-height: 30px;">'. $housing_a .'</td>
					<td align="center" valign="bottom" style="width:35px; font-size:10px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px; line-height: 30px; color: #095779;"><b>'. $total_payable .'</b></td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px; line-height: 30px;">'. $deduct_loan .'</td>
					<td align="center" valign="bottom" style="width:35px; font-size:9px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; height: 35px; line-height: 30px;">'. $net_payable .'</td>
					
				</tr>';
		
		if($sl_no%10 == 0 || $dataRowCount1 == $sl_no){
			//$pdf->AddPage();8+1
			$html .= '<tr>
						<td height="35" align="center" colspan="9" style="color: red; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;"><b>TOTAL PAGE NUMBER '.$page_no.'</b>
						</td>
						<td align="center" style="font-size:10px; color: red; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;"><b>'.$this_month_salary_total .'</b>
						</td>
						<td align="center" style="font-size:10px; color: red; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; height: 10px;  line-height: 30px; height: 30px;"><b>'.$amount_of_overtime_total.'</b>
						</td>
						<td align="center" colspan="5" style="font-size:10px; color: red; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;"><b>'.$allowance_amount_total.'</b>
						</td>
						<td align="center" style="font-size:10px; color: red; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;"><b>'.$total_payable_total.'</b>
						</td>
						<td align="center" style="font-size:10px; color: red; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;"><b>'.$advance_loan_total.'</b>
						</td>
						<td align="center" style="font-size:10px; color: red;o border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;"><b>'.$net_pay_total.'</b>
						</td>
						
					</tr>';
			
			/* $this_month_salary_total = $amount_of_overtime_total = $allowance_amount_total = $total_payable_total = $advance_loan_total = $net_pay_tota = 0; */
			
			$salary_grand_total = $this_month_salary_total + $salary_grand_total;
			$overt_time_grand_total = $amount_of_overtime_total + $overt_time_grand_total;
			$allowance_grand_total = $allowance_amount_total + $allowance_grand_total;
			$total_payable_grand_total = $total_payable_total + $total_payable_grand_total;
			$advance_loan_grand_total = $advance_loan_total + $advance_loan_grand_total;
			$net_pay_grand_total = $net_pay_total + $net_pay_grand_total;
			
			if($sl_no == $total_rows_count){
				$html .= '<tr>
						<td height="10" colspan="9" align="center" style="color: red; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;">
							<b>OVERALL TOTAL</b>
						</td>
						<td height="10" align="center" style="font-size:10px; color: red; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;">
							<b>'.$salary_grand_total .'</b>
						</td>
						<td height="10" align="center" style="font-size:9px; color: red; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;">
							<b>'.$overt_time_grand_total.'</b>
						</td>
						<td height="10" colspan="5" align="center" style="font-size:10px; color: red; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;">
							<b>'.$allowance_grand_total.'</b>
						</td>
						<td height="10" align="center" style="font-size:10px; color: red; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;">
							<b>'.$total_payable_grand_total.'</b>
						</td>
						<td height="10" align="center" style="font-size:10px; color: red; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;">
							<b>'.$advance_loan_grand_total.'</b>
						</td>
						<td height="10" align="center" style="font-size:10px; color: red; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; line-height: 30px; height: 30px;">
							<b>'.$net_pay_grand_total.'</b>
						</td>
						
					</tr>';
			}
			
			$this_month_salary_total = $amount_of_overtime_total = $allowance_amount_total = $total_payable_total = $advance_loan_total = $net_pay_total = 0;
			
			if($sl_no%10 == 0){
				$page_no++;
				$html .= '</tbody></table><br pagebreak="true"/><table width="100%"  cellpadding="2" cellspacing="0"><thead>
		
				<tr style="background-color: #FFFF00; color: #000;">
					<th align="center" style="width:25px; font-size:7px; border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">S/NO</th>
					<th align="center" style="width:40px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">CODE NO</th>
					<th align="center" style="width:100px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">FULL NAME</th>
					<th align="center" style="width:50px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">PASSPORT NO</th>
					<th align="center" style="width:60px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">DESIGNATION</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">NUMBER OF DATES</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">OVERTIME (HOUR)</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">MONTHLY SALARY</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">OVERTIME FOR AN HOUR</th> 
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">SALARY FOR THIS MONTH</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">AMOUNT OF OVERTIME</th> 
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">ALLOWANCE</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">CONVEYANCE</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">FOOD</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">MEDICAL</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">HOUSING</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">TOTAL PAYABLE</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">ADVANCE/  LOAN</th>
					<th align="center" style="width:35px; font-size:7px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">NET PAYABLE</th> 
					
				</tr>
			</thead><tbody>';
			}
			
		}
		if($sl_no ==  25){
			//break;
		}
		$sl_no++;
	}
}


		
$html .='</tbody>
	</table></div>';
//echo $html;

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('ListOfSalary.pdf', 'I');
?>
