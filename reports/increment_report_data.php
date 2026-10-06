<?php

function incrementReportFilters(array $input)
{
    $ranges = array(
        'month' => array(1, 12),
        'year' => array(2020, 2050),
        'emp_id' => array(1, PHP_INT_MAX),
        'project_id' => array(1, PHP_INT_MAX),
        'cmpy' => array(1, PHP_INT_MAX)
    );
    $filters = array('valid' => true);
    foreach ($ranges as $key => $range) {
        $value = $input[$key] ?? '';
        if ($value === '') {
            $filters[$key] = '';
            continue;
        }
        $number = is_scalar($value) ? filter_var($value, FILTER_VALIDATE_INT, array(
            'options' => array('min_range' => $range[0], 'max_range' => $range[1])
        )) : false;
        $filters[$key] = $number;
        if ($number === false) {
            $filters['valid'] = false;
        }
    }
    return $filters;
}

function incrementReportRows($conn, array $filters, $workStatus)
{
    if (!$filters['valid']) {
        throw new InvalidArgumentException('Invalid increment report filters.');
    }
    $sql = 'SELECT si.increment_id, e.emp_code, e.emp_name, si.increment_dt,
            si.previous_salary, si.previous_allowance, si.increment_amt,
            si.increment_allowance, si.current_inc_salary
        FROM salary_increment si
        JOIN employee e ON e.emp_id = si.ref_emp_id
        WHERE e.work_status = ? AND e.employe_status = 0 AND si.increment_amt > 0';
    $types = 's';
    $params = array($workStatus);
    $conditions = array(
        'month' => 'MONTH(si.increment_dt) = ?',
        'year' => 'YEAR(si.increment_dt) = ?',
        'emp_id' => 'si.ref_emp_id = ?',
        'cmpy' => 'e.ref_comp_id = ?'
    );
    foreach ($conditions as $key => $condition) {
        if ($filters[$key] !== '') {
            $sql .= ' AND ' . $condition;
            $types .= 'i';
            $params[] = $filters[$key];
        }
    }
    if ($filters['project_id'] !== '') {
        // EXISTS avoids duplicate increments when an employee has multiple assignments.
        $sql .= " AND EXISTS (
            SELECT 1 FROM project_employees pe
            WHERE pe.ref_emp_id = si.ref_emp_id AND pe.ref_proj_id = ?
            AND (pe.emp_joined_date IS NULL OR YEAR(pe.emp_joined_date) = 0 OR pe.emp_joined_date <= si.increment_dt)
            AND (pe.emp_quite_date IS NULL OR YEAR(pe.emp_quite_date) = 0 OR pe.emp_quite_date >= si.increment_dt)
        )";
        $types .= 'i';
        $params[] = $filters['project_id'];
    }
    $stmt = mysqli_prepare($conn, $sql . ' ORDER BY si.increment_dt ASC, e.emp_code, si.increment_id ASC');
    try {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } finally {
        mysqli_stmt_close($stmt);
    }
}

function incrementReportEscape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
