<?php
include('../config/db.php');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=gratuity_report.xls");

echo "<table border='1'>
<tr>
<th>Name</th>
<th>Emp Code</th>
<th>Designation</th>
<th>Joining Date</th>
<th>Salary</th>
<th>Payable</th>
<th>Saved</th>
<th>Difference</th>
</tr>";

$qry = mysqli_query($conn, "SELECT emp_name, emp_code, joining_date, salary FROM employee WHERE employe_status = 0");

while($r = mysqli_fetch_assoc($qry)){
    echo "<tr>
        <td>{$r['emp_name']}</td>
        <td>{$r['emp_code']}</td>
        <td>-</td>
        <td>{$r['joining_date']}</td>
        <td>{$r['salary']}</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
    </tr>";
}
echo "</table>";
exit;
?>
