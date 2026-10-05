<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;

$dompdf = new Dompdf();
ob_start();
?>

<h3>Employee Gratuity Report</h3>
<table border="1" width="100%" cellpadding="5">
<tr>
<th>Name</th>
<th>Emp Code</th>
<th>Joining Date</th>
<th>Payable</th>
<th>Saved</th>
<th>Difference</th>
</tr>

<?php
include('../config/db.php');
$qry = mysqli_query($conn, "SELECT emp_name, emp_code, joining_date FROM employee WHERE employe_status = 0");
while($r = mysqli_fetch_assoc($qry)){
    echo "<tr>
        <td>{$r['emp_name']}</td>
        <td>{$r['emp_code']}</td>
        <td>{$r['joining_date']}</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
    </tr>";
}
?>
</table>

<?php
$html = ob_get_clean();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("gratuity_report.pdf", ["Attachment" => true]);
