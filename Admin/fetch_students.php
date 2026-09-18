<?php
header('Content-Type: application/json');

// Mock Data Source Array
$students = [
    ['id' => 1, 'en'=>'EN2025001','name'=>'Rahul Patel','email'=>'rahul@gmail.com','phone'=>'9800000001','semester'=>'Semester 2','status'=>'verified','avatar'=>'avatar-1.jpg'],
    ['id' => 2, 'en'=>'EN2025002','name'=>'Priya Shah','email'=>'priya@gmail.com','phone'=>'9800000002','semester'=>'Semester 3','status'=>'pending','avatar'=>'avatar-2.jpg'],
    ['id' => 3, 'en'=>'EN2025003','name'=>'Amit Kumar','email'=>'amit@gmail.com','phone'=>'9800000003','semester'=>'Semester 4','status'=>'rejected','avatar'=>'avatar-3.jpg'],
    ['id' => 4, 'en'=>'EN2025004','name'=>'Neha Joshi','email'=>'neha@gmail.com','phone'=>'9800000004','semester'=>'Semester 5','status'=>'active','avatar'=>'avatar-4.jpg'],
    ['id' => 5, 'en'=>'EN2025005','name'=>'Riya Mehta','email'=>'riya@gmail.com','phone'=>'9800000005','semester'=>'Semester 6','status'=>'inactive','avatar'=>'avatar-5.jpg'],
    ['id' => 6, 'en'=>'EN2025006','name'=>'Karan Desai','email'=>'karan@gmail.com','phone'=>'9800000006','semester'=>'Semester 1','status'=>'blocked','avatar'=>'avatar-6.jpg'],
    ['id' => 7, 'en'=>'EN2025007','name'=>'Sneha Patel','email'=>'sneha@gmail.com','phone'=>'9800000007','semester'=>'Semester 2','status'=>'verified','avatar'=>'avatar-7.jpg'],
    ['id' => 8, 'en'=>'EN2025008','name'=>'Vivek Shah','email'=>'vivek@gmail.com','phone'=>'9800000008','semester'=>'Semester 3','status'=>'pending','avatar'=>'avatar-8.jpg'],
    ['id' => 9, 'en'=>'EN2025009','name'=>'Anjali Patel','email'=>'anjali@gmail.com','phone'=>'9800000009','semester'=>'Semester 4','status'=>'rejected','avatar'=>'avatar-9.jpg'],
    ['id' => 10, 'en'=>'EN2025010','name'=>'Rohit Sharma','email'=>'rohit@gmail.com','phone'=>'9800000010','semester'=>'Semester 5','status'=>'active','avatar'=>'avatar-10.jpg'],
    ['id' => 11, 'en'=>'EN2025011','name'=>'Pooja Verma','email'=>'pooja@gmail.com','phone'=>'9800000011','semester'=>'Semester 6','status'=>'inactive','avatar'=>'avatar-1.jpg'],
    ['id' => 12, 'en'=>'EN2025012','name'=>'Jay Mehta','email'=>'jay@gmail.com','phone'=>'9800000012','semester'=>'Semester 1','status'=>'blocked','avatar'=>'avatar-2.jpg'],
    ['id' => 13, 'en'=>'EN2025013','name'=>'Nisha Singh','email'=>'nisha@gmail.com','phone'=>'9800000013','semester'=>'Semester 2','status'=>'verified','avatar'=>'avatar-3.jpg'],
    ['id' => 14, 'en'=>'EN2025014','name'=>'Arjun Patel','email'=>'arjun@gmail.com','phone'=>'9800000014','semester'=>'Semester 3','status'=>'pending','avatar'=>'avatar-4.jpg'],
    ['id' => 15, 'en'=>'EN2025015','name'=>'Krishna Shah','email'=>'krishna@gmail.com','phone'=>'9800000015','semester'=>'Semester 4','status'=>'rejected','avatar'=>'avatar-5.jpg'],
    ['id' => 16, 'en'=>'EN2025016','name'=>'Divya Joshi','email'=>'divya@gmail.com','phone'=>'9800000016','semester'=>'Semester 5','status'=>'active','avatar'=>'avatar-6.jpg'],
    ['id' => 17, 'en'=>'EN2025017','name'=>'Harsh Patel','email'=>'harsh@gmail.com','phone'=>'9800000017','semester'=>'Semester 6','status'=>'inactive','avatar'=>'avatar-7.jpg']
];

// Capture dynamic search parameters
$searchText = isset($_POST['search']) ? trim($_POST['search']) : '';
$statusFilter = isset($_POST['status']) ? trim($_POST['status']) : '';
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$perPage = isset($_POST['per_page']) ? (int)$_POST['per_page'] : 5;

if ($page < 1) $page = 1;
if ($perPage < 1) $perPage = 5;

// Global counter calculations (prior to text match mutations)
$globalActive = 0;
$globalInactive = 0;
$globalTotal = count($students);

