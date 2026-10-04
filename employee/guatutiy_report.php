<?php

include('../header.php');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<style>
/* Fix floating label overlap for datetimepicker */
.form-focus .datetimepicker {
    padding-top: 22px !important;
    height: 50px;
}

.form-focus .datetimepicker:focus + .focus-label,
.form-focus .datetimepicker:not(:placeholder-shown) + .focus-label,
.form-focus .datetimepicker.has-value + .focus-label {
    top: 6px;
    font-size: 11px;
    color: #999;
}

/* Force label to stay above */
.form-focus .focus-label {
    top: 6px !important;
    transform: none !important;
}

</style>
<style>
@media print {

    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    body {
        margin: 0;
        padding: 0;
    }

    /* Hide everything except report */
    body * {
        visibility: hidden;
    }

    .table-responsive,
    .table-responsive * {
        visibility: visible;
    }

    .table-responsive {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        overflow: visible !important;
    }

    table {
        width: 100% !important;
        table-layout: fixed;
        font-size: 10px;
    }

    th, td {
        word-wrap: break-word;
        white-space: normal !important;
        padding: 4px !important;
    }

    /* Hide buttons & filters */
    .btn,
    .filter-row,
    .page-header {
        display: none !important;
    }
}

</style>

<script>
$('.datetimepicker').each(function () {
    if ($(this).val() !== '') {
        $(this).addClass('has-value');
    }
});

$('.datetimepicker').on('change', function () {
    $(this).addClass('has-value');
});
</script>
<script>
// function exportExcel(event) {
//     if(event) event.preventDefault();

//     let table = document.getElementById("datatable-no-sorting2");
//     let html = table.outerHTML.replace(/ /g, '%20');

//     let filename = 'Employee_Gratuity_Report.xls';

//     let link = document.createElement("a");
//     link.href = 'data:application/vnd.ms-excel,' + html;
//     link.download = filename;

//     document.body.appendChild(link);
//     link.click();
//     document.body.removeChild(link);
// }
// 
</script>

<script>
function exportExcel() {
    const table = document.getElementById("datatable-no-sorting2");

    const wb = XLSX.utils.table_to_book(table, { sheet: "Gratuity Report" });

    XLSX.writeFile(wb, "Employee_Gratuity_Report.xlsx");
}
</script>


<script>
function exportPDF(event) {
    if(event) event.preventDefault();

    const element = document.getElementById("reportArea");

    html2pdf(element, {
        margin: 10,
        filename: 'Employee_Gratuity_Report.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: {
            scale: 2,
            useCORS: true
        },
        jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'landscape'
        }
    });
}
</script>

<?php


//FILTER OPTION
$dep = $emp_name = $cmpy = $emp_code = $condition = $company = "";
$as_on_raw = "";

if(isset($_GET['dep']) && !empty($_GET['dep'])){
    $dep = (int)$_GET['dep'];
    $condition .= ' AND division = '.$dep;
}
if(isset($_GET['emp_name']) && !empty($_GET['emp_name'])){
    $emp_name = mysqli_real_escape_string($conn, $_GET['emp_name']);
    $condition .= " AND emp_name LIKE '%".$emp_name."%'";
}
if(isset($_GET['cmpy']) && !empty($_GET['cmpy'])){
    $company = (int)$_GET['cmpy'];
    $condition .= ' AND ref_comp_id = '.$company;
}
if(isset($_GET['emp_code']) && !empty($_GET['emp_code'])){
    $emp_code = mysqli_real_escape_string($conn, $_GET['emp_code']);
    $condition .= " AND emp_code = '".$emp_code."'";
}

$from_date_raw = "";
$to_date_raw = "";

if(isset($_GET['from_date']) && !empty($_GET['from_date'])){
    $from_date_raw = $_GET['from_date'];
    $from_date = date("Y-m-d", strtotime(str_replace('/', '-', $from_date_raw)));
}

