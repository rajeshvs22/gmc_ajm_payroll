<?php
require __DIR__ . '/../header.php';
require_once __DIR__ . '/increment_report_data.php';

$filters = incrementReportFilters($_GET);
$reportError = '';
$rows = $companies = $employees = $projects = array();
if (!$filters['valid']) {
    $reportError = 'Please choose valid report filters.';
}
try {
    $companies = mysqli_fetch_all(mysqli_query($conn, 'SELECT comp_id, company_name FROM company_master ORDER BY company_name'), MYSQLI_ASSOC);
    $projects = mysqli_fetch_all(mysqli_query($conn, 'SELECT proj_id, proj_name FROM project_details ORDER BY proj_name'), MYSQLI_ASSOC);
    $employeeStmt = mysqli_prepare($conn, 'SELECT e.emp_id, e.emp_code, e.emp_name FROM employee e
        WHERE e.work_status = ? AND e.employe_status = 0
        AND EXISTS (SELECT 1 FROM salary_increment si WHERE si.ref_emp_id = e.emp_id AND si.increment_amt > 0)
        ORDER BY e.emp_name, e.emp_code');
    mysqli_stmt_bind_param($employeeStmt, 's', $work_status);
    mysqli_stmt_execute($employeeStmt);
    $employees = mysqli_fetch_all(mysqli_stmt_get_result($employeeStmt), MYSQLI_ASSOC);
    mysqli_stmt_close($employeeStmt);
    if ($filters['valid']) {
        $rows = incrementReportRows($conn, $filters, $work_status);
    }
} catch (Throwable $error) {
    error_log('Increment report failed: ' . $error->getMessage());
    $reportError = 'The increment report could not be loaded. Please try again.';
}
$months = array(1 => 'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE',
    'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER');
?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Increment Report</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= WEB_URL ?>dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Increment Report</li>
                    </ul>
                </div>
            </div>
        </div>

        <form method="GET" action="<?= WEB_URL ?>reports/increment_report.php">
            <div class="row filter-row">
                <div class="col-sm-6 col-lg-2">
                    <div class="form-group form-focus select-focus">
                        <select class="select" name="month" id="increment_month">
                            <option value="">All Months</option>
                            <?php foreach ($months as $number => $name) { ?>
                                <option value="<?= $number ?>" <?= $filters['month'] === $number ? 'selected' : '' ?>><?= $name ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label" for="increment_month">Month</label>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="form-group form-focus select-focus">
                        <select class="select" name="year" id="increment_year">
                            <option value="">All Years</option>
                            <?php for ($year = 2020; $year <= 2050; $year++) { ?>
                                <option value="<?= $year ?>" <?= $filters['year'] === $year ? 'selected' : '' ?>><?= $year ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label" for="increment_year">Year</label>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="form-group form-focus select-focus">
                        <select class="select" name="emp_id" id="increment_employee">
                            <option value="">All Employees</option>
                            <?php foreach ($employees as $employee) { ?>
                                <option value="<?= (int) $employee['emp_id'] ?>" <?= $filters['emp_id'] === (int) $employee['emp_id'] ? 'selected' : '' ?>><?= incrementReportEscape($employee['emp_code'] . ' - ' . $employee['emp_name']) ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label" for="increment_employee">Employee</label>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="form-group form-focus select-focus">
                        <select class="select" name="project_id" id="increment_project">
                            <option value="">All Projects</option>
                            <?php foreach ($projects as $project) { ?>
                                <option value="<?= (int) $project['proj_id'] ?>" <?= $filters['project_id'] === (int) $project['proj_id'] ? 'selected' : '' ?>><?= incrementReportEscape($project['proj_name']) ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label" for="increment_project">Project</label>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="form-group form-focus select-focus">
                        <select class="select" name="cmpy" id="increment_company">
                            <option value="">All Companies</option>
                            <?php foreach ($companies as $company) { ?>
                                <option value="<?= (int) $company['comp_id'] ?>" <?= $filters['cmpy'] === (int) $company['comp_id'] ? 'selected' : '' ?>><?= incrementReportEscape($company['company_name']) ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label" for="increment_company">Company</label>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <button class="btn btn-success btn-block" type="submit">Go</button>
                    <a class="btn btn-link btn-block" href="<?= WEB_URL ?>reports/increment_report.php">Reset</a>
                </div>
            </div>
        </form>

        <?php if ($reportError !== '') { ?>
            <div class="alert alert-danger" role="alert"><?= incrementReportEscape($reportError) ?></div>
        <?php } else { ?>
            <p class="text-muted">Basic salary before the increment; total salary after the increment.</p>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table" id="increment-report-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Employee Name</th>
                                    <th>Date</th>
                                    <th class="text-right">Basic Salary</th>
                                    <th class="text-right">Increment Amount</th>
                                    <th class="text-right">Total Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row) {
                                    $date = $row['increment_dt'];
                                    $validDate = !empty($date) && $date !== '0000-00-00';
                                ?>
                                    <tr>
                                        <td><?= incrementReportEscape($row['emp_code']) ?></td>
                                        <td><?= incrementReportEscape($row['emp_name']) ?></td>
                                        <td data-order="<?= $validDate ? incrementReportEscape($date) : '' ?>"><?= $validDate ? date('d-m-Y', strtotime($date)) : '-' ?></td>
                                        <td class="text-right"><?= number_format((float) $row['previous_salary'], 2) ?></td>
                                        <td class="text-right"><?= number_format((float) $row['increment_amt'], 2) ?></td>
                                        <td class="text-right"><?= number_format((float) $row['current_inc_salary'], 2) ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php require __DIR__ . '/../footer.php'; ?>
<script>
$(document).ready(function () {
    $('#increment-report-table').DataTable({
        pageLength: 25,
        order: [[2, 'desc']],
        language: { emptyTable: 'No increments found for the selected filters.' }
    });
});
</script>
</body>
</html>