foreach ($students as $s) {
    if ($s['status'] === 'active' || $s['status'] === 'verified') {
        $globalActive++;
    } else {
        $globalInactive++;
    }
}

// Implement search text matching algorithms
$filteredStudents = [];
foreach ($students as $s) {
    // Drop records if filtering target status isn't matched
    if ($statusFilter !== '' && $s['status'] !== $statusFilter) {
        continue;
    }
    
    // Apply key term checks across text fields
    if ($searchText !== '') {
        $term = strtolower($searchText);
        $matchName  = (strpos(strtolower($s['name']), $term) !== false);
        $matchEn    = (strpos(strtolower($s['en']), $term) !== false);
        $matchEmail = (strpos(strtolower($s['email']), $term) !== false);
        
        if (!$matchName && !$matchEn && !$matchEmail) {
            continue;
        }
    }
    
    $filteredStudents[] = $s;
}

// Calculate slice indexes
$totalFiltered = count($filteredStudents);
$totalPages = ceil($totalFiltered / $perPage);
if ($page > $totalPages && $totalPages > 0) $page = $totalPages;

$startIndex = ($page - 1) * $perPage;
$pageItems = array_slice($filteredStudents, $startIndex, $perPage);

// Construct markup string blocks
$htmlRows = '';
$rowIndex = $startIndex + 1;

if ($totalFiltered === 0) {
    $htmlRows = '<tr><td colspan="10" class="text-center py-4 text-muted">No student records match criteria.</td></tr>';
} else {
    foreach ($pageItems as $student) {
        $status = $student['status'];
        $displayStatus = ucfirst($status);
        
        // Match status property styles to correct theme rules
        $pillClass = 'uni-pill--pending';
        if ($status === 'active' || $status === 'verified') {
            $pillClass = 'uni-pill--active';
        } elseif ($status === 'inactive' || $status === 'rejected' || $status === 'blocked') {
            $pillClass = 'uni-pill--inactive';
        }
        
        // Compute toggle state values
        $isChecked = ($status === 'active' || $status === 'verified') ? 'checked' : '';
        
        $htmlRows .= '<tr data-id="' . $student['id'] . '">';
        $htmlRows .= '<td class="uni-date">' . $rowIndex++ . '</td>';
        $htmlRows .= '<td><img src="assets/images/user/' . $student['avatar'] . '" alt="Student Profile picture" class="uni-avatar"></td>';
        $htmlRows .= '<td><span class="uni-enrollment">' . htmlspecialchars($student['en']) . '</span></td>';
        $htmlRows .= '<td><span class="uni-name">' . htmlspecialchars($student['name']) . '</span></td>';
        $htmlRows .= '<td class="uni-email">' . htmlspecialchars($student['email']) . '</td>';
        $htmlRows .= '<td class="uni-phone">' . htmlspecialchars($student['phone']) . '</td>';
        $htmlRows .= '<td class="uni-semester">' . htmlspecialchars($student['semester']) . '</td>';
        
        // Status Column Implementation
        $htmlRows .= '<td>';
        $htmlRows .= '<span class="uni-pill status-badge ' . $pillClass . '">';
        $htmlRows .= '<span class="dot"></span>';
        $htmlRows .= '<span class="pill-label">' . $displayStatus . '</span>';
        $htmlRows .= '</span>';
        $htmlRows .= '</td>';
        
        // Toggle Switch Column Implementation
        $htmlRows .= '<td>';
        $htmlRows .= '<div class="form-check form-switch mb-0">';
        $htmlRows .= '<input class="form-check-input status-toggle" type="checkbox" role="switch" ' . $isChecked . '>';
        $htmlRows .= '</div>';
        $htmlRows .= '</td>';
        
        // Action Icons Column
        $htmlRows .= '<td class="text-end">';
        $htmlRows .= '<div class="d-inline-flex gap-1">';
        $htmlRows .= '<a href="ViewStudent.php?id=' . $student['id'] . '" class="uni-action-btn uni-action-view" title="View"><i class="bi bi-eye"></i></a>';
        $htmlRows .= '<a href="EditStudent.php?id=' . $student['id'] . '" class="uni-action-btn uni-action-edit" title="Edit"><i class="bi bi-pencil"></i></a>';
        $htmlRows .= '<a href="javascript:void(0)" class="uni-action-btn uni-action-delete" title="Delete"><i class="bi bi-trash"></i></a>';
        $htmlRows .= '</div>';
        $htmlRows .= '</td>';
        
        $htmlRows .= '</tr>';
    }
}

// Package structured elements to match frontend expected parameters exactly
echo json_encode([
    'html' => $htmlRows,
    'totalCount' => $globalTotal,
    'activeCount' => $globalActive,
    'inactiveCount' => $globalInactive,
    'totalFiltered' => $totalFiltered,
    'totalPages' => $totalPages,
    'start' => $startIndex,
    'end' => $startIndex + count($pageItems)
]);