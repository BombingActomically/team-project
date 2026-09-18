<?php
include 'auth_check.php';


// Database Connection

$host = "localhost";
$user = "root";
$password = "";
$database = "evenza";


$conn = mysqli_connect($host,$user,$password,$database);


if(!$conn)
{
    die("Database Connection Failed : ".mysqli_connect_error());
}


// Statistics

$total_payment = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT COUNT(*) as total FROM payments")
)['total'];


$total_revenue = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT SUM(amount) as total FROM payments WHERE payment_status='paid'")
)['total'] ?? 0;



$paid_payment = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT COUNT(*) as total FROM payments WHERE payment_status='paid'")
)['total'];



$pending_payment = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT COUNT(*) as total FROM payments WHERE payment_status='pending'")
)['total'];



$failed_payment = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT COUNT(*) as total FROM payments WHERE payment_status='failed'")
)['total'];

?>

<!doctype html>
<html lang="en" data-pc-theme="light">


<head>

<title>Payment Overview | Evenza Admin</title>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width,initial-scale=1">


<link rel="icon" href="assets/images/favicon.svg">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


<link rel="stylesheet" href="assets/css/style.css">

<link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">


</head>



<body>


<!-- Sidebar -->

<?php include_once("Sidebar.php"); ?>



<!-- Header -->

<?php include_once("Header.php"); ?>



<div class="pc-container">

<div class="pc-content">



<!-- Page Header -->

<div class="page-header">

<div class="page-block">

<h5 class="mb-0">
Payment Overview
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
Payment Overview
</li>


</ul>


</div>

</div>





<!-- Cards -->


<div class="row">



<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Total Payments</h6>

<h3>
<?php echo $total_payment; ?>
</h3>


</div>

</div>

</div>





<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Total Revenue</h6>

<h3 class="text-success">

₹ <?php echo number_format($total_revenue,2); ?>

</h3>


</div>

</div>

</div>





<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Successful</h6>

<h3 class="text-success">

<?php echo $paid_payment; ?>

</h3>


</div>

</div>

</div>





<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Pending</h6>

<h3 class="text-warning">

<?php echo $pending_payment; ?>

</h3>


</div>

</div>

</div>



</div>





<!-- Payment Table -->


<div class="card mt-4 shadow-sm">


<div class="card-header">

<h5 class="mb-0">
All Payments
</h5>

</div>



<div class="card-body">


<div class="table-responsive">


<table class="table table-hover">


<thead class="table-light">

<tr>

<th>#</th>

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


$result = mysqli_query(
$conn,
"SELECT * FROM payments ORDER BY payment_id DESC"
);


$count=1;


while($row=mysqli_fetch_assoc($result))
{


?>


<tr>


<td>
<?php echo $count++; ?>
</td>



<td>
<?php echo $row['registration_id']; ?>
</td>



<td>

₹ <?php echo $row['amount']; ?>

</td>



<td>

<?php echo strtoupper($row['payment_method']); ?>

</td>



<td>

<?php echo $row['transaction_id'] ?? 'N/A'; ?>

</td>



<td>


<?php

if($row['payment_status']=="paid")
{

echo '<span class="badge bg-success">
Paid
</span>';

}

elseif($row['payment_status']=="pending")
{

echo '<span class="badge bg-warning">
Pending
</span>';

}

else
{

echo '<span class="badge bg-danger">
Failed
</span>';

}

?>


</td>



<td>

<?php

if($row['payment_date'])
{
echo date("d M Y",strtotime($row['payment_date']));
}
else
{
echo "N/A";
}

?>

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




<?php include_once("Footer.php"); ?>



<script src="assets/js/plugins/simplebar.min.js"></script>

<script src="assets/js/plugins/popper.min.js"></script>

<script src="assets/js/plugins/feather.min.js"></script>

<script src="assets/js/theme.js"></script>

<script src="assets/js/script.js"></script>



<script>
layout_change('false');
</script>


<script>
layout_theme_sidebar_change('dark');
</script>


<script>
change_box_container('false');
</script>


<script>
layout_caption_change('true');
</script>


<script>
layout_rtl_change('false');
</script>


<script>
preset_change('preset-1');
</script>


<script>
main_layout_change('vertical');
</script>



</body>

</html>