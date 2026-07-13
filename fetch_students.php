<?php
$students=[
['en'=>'EN2025001','name'=>'Rahul Patel','email'=>'rahul@gmail.com','phone'=>'9800000001','semester'=>'Semester 2','status'=>'verified','avatar'=>'avatar-1.jpg'],
['en'=>'EN2025002','name'=>'Priya Shah','email'=>'priya@gmail.com','phone'=>'9800000002','semester'=>'Semester 3','status'=>'pending','avatar'=>'avatar-2.jpg'],
['en'=>'EN2025003','name'=>'Amit Kumar','email'=>'amit@gmail.com','phone'=>'9800000003','semester'=>'Semester 4','status'=>'rejected','avatar'=>'avatar-3.jpg'],
['en'=>'EN2025004','name'=>'Neha Joshi','email'=>'neha@gmail.com','phone'=>'9800000004','semester'=>'Semester 5','status'=>'active','avatar'=>'avatar-4.jpg'],
['en'=>'EN2025005','name'=>'Riya Mehta','email'=>'riya@gmail.com','phone'=>'9800000005','semester'=>'Semester 6','status'=>'inactive','avatar'=>'avatar-5.jpg'],
['en'=>'EN2025006','name'=>'Karan Desai','email'=>'karan@gmail.com','phone'=>'9800000006','semester'=>'Semester 1','status'=>'blocked','avatar'=>'avatar-6.jpg'],
['en'=>'EN2025007','name'=>'Sneha Patel','email'=>'sneha@gmail.com','phone'=>'9800000007','semester'=>'Semester 2','status'=>'verified','avatar'=>'avatar-7.jpg'],
['en'=>'EN2025008','name'=>'Vivek Shah','email'=>'vivek@gmail.com','phone'=>'9800000008','semester'=>'Semester 3','status'=>'pending','avatar'=>'avatar-8.jpg'],
['en'=>'EN2025009','name'=>'Anjali Patel','email'=>'anjali@gmail.com','phone'=>'9800000009','semester'=>'Semester 4','status'=>'rejected','avatar'=>'avatar-9.jpg'],
['en'=>'EN2025010','name'=>'Rohit Sharma','email'=>'rohit@gmail.com','phone'=>'9800000010','semester'=>'Semester 5','status'=>'active','avatar'=>'avatar-10.jpg'],
['en'=>'EN2025011','name'=>'Pooja Verma','email'=>'pooja@gmail.com','phone'=>'9800000011','semester'=>'Semester 6','status'=>'inactive','avatar'=>'avatar-1.jpg'],
['en'=>'EN2025012','name'=>'Jay Mehta','email'=>'jay@gmail.com','phone'=>'9800000012','semester'=>'Semester 1','status'=>'blocked','avatar'=>'avatar-2.jpg'],
['en'=>'EN2025013','name'=>'Nisha Singh','email'=>'nisha@gmail.com','phone'=>'9800000013','semester'=>'Semester 2','status'=>'verified','avatar'=>'avatar-3.jpg'],
['en'=>'EN2025014','name'=>'Arjun Patel','email'=>'arjun@gmail.com','phone'=>'9800000014','semester'=>'Semester 3','status'=>'pending','avatar'=>'avatar-4.jpg'],
['en'=>'EN2025015','name'=>'Krishna Shah','email'=>'krishna@gmail.com','phone'=>'9800000015','semester'=>'Semester 4','status'=>'rejected','avatar'=>'avatar-5.jpg'],
['en'=>'EN2025016','name'=>'Divya Joshi','email'=>'divya@gmail.com','phone'=>'9800000016','semester'=>'Semester 5','status'=>'active','avatar'=>'avatar-6.jpg'],
['en'=>'EN2025017','name'=>'Harsh Patel','email'=>'harsh@gmail.com','phone'=>'9800000017','semester'=>'Semester 6','status'=>'inactive','avatar'=>'avatar-7.jpg'],
['en'=>'EN2025018','name'=>'Komal Shah','email'=>'komal@gmail.com','phone'=>'9800000018','semester'=>'Semester 1','status'=>'blocked','avatar'=>'avatar-8.jpg'],
['en'=>'EN2025019','name'=>'Yash Mehta','email'=>'yash@gmail.com','phone'=>'9800000019','semester'=>'Semester 2','status'=>'verified','avatar'=>'avatar-9.jpg'],
['en'=>'EN2025020','name'=>'Mihir Desai','email'=>'mihir@gmail.com','phone'=>'9800000020','semester'=>'Semester 3','status'=>'pending','avatar'=>'avatar-10.jpg'],
['en'=>'EN2025021','name'=>'Ishita Patel','email'=>'ishita@gmail.com','phone'=>'9800000021','semester'=>'Semester 4','status'=>'rejected','avatar'=>'avatar-1.jpg'],
['en'=>'EN2025022','name'=>'Dev Shah','email'=>'dev@gmail.com','phone'=>'9800000022','semester'=>'Semester 5','status'=>'active','avatar'=>'avatar-2.jpg'],
['en'=>'EN2025023','name'=>'Aarya Mehta','email'=>'aarya@gmail.com','phone'=>'9800000023','semester'=>'Semester 6','status'=>'inactive','avatar'=>'avatar-3.jpg'],
['en'=>'EN2025024','name'=>'Manav Patel','email'=>'manav@gmail.com','phone'=>'9800000024','semester'=>'Semester 1','status'=>'blocked','avatar'=>'avatar-4.jpg'],
['en'=>'EN2025025','name'=>'Khushi Shah','email'=>'khushi@gmail.com','phone'=>'9800000025','semester'=>'Semester 2','status'=>'verified','avatar'=>'avatar-5.jpg'],
['en'=>'EN2025026','name'=>'Parth Desai','email'=>'parth@gmail.com','phone'=>'9800000026','semester'=>'Semester 3','status'=>'pending','avatar'=>'avatar-6.jpg'],
['en'=>'EN2025027','name'=>'Riddhi Mehta','email'=>'riddhi@gmail.com','phone'=>'9800000027','semester'=>'Semester 4','status'=>'rejected','avatar'=>'avatar-7.jpg'],
['en'=>'EN2025028','name'=>'Meet Patel','email'=>'meet@gmail.com','phone'=>'9800000028','semester'=>'Semester 5','status'=>'active','avatar'=>'avatar-8.jpg'],
['en'=>'EN2025029','name'=>'Tanya Shah','email'=>'tanya@gmail.com','phone'=>'9800000029','semester'=>'Semester 6','status'=>'inactive','avatar'=>'avatar-9.jpg'],
['en'=>'EN2025030','name'=>'Dhruv Joshi','email'=>'dhruv@gmail.com','phone'=>'9800000030','semester'=>'Semester 1','status'=>'blocked','avatar'=>'avatar-10.jpg'],
];

$search=strtolower($_POST['search']??'');
$status=strtolower($_POST['status']??'');
$i=1;
$badge = [
    'pending'   => 'bg-warning',
    'verified'  => 'bg-success',
    'rejected'  => 'bg-danger',
    'active'    => 'bg-primary',
    'inactive'  => 'bg-secondary',
    'blocked'   => 'bg-dark'
];
foreach($students as $s){
if($search && strpos(strtolower($s['en']." ".$s['name']." ".$s['email']),$search)===false) continue;
if($status && $s['status']!=$status) continue;
echo "<tr>";
echo "<td>".$i++."</td>";
echo "<td><img src='assets/images/user/".$s['avatar']."' width='45' class='rounded-circle'></td>";
echo "<td>".$s['en']."</td>";
echo "<td>".$s['name']."</td>";
echo "<td>".$s['email']."</td>";
echo "<td>".$s['phone']."</td>";
echo "<td>".$s['semester']."</td>";
echo "<td><span class='badge ".$badge[$s['status']]."'>".ucfirst($s['status'])."</span></td>";
echo "<td>
<a href='#' class='btn btn-sm btn-info'><i class='ti ti-eye'></i></a>
<a href='#' class='btn btn-sm btn-warning'><i class='ti ti-edit'></i></a>
<a href='#' class='btn btn-sm btn-danger'><i class='ti ti-lock'></i></a>
</td>";
echo "</tr>";
}
if($i==1){
echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
}
?>