<?php

require '../config.php';
require_once("../libraries/TCPDF-main/tcpdf.php");


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, false, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// add a new page for TOC
$pdf->addPage('p', 'A4');

$company_name = $emp_name = $position = $emp_code = $joining_date = $salary_month = $basic_salary = $house_rent = $conveyance = $food_allowance = $medical_allowance = $other_allowances = $loan_deductions = $total_earnings = "";

$getSalaryDetailsQry = "SELECt e.*, ep.*, ea.*, ( SELECT company_name FROM company_master WHERE comp_id = e.ref_comp_id ) as company_name, (SELECT position_name FROM position_master WHERE position_id=e.position) as position_name FROM employee_payroll ep INNER JOIN  employee_attendance ea ON ep.attendance_id=ea.attendance_id INNER JOIN employee e ON e.emp_id = ea.ref_emp_id WHERE payroll_id=".$_GET['p_id'];

$getSalaryDetailsQryExe = mysqli_query($conn, $getSalaryDetailsQry); 
if(mysqli_num_rows($getSalaryDetailsQryExe) > 0){ 
	while($getSalaryDetailsData = mysqli_fetch_assoc($getSalaryDetailsQryExe)){ 
		$emp_id = $getSalaryDetailsData['emp_id'];
		$company_name = $getSalaryDetailsData['company_name'];
		$emp_name = $getSalaryDetailsData['emp_name'];
		$position = $getSalaryDetailsData['position_name'];
		$emp_code = $getSalaryDetailsData['emp_code'];
		
		$month  = $getSalaryDetailsData['month'];
		$dateObj   = DateTime::createFromFormat('!m', $month);
		$year = $getSalaryDetailsData['year'];
		$salary_month = $dateObj->format('F')." ".$year;
		
		$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		
		$basic_salary = $getSalaryDetailsData['salary_for_this_month'];
		$over_time_salary = round($getSalaryDetailsData['e_overtime'] * $getSalaryDetailsData['over_time_hour_rate']);
		
		$basic_salary = (int)$basic_salary;
		
		if($basic_salary > 0 || $over_time_salary > 0){
			$house_rent = $getSalaryDetailsData['housing_allowance'];
			
			//$conveyance = $getSalaryDetailsData['conveyance_allowance'];
			$perDay_conveyance_allowance = $getSalaryDetailsData['conveyance_allowance']/$total_days;
			$conveyance = round($perDay_conveyance_allowance * $getSalaryDetailsData['no_of_wdays']); 
			
			$food_allowance = $getSalaryDetailsData['food_allowance'];
			$medical_allowance = $getSalaryDetailsData['medical_allowance'];
			$other_allowances = $getSalaryDetailsData['allowance'];
			$other_allowances = (int)$other_allowances;
		}else{
			$house_rent = 0;
			$conveyance = 0;
			$food_allowance = 0;
			$medical_allowance = 0;
			$other_allowances = 0;
		}
		
		$loan_deductions = $getSalaryDetailsData['deduct_loan'];
		
		//leave salary or bonus
		$getLvsalBonusByIdQry = "SELECT * FROM leave_sal_bonus WHERE ref_emp_id='$emp_id' AND month='$month' AND year='$year'";
		$getLvsalBonusByIdQryExe = mysqli_query($conn, $getLvsalBonusByIdQry);
		$data = '';
		$leavSalary = $bonus = 0;
		if(mysqli_num_rows($getLvsalBonusByIdQryExe) > 0){
			$theLvsalBonusData = mysqli_fetch_assoc($getLvsalBonusByIdQryExe);
			$leavSalary = round($theLvsalBonusData['leave_Salary']);
			$bonus = round($theLvsalBonusData['bonus']);
		}
		
		$total_earnings = ($basic_salary + $house_rent + $conveyance + $food_allowance + $medical_allowance + $other_allowances +$over_time_salary + $leavSalary + $bonus) - $loan_deductions;
		
		$total_earnings = round($total_earnings);
		
		
		
		$total_wdays = $leave_days = $over_time_hrs = 0;
	
		$over_time_hrs = $getSalaryDetailsData['e_overtime'];
		
		if($total_days == $getSalaryDetailsData['no_of_wdays']){
			$total_wdays = $getSalaryDetailsData['no_of_wdays'];
		}else{
			$total_wdays =$getSalaryDetailsData['no_of_wdays'];
			$leave_days = $total_days - $getSalaryDetailsData['no_of_wdays'];
		}
	}
	$logo = WEB_URL.'assets/img/logo.png';
}


$html = '<table cellspacing="3" cellpadding="2">
             
			<tr>
			    <td style="text-align:center;"><h3 style="line-height:30px;">'.$company_name.'</h3></td>
			</tr>
			<tr>
				<td style="text-align:center;">SALARY PAY SLIP</td>
			</tr>
		</table>
		<table cellpadding="3">
		    <tr><td colspan="2"><img src="'.$logo.'" class="inv-logo" alt=""></td> 
				 
			 
				<td colspan="2" align="right" style="line-height: 50px;">Salary Month: '.$salary_month.'</td>
			</tr> 
			 
			<tr>
			    <td width="15%" style="text-align:left; border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 10px;">Name:</td>
				<td width="45%" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 10px;"><b>'.$emp_name.'</b></td>
				<td width="33%" style="text-align:left; border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 10px;">No Of Working Days:</td>
				<td width="7%" style="text-align:right; border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 10px;">'.$total_wdays.'</td>
				
			</tr>
			<tr>
			    <td width="15%" style="text-align:left; border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 10px;">Occupation:</td>
				<td width="45%" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 10px;">'.$position.'</td>
				<td width="33%" style="text-align:left; border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 10px;">Leave Days:</td>
				<td width="7%" style="text-align:right; border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 10px;">'.$leave_days.'</td>
			</tr>
			<tr>
			    <td width="15%" style="text-align:left; border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 10px;">Employee ID:</td>
				<td width="45%" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 10px;">'.$emp_code.'</td>
				<td width="33%" style="text-align:left;  border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 10px;">Over Time Hours:</td>
				<td width="7%" style="text-align:right;  border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 10px;">'.$over_time_hrs.'</td>
			</tr>
			 
		</table>
		
		
	 
           

				<table width="100%" cellspacing="0" style="border-collapse: collapse;">
<tbody>
    <tr>
	    
            		<td colspan="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; text-align: center; background-color: #003466; color: #fff; padding: 10px;"><strong>Earnings</strong></td>
            		<td colspan="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 10px; text-align: center; background-color: #003466; color: #fff;"><strong>Deductions</strong></td>
            	</tr>
            	<tr>
        			<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 5px;">Basic Salary</td>
                    <td valign="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right; padding: 5px;">'.$basic_salary.'</td>
                    <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 5px;">Loan Paid</td>
					<td valign="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right; padding: 5px;">'.$loan_deductions.'</td>
                </tr>
                <tr>
 					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 5px;">Overtime Salary</td>
 					<td valign="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right; padding: 5px;">'.$over_time_salary.'</td>
 					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
 					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
                </tr>
                <tr>
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 5px;">House Rent Allowance</td>
					<td valign="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right; padding: 5px;">'.$house_rent.'</td> 
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
 					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
				</tr>
				<tr>
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 5px;">Conveyance</td>
					<td valign="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right; padding: 5px;">'.$conveyance.'</td> 
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
 					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
				</tr>
				<tr>
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 5px;">Food  Allowance</td>
					<td valign="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right; padding: 5px;">'.$food_allowance.'</td> 
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
 					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
				</tr>
				<tr>
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 5px;">Medical  Allowance</td>
					<td valign="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right; padding: 5px;">'. $medical_allowance .'</td> 
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
 					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
				</tr>
				<tr>
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 5px;">Allowance</td>
					<td valign="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right; padding: 5px;">'.$other_allowances .'</td> 
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
 					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
				</tr>
				<tr>
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 5px;">Bonus</td>
					<td valign="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right; padding: 5px;">'.$bonus .'</td> 
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
 					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
				</tr>';
				
					$html .='<tr>
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 5px;">Leave Salary</td>
					<td valign="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right; padding: 5px;">'.$leavSalary .'</td> 
					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
 					<td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; border-right: 1px solid #000;"></td>
				</tr>';
				
				
				$html .= '<tr>
					<td colspan="3" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;"><strong>Total Payable</strong></td>
					<td valign="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right; padding: 5px;">Dirhams. '.$total_earnings.'</td>
				</tr>
				<tr>
					<td style="text-transform: capitalize;  border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;"><strong>Net Salary: </strong> </td>
					<td colspan="3" style="border-bottom: 1px solid #000; border-right: 1px solid #000; font-size: 11px; text-align:right;"> <b>Dirhams.</b>( '. numbersToWords($total_earnings).' )</td>
				</tr>
				 
	     
	 
	  	</tbody>
</table>
	 
       
 ';
	
//echo $html;	
		
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('payslip.pdf', 'I');


?>