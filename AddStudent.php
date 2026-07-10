<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>

<title>Admin | Add Student</title>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="icon" href="assets/images/favicon.svg" type="image/x-icon" />

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">
<link rel="stylesheet" href="assets/fonts/feather.css">
<link rel="stylesheet" href="assets/css/style.css">

</head>


<body>


<div class="loader-bg fixed inset-0 bg-white z-[1034]">
<div class="loader-track h-[5px] w-full">
<div class="loader-fill w-[300px] h-[5px] bg-primary-500"></div>
</div>
</div>



<?php include_once("Sidebar.php"); ?>

<?php include_once("Header.php"); ?>



<div class="pc-container">

<div class="pc-content">


<div class="page-header">

<div class="page-block">

<div class="page-header-title">
<h5 class="mb-0 font-medium">
Add Student
</h5>
</div>


<ul class="breadcrumb">

<li class="breadcrumb-item">
<a href="Index.php">Home</a>
</li>

<li class="breadcrumb-item">
Student Management
</li>

<li class="breadcrumb-item">
Add Student
</li>

</ul>

</div>

</div>



<div class="card shadow-sm border-0">


<div class="card-header">
<h5 class="mb-0">
Add Student
</h5>
</div>


<div class="card-body">


<form id="studentForm" class="needs-validation" novalidate enctype="multipart/form-data">


<div class="row">


<div class="col-md-6 mb-3">

<label class="form-label">College</label>

<select class="form-select" id="college_id" required>

<option value="">Choose College</option>
<option value="1">ABC College</option>
<option value="2">XYZ College</option>

</select>

<div class="invalid-feedback">
Please select college
</div>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">Enrollment No</label>

<input type="text"
class="form-control"
id="enrollment_no"
placeholder="Enter enrollment number"
required>

<div class="invalid-feedback">
Enrollment number is required
</div>

</div>




<div class="col-md-6 mb-3">

<label class="form-label">Student Name</label>

<input type="text"
class="form-control"
id="name"
placeholder="Enter student name"
required>

<div class="invalid-feedback">
Student name is required
</div>

</div>




<div class="col-md-6 mb-3">

<label class="form-label">Email</label>

<input type="email"
class="form-control"
id="email"
placeholder="Enter email"
required>

<div class="invalid-feedback">
Valid email is required
</div>

</div>




<div class="col-md-6 mb-3">

<label class="form-label">Password</label>

<input type="password"
class="form-control"
id="password"
placeholder="Enter password"
required>

<div class="invalid-feedback">
Password is required
</div>

</div>




<div class="col-md-6 mb-3">

<label class="form-label">Phone</label>

<input type="text"
class="form-control"
id="phone"
placeholder="Enter phone number">

<div class="invalid-feedback">
Enter valid phone number
</div>

</div>




<div class="col-md-6 mb-3">

<label class="form-label">Gender</label>

<select class="form-select" id="gender" required>

<option value="">
Choose Gender
</option>

<option value="male">
Male
</option>

<option value="female">
Female
</option>

<option value="other">
Other
</option>

</select>

<div class="invalid-feedback">
Please select gender
</div>

</div>




<div class="col-md-6 mb-3">

<label class="form-label">Semester</label>

<select class="form-select" id="semester" required>

<option value="">
Choose Semester
</option>

<option>Semester 1</option>
<option>Semester 2</option>
<option>Semester 3</option>
<option>Semester 4</option>
<option>Semester 5</option>
<option>Semester 6</option>
<option>Semester 7</option>
<option>Semester 8</option>

</select>

<div class="invalid-feedback">
Please select semester
</div>

</div>




<div class="col-md-6 mb-3">

<label class="form-label">
ID Card Image
</label>

<input type="file"
class="form-control"
id="id_card_image"
accept="image/png,image/jpeg"
required>

<div class="invalid-feedback">
ID Card image is required (JPG/PNG)
</div>

</div>




<div class="col-md-6 mb-3">

<label class="form-label">
Profile Photo
</label>

<input type="file"
class="form-control"
id="profile_photo"
accept="image/png,image/jpeg">

<div class="invalid-feedback">
Only JPG or PNG allowed
</div>

</div>




<div class="col-md-6 mb-3">

<label class="form-label">
Status
</label>


<select class="form-select" id="status" required>

<option value="">
Choose Status
</option>

<option value="pending">
Pending
</option>

<option value="verified">
Verified
</option>

<option value="active">
Active
</option>

<option value="inactive">
Inactive
</option>

<option value="blocked">
Blocked
</option>

</select>


<div class="invalid-feedback">
Please select status
</div>


</div>


</div>



<div class="text-end mt-3">

<button type="reset" class="btn btn-light me-2">
Reset
</button>


<button type="submit" class="btn btn-primary">
<i class="ti ti-device-floppy"></i>
Save Student
</button>


</div>


</form>


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



document.getElementById("studentForm").addEventListener("submit",function(e){

e.preventDefault();


let isValid=true;

let form=this;


form.classList.add("was-validated");



document.querySelectorAll(".form-control,.form-select")
.forEach(function(el){

el.classList.remove("is-invalid");

});



let fields=[
"college_id",
"enrollment_no",
"name",
"email",
"password",
"gender",
"semester",
"status"
];


fields.forEach(function(id){

let field=document.getElementById(id);

if(field.value.trim()=="")
{

field.classList.add("is-invalid");

isValid=false;

}

});



let email=document.getElementById("email");

let emailPattern=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;


if(!emailPattern.test(email.value))
{

email.classList.add("is-invalid");

isValid=false;

}



let phone=document.getElementById("phone");


if(phone.value!="" && !/^[0-9]{7,15}$/.test(phone.value))
{

phone.classList.add("is-invalid");

isValid=false;

}




let idCard=document.getElementById("id_card_image");


if(idCard.files.length==0)
{

idCard.classList.add("is-invalid");

isValid=false;

}

else if(!["image/jpeg","image/png"].includes(idCard.files[0].type))
{

idCard.classList.add("is-invalid");

isValid=false;

}



let profile=document.getElementById("profile_photo");


if(profile.files.length>0)
{

if(!["image/jpeg","image/png"].includes(profile.files[0].type))
{

profile.classList.add("is-invalid");

isValid=false;

}

}




if(isValid)
{

alert("Student added successfully ✅");

form.reset();

form.classList.remove("was-validated");

}


});


</script>


</body>

</html>