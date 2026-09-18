<?php include 'auth_check.php'; ?>

<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>

<title>Admin | Cancelled Registrations</title>

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
Cancelled Registrations
</h5>

</div>



<ul class="breadcrumb">


<li class="breadcrumb-item">

<a href="Index.php">
Home
</a>

</li>


<li class="breadcrumb-item">
Registration Management
</li>


<li class="breadcrumb-item">
Cancelled Registrations
</li>


</ul>


</div>

</div>








<div class="card">



<div class="card-header">


<div class="row align-items-center">


<div class="col-md-6">


<h5 class="mb-0">
Cancelled Registration List
</h5>


</div>




<div class="col-md-6 text-end">


<input 
type="text"
class="form-control d-inline-block w-50"
placeholder="Search registration">


</div>


</div>


</div>






<div class="card-body">


<div class="table-responsive">


<table class="table table-hover align-middle">



<thead class="table-light">


<tr>


<th>#</th>

<th>Registration ID</th>

<th>Event</th>

<th>Type</th>

<th>Participant</th>

<th>College</th>

<th>Status</th>

<th>Cancelled Date</th>

<th>Action</th>


</tr>


</thead>





<tbody>




<tr>


<td>
1
</td>


<td>
REG0051
</td>



<td>
Photography Contest
</td>



<td>

<span class="badge bg-primary">
Solo
</span>

</td>



<td>
Rahul Patel
</td>



<td>
ABC College
</td>



<td>

<span class="badge bg-secondary">
Cancelled
</span>

</td>



<td>
22-07-2026
</td>



<td>


<a href="#" class="btn btn-sm btn-info">

<i class="ti ti-eye"></i>

</a>


</td>



</tr>








<tr>


<td>
2
</td>


<td>
REG0052
</td>



<td>
Hackathon
</td>



<td>

<span class="badge bg-success">
Team
</span>

</td>



<td>
Code Warriors
</td>



<td>
XYZ College
</td>



<td>

<span class="badge bg-secondary">
Cancelled
</span>

</td>



<td>
23-07-2026
</td>



<td>


<a href="#" class="btn btn-sm btn-info">

<i class="ti ti-eye"></i>

</a>


</td>



</tr>









<tr>


<td>
3
</td>


<td>
REG0053
</td>



<td>
Dance Competition
</td>



<td>

<span class="badge bg-primary">
Solo
</span>

</td>



<td>
Priya Shah
</td>



<td>
PQR College
</td>



<td>

<span class="badge bg-secondary">
Cancelled
</span>

</td>



<td>
24-07-2026
</td>



<td>


<a href="#" class="btn btn-sm btn-info">

<i class="ti ti-eye"></i>

</a>


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