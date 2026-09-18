<?php
$conn = mysqli_connect("localhost", "root", "", "employee");

if (!$conn) 
{
    die("Connection Failed: " . mysqli_connect_error());
}
?>