if(isset($_GET['to_date']) && !empty($_GET['to_date'])){
    $to_date_raw = $_GET['to_date'];
    $to_date = date("Y-m-d", strtotime(str_replace('/', '-', $to_date_raw)));
} else {
    $to_date = date("Y-m-d");
    $to_date_raw = date("d/m/Y");
}

$from_date_display = !empty($from_date_raw) ? $from_date_raw : 'N/A';
$to_date_display   = !empty($to_date_raw) ? $to_date_raw : date('d/m/Y');


function gratuityAmount($joining_date, $end_date, $salary)
{
    if(strtotime($end_date) < strtotime($joining_date)){
        return 0;
    }

    $total_days = floor((strtotime($end_date) - strtotime($joining_date)) / 86400);
    $years = floor($total_days / 365);
    $extra_days = $total_days - ($years * 365);

    $daily_rate = $salary / 30;

    // First 5 years
    $first5_years = min($years, 5);
    $first5_amount = $first5_years * 21 * $daily_rate;

    // After 5 years
    $after5_years = max(0, $years - 5);
    $after5_amount = $after5_years * 30 * $daily_rate;

    // Extra days
    $extra_amount = $extra_days * $daily_rate * 0.083;

    return round($first5_amount + $after5_amount + $extra_amount, 2);
}


?>
<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Employee Gratuity Report</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Gratuity Report</li>
                    </ul>
                </div>
            </div>
        </div>

        <form action="" method="GET">
        <div class="row filter-row">

            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus select-focus">
                    <select name="cmpy" id="cmpy" class="form-control">
                        <option value="">Select Company</option>
                        <?php
                        $companyQry = mysqli_query($conn, "SELECT * FROM company_master WHERE visibility=1");
                        while ($c = mysqli_fetch_assoc($companyQry)) {
                            $selected = ($company == $c['comp_id']) ? 'selected' : '';
                            echo '<option value="' . $c['comp_id'] . '" ' . $selected . '>' . $c['company_name'] . '</option>';
                        }
                        ?>
                        
                    </select>
                    <label class="focus-label">Company</label>
                    
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <input type="text" class="form-control floating" name="emp_code" value="<?= htmlspecialchars($emp_code) ?>">
                    <label class="focus-label">Employee CODE</label>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <input type="text" class="form-control floating" name="emp_name" value="<?= htmlspecialchars($emp_name) ?>">
                    <label class="focus-label">Employee Name</label>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <label class="focus-label">From Date</label>
                    <input type="text"
                        class="form-control datetimepicker"
                        name="from_date"
                        value="<?= htmlspecialchars($from_date_raw) ?>">
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <label class="focus-label">To Date</label>
                    <input type="text"
                        class="form-control datetimepicker"
                        name="to_date"
                        value="<?= htmlspecialchars($to_date_raw) ?>">
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <button type="submit" name="search" value="search" class="btn btn-success btn-block m-b-20"> Search </button>               
            </div>
             
                <div class="mb-2">
                   <button type="button" class="btn btn-warning btn-sm" onclick="exportExcel()" style="margin-left: 16px;">
                        Excel
                    </button>

                    <button type="button" class="btn btn-danger btn-sm" onclick="exportPDF()">
                        PDF
                    </button>

                    <button type="button" onclick="window.print()" class="btn btn-secondary btn-sm">
                        Print
                    </button>

                </div>
        </div>
        </form>

        <!-- /Search Filter -->

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <div id="reportArea">
                    <!-- page-header -->
                        <table class="table table-striped custom-table datatable-no-sorting" id="datatable-no-sorting2">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <!-- <th>Company</th> -->
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Joining Date</th>
                                    <th>First 5 Years<br/>(days/year)</th>
                                    <th>After 5 Years<br/>(days/year)</th>
                                    <th>Duration (Yr.Month)</th>
                                    <th>Monthly Basic Salary</th>
                                    <th>Payable Amount (DHS)</th>
                                    <th>ESFAND / Saved (DHS) <small>(<?= htmlspecialchars($from_date_display) ?>)</small></th>
                                    <th>Difference (DHS) <small>(<?= htmlspecialchars($to_date_display) ?>)</small></th>
                                </tr>
                            </thead>
                            <tbody><?php
                                $getAllEmpQry = array();
                                // Fetch employees with optional saved gratuity join
                                if(isset($_GET['search']) && !empty($_GET['search'])){
                                        $getAllEmpQry = "SELECT e.*, cm.company_name,
                                                (SELECT department_name FROM department_master WHERE dep_id = e.division) AS dep_name,
                                                (SELECT position_name FROM position_master WHERE position_id = e.position) AS position_name
                                                FROM employee AS e
                                                LEFT JOIN company_master cm ON cm.comp_id = e.ref_comp_id
                                                WHERE 1 $condition
                                                ORDER BY e.emp_name ASC";
                                }
                                

                                $qryExe = mysqli_query($conn, $getAllEmpQry);
                                if(mysqli_num_rows($qryExe) > 0){
                                    $sl_no = 1;
                                    while($row = mysqli_fetch_assoc($qryExe)){

                                        $joining_date = $row['joining_date'];
                                        $salary = (float)$row['salary'];

                                        $is_joining_empty = (
                                            empty($joining_date) ||
                                            $joining_date == '0000-00-00'
                                        );

                                        if ($is_joining_empty) {
                                            // All values = 0
                                            $joining_date_display = '0';
                                            $payable_amount = 0;
                                            $saved_amount = 0;
                                            $difference = 0;
                                            $duration_year_month = '0.00';
                                            $after_5_years = 0;

                                        } else {

                                                // Normal calculations
                                                $joining_date_display = date('d-m-Y', strtotime($joining_date));


                                                if($salary <= 0) $salary = 0;

                                                // Total Payable → Joining → To Date
                                                $payable_amount = gratuityAmount($joining_date, $to_date, $salary);

                                                // Saved → Joining → From Date
                                                if(!empty($from_date)){
                                                    $saved_amount = gratuityAmount($joining_date, $from_date, $salary);
                                                } else {
                                                    $saved_amount = 0;
                                                }

                                                // Difference
                                                $difference = round($payable_amount - $saved_amount, 2);

                                                // Years calculation only for showing 30 or 0
                                                $service_years = floor((strtotime($to_date) - strtotime($joining_date)) / (365*86400));
                                                $after_5_years = ($service_years > 5) ? 30 : 0;
                                        }

                                        ?>
                                        <tr>
                                            <td><?= $sl_no ?></td>
                                            <!-- <td><?= htmlspecialchars($row['company_name']) ?></td> -->
                                            <td><?= htmlspecialchars($row['emp_name']) ?><br>
                                                <small><?= htmlspecialchars($row['emp_code']) ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($row['position_name']) ?></td>
                                            <td><?= $joining_date_display ?></td>
                                            <!-- First 5 years always 21 -->
                                            <td style="text-align:center;">21</td>

                                            <!-- After 5 years: 30 or 0 -->
                                            <td style="text-align:center;"><?= $after_5_years ?></td>

                                            <!-- Duration (Year.Month) -->
                                            <?php
                                                $total_days = floor((strtotime($to_date) - strtotime($joining_date)) / 86400);
                                                $years = floor($total_days / 365);
                                                $months = floor(($total_days - ($years * 365)) / 30);
                                                $duration_year_month = sprintf("%d.%02d", $years, $months);
                                            ?>
                                            <td><?= $duration_year_month ?></td>

                                            <td><?= number_format($salary, 2) ?></td>

                                            <!-- Payable -->
                                            <td><b><?= number_format($payable_amount, 2) ?></b></td>

                                            <!-- ESFAND / Saved -->
                                            <td><?= number_format($saved_amount, 2) ?></td>

                                            <!-- Difference -->
                                            <td><?= number_format($difference, 2) ?></td>
                                        </tr><?php
                                        $sl_no++;
                                    }
                                } else {
                                    echo '<tr><td colspan="11">No employees found.</td></tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                    <!-- table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require '../footer.php'
?>


</body>
</html>
