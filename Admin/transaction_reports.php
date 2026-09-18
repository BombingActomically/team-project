<?php
include 'auth_check.php';


// Database Connection

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "evenza"
);


if(!$conn)
{
    die("Database Connection Failed : ".mysqli_connect_error());
}



// Total Transactions

$total_transactions = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total FROM payments")
)['total'];



// Total Revenue

$total_revenue = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT SUM(amount) as total FROM payments WHERE payment_status='paid'")
)['total'] ?? 0;



// Pending Amount

$pending_amount = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT SUM(amount) as total FROM payments WHERE payment_status='pending'")
)['total'] ?? 0;



// Failed Amount

$failed_amount = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT SUM(amount) as total FROM payments WHERE payment_status='failed'")
)['total'] ?? 0;



// All Transactions

$query = "
SELECT *
FROM payments
ORDER BY payment_id DESC
";


$result = mysqli_query($conn,$query);


?>



<!doctype html>

<html lang="en"
data-pc-preset="preset-1"
data-pc-sidebar-caption="true"
data-pc-direction="ltr"
dir="ltr"
data-pc-theme="light">



<head>


<title>Transaction Reports | Admin</title>


<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">


<link rel="icon" href="assets/images/favicon.svg">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


<link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">

<link rel="stylesheet" href="assets/fonts/feather.css">

<link rel="stylesheet" href="assets/css/style.css">


</head>



<body>



<?php include "Sidebar.php"; ?>


<?php include "Header.php"; ?>





<div class="pc-container">

<div class="pc-content">



<div class="page-header">

<div class="page-block">


<h5 class="mb-0">
Transaction Reports
</h5>



<ul class="breadcrumb">

<li class="breadcrumb-item">
<a href="Dashboard.php">
Home
</a>
</li>


<li class="breadcrumb-item">
Payment Management
</li>


<li class="breadcrumb-item active">
Transaction Reports
</li>


</ul>



</div>

</div>





<!-- Cards -->


<div class="row">



<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Total Transactions</h6>

<h3>
<?= $total_transactions; ?>
</h3>

</div>

</div>

</div>




<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Total Revenue</h6>

<h3>
₹ <?= $total_revenue; ?>
</h3>

</div>

</div>

</div>




<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Pending Amount</h6>

<h3>
₹ <?= $pending_amount; ?>
</h3>

</div>

</div>

</div>





<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Failed Amount</h6>

<h3>
₹ <?= $failed_amount; ?>
</h3>

</div>

</div>

</div>



</div>






<!-- Table -->


<div class="row">


<div class="col-sm-12">


<div class="card">


<div class="card-header">

<h5>
All Transaction Reports
</h5>

</div>



<div class="card-body">


<div class="table-responsive">



<table class="table table-hover">


<thead>


<tr>

<th>#</th>

<th>Payment ID</th>

<th>Registration ID</th>

<th>Amount</th>

<th>Method</th>

<th>Transaction ID</th>

<th>Status</th>

<th>Date</th>


</tr>


</thead>



<tbody>


<?php


$count=1;


if(mysqli_num_rows($result)>0)
{


while($row=mysqli_fetch_assoc($result))

{


?>


<tr>


<td>
<?= $count++; ?>
</td>


<td>
<?= $row['payment_id']; ?>
</td>


<td>
<?= $row['registration_id']; ?>
</td>


<td>
₹ <?= $row['amount']; ?>
</td>


<td>
<?= ucfirst($row['payment_method']); ?>
</td>


<td>
<?= $row['transaction_id']; ?>
</td>



<td>


<?php

if($row['payment_status']=="paid")
{

?>

<span class="badge bg-success">
Paid
</span>


<?php

}

elseif($row['payment_status']=="pending")

{

?>

<span class="badge bg-warning text-dark">
Pending
</span>


<?php

}

else

{

?>

<span class="badge bg-danger">
Failed
</span>


<?php

}

?>


</td>



<td>

<?= $row['payment_date'] ?? 'Not Available'; ?>

</td>



</tr>



<?php


}

}

else

{


?>


<tr>

<td colspan="8" class="text-center">

No Transaction Found

</td>

</tr>


<?php


}


?>


</tbody>


</table>


</div>


</div>


</div>


</div>


</div>


</div>


</div>





<?php include "Footer.php"; ?>



<script src="assets/js/plugins/simplebar.min.js"></script>

<script src="assets/js/plugins/popper.min.js"></script>

<script src="assets/js/plugins/feather.min.js"></script>

<script src="assets/js/component.js"></script>

<script src="assets/js/theme.js"></script>

<script src="assets/js/script.js"></script>



</body>

</html>