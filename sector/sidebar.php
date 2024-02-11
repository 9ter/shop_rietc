<?php


$title = 'RIETC';
$imageprofile = 'https://i.pinimg.com/originals/90/7a/d2/907ad223930adecd5dfcc11d5a56bbb3.jpg';

$partstaff = 'manage_staff.php';
$partstudents = 'manage_students.php';

//////rank//////
if ($perm == 0) {
    $rank = "Admin";
} else if ($perm == 3) {
    $rank = "Top-up officer";
} else {
    $rank = "";
}
$menusidbar = '
<a href="index.php" class="nav-item nav-link active"><iclass="fa fa-tachometer-alt me-2"></i>Dashboard</a>

';

?>