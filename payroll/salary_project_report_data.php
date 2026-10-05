<?php

// Keep the screen and PDF filters and payroll selection identical.
function projectSalaryFilters(array $input)
{
    $integer = function ($key, $min, $max) use ($input) {
        $value = $input[$key] ?? null;
        if (!is_scalar($value)) {
            return false;
        }
        return filter_var($value, FILTER_VALIDATE_INT, array(
            'options' => array('min_range' => $min, 'max_range' => $max)
        ));
    };
    $company = !isset($input['cmpy']) || $input['cmpy'] === ''
        ? '' : $integer('cmpy', 1, PHP_INT_MAX);
    $project = $integer('project_id', 1, PHP_INT_MAX);
    $month = $integer('month', 1, 12);
    $year = $integer('year', 2020, 2050);
    return array(
        'company' => $company,
        'project_id' => $project,
        'month' => $month,
        'year' => $year,
        'valid' => $company !== false && $project !== false && $month !== false && $year !== false
    );
}

function projectSalaryRows($conn, array $filters, $workStatus)
{
    if (!$filters['valid']) {
        throw new InvalidArgumentException('Invalid project salary report filters.');
    }
    $sql = "SELECT ep.*, e.passport_number
        FROM employee_payroll ep
        JOIN employee e ON e.emp_id = ep.emp_id_
        WHERE ep.month = ? AND ep.year = ? AND ep.project_id_ = ? AND ep.work_status_ = ?
        AND (e.employe_status = 0 OR (e.employe_status = 1 AND EXISTS (
            SELECT 1 FROM employee_cancellation EC
            WHERE EC.ref_emp_id = e.emp_id
            AND (YEAR(EC.cancel_date) > ? OR (YEAR(EC.cancel_date) = ? AND MONTH(EC.cancel_date) >= ?))
        )))";
    $month = $filters['month'];
    $year = $filters['year'];
    $project = $filters['project_id'];
    $company = $filters['company'];
    if ($company !== '') {
        $sql .= ' AND ep.ref_comp_id = ?';
    }
    $stmt = mysqli_prepare($conn, $sql . ' ORDER BY e.emp_id');
    if ($company === '') {
        mysqli_stmt_bind_param($stmt, 'iiisiii', $month, $year, $project, $workStatus, $year, $year, $month);
    } else {
        mysqli_stmt_bind_param($stmt, 'iiisiiii', $month, $year, $project, $workStatus, $year, $year, $month, $company);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function projectSalaryAllowances(array $row)
{
    $allowances = array(
        'allowance' => (float) ($row['allowance'] ?? 0),
        'conveyance' => (float) ($row['conveyance_a'] ?? 0),
        'food' => (float) ($row['food_a'] ?? 0),
        'medical' => (float) ($row['medical_a'] ?? 0),
        'housing' => (float) ($row['housing_a'] ?? 0)
    );
    if ((int) $row['year'] * 100 + (int) $row['month'] < 202310) {
        $allowances = array('allowance' => array_sum($allowances),
            'conveyance' => 0, 'food' => 0, 'medical' => 0, 'housing' => 0);
    }
    return $allowances;
}
