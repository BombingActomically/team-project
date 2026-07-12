<?php include 'auth_check.php'; ?>

<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>

<title>Admin | Team Details</title>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="icon" href="assets/images/favicon.svg" type="image/x-icon" />

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="assets/fonts/phosphor/duotone/style.css" />
<link rel="stylesheet" href="assets/fonts/tabler-icons.min.css" />
<link rel="stylesheet" href="assets/fonts/feather.css" />
<link rel="stylesheet" href="assets/fonts/fontawesome.css" />

<link rel="stylesheet" href="assets/css/style.css" id="main-style-link" />

</head>


<body>



<div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">

<div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">

<div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0"></div>

</div>

</div>



<?php include_once("Sidebar.php"); ?>

<?php include_once("Header.php"); ?>




<div class="pc-container">

<div class="pc-content">





<!-- Page Header -->


<div class="page-header">

<div class="page-block">


<div class="page-header-title">

<h5 class="mb-0 font-medium">
Team Details
</h5>

</div>



<ul class="breadcrumb">


<li class="breadcrumb-item">

<a href="Index.php">
Home
</a>

</li>


<li class="breadcrumb-item">
Team Management
</li>


<li class="breadcrumb-item">
Team Details
</li>


</ul>


</div>

</div>









<!-- Team Information -->


<div class="row">


<div class="col-md-6">


<div class="card">


<div class="card-header">

<h5 class="mb-0">
Team Information
</h5>

</div>



<div class="card-body">


<div class="mb-3">

<label class="fw-bold">
Team Name
</label>

<p>
Code Warriors
</p>

</div>



<div class="mb-3">

<label class="fw-bold">
Team Code
</label>

<p>

<span class="badge bg-primary">
CW101
</span>

</p>

</div>




<div class="mb-3">

<label class="fw-bold">
Created Date
</label>

<p>
15-07-2026
</p>

</div>



</div>


</div>


</div>








<!-- Event Information -->


<div class="col-md-6">


<div class="card">


<div class="card-header">

<h5 class="mb-0">
Event Information
</h5>

</div>



<div class="card-body">


<div class="mb-3">

<label class="fw-bold">
Event Name
</label>

<p>
Hackathon
</p>

</div>




<div class="mb-3">

<label class="fw-bold">
Venue
</label>

<p>
Main Auditorium
</p>

</div>




<div class="mb-3">

<label class="fw-bold">
Event Date
</label>

<p>
20-07-2026
</p>

</div>



</div>


</div>


</div>



</div>









<!-- Leader Details -->


<div class="card">


<div class="card-header">

<h5 class="mb-0">
Team Leader
</h5>

</div>


<div class="card-body">


<div class="row align-items-center">


<div class="col-md-2">


<img src="assets/images/user/avatar-1.jpg"
width="80"
class="rounded-circle">


</div>



<div class="col-md-10">


<h5>
Rahul Patel
</h5>


<p class="mb-1">
Enrollment : EN2025001
</p>


<p class="mb-1">
Email : rahul@gmail.com
</p>


<p class="mb-0">
College : ABC College
</p>


</div>



</div>


</div>


</div>









<!-- Members -->


<div class="card">


<div class="card-header">

<h5 class="mb-0">
Team Members
</h5>

</div>



<div class="card-body">


<div class="table-responsive">


<table class="table table-hover align-middle">



<thead class="table-light">


<tr>


<th>#</th>

<th>Photo</th>

<th>Name</th>

<th>Enrollment</th>

<th>Email</th>

<th>College</th>

<th>Role</th>


</tr>


</thead>





<tbody>




<tr>


<td>
1
</td>


<td>

<img src="assets/images/user/avatar-1.jpg"
width="45"
class="rounded-circle">

</td>


<td>
Rahul Patel
</td>


<td>
EN2025001
</td>


<td>
rahul@gmail.com
</td>


<td>
ABC College
</td>


<td>

<span class="badge bg-success">
Leader
</span>

</td>


</tr>







<tr>


<td>
2
</td>


<td>

<img src="assets/images/user/avatar-2.jpg"
width="45"
class="rounded-circle">

</td>


<td>
Amit Shah
</td>


<td>
EN2025002
</td>


<td>
amit@gmail.com
</td>


<td>
ABC College
</td>


<td>

<span class="badge bg-primary">
Member
</span>

</td>


</tr>






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

<script src="assets/js/icon/custom-icon.js"></script>

<script src="assets/js/plugins/feather.min.js"></script>

<script src="assets/js/component.js"></script>

<script src="assets/js/theme.js"></script>

<script src="assets/js/script.js"></script>





<script>

layout_change('false');

layout_theme_sidebar_change('dark');

change_box_container('false');

layout_caption_change('true');

layout_rtl_change('false');

preset_change('preset-1');

main_layout_change('vertical');

</script>



</body>

</html>