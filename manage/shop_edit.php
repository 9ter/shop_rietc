<?php
session_start();

if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: ../signin.php");
    exit();
} else {
    // Regenerate session ID to prevent session fixation attacks
    session_regenerate_id();
}

$perm = $_SESSION["perm"];

if ($perm != 0) {
    header("Location: ../signin.php");
    exit();
}


include('../sector/sidebar.php');
include('../config.php');


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM shop WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_user = $stmt->get_result();
    $row_user = mysqli_fetch_assoc($result_user);

    $id_user = $row_user['id_user'];

    if ($result_user) {
        $stmt_product = $conn->prepare("SELECT * FROM product WHERE id_user = ?");
        $stmt_product->bind_param("i", $id_user);
        $stmt_product->execute();
        $result_product = $stmt_product->get_result();
    }
} else {
    header("Location: ../signin.php");
    exit();
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>RIETC</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>
    <style>
        #search-results {
            position: absolute;
            width: 200px;
            background-color: #fff;
            border: 1px solid #ccc;
            display: none;
        }

        #search-results ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #search-results li {
            padding: 8px;
            cursor: pointer;
        }

        #search-results1 {
            position: absolute;
            width: 200px;
            background-color: #fff;
            border: 1px solid #ccc;
            display: none;
        }

        #search-results1 ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #search-results1 li {
            padding: 8px;
            cursor: pointer;
        }
    </style>

    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">
                <a href="../index.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>
                        <?php echo $title; ?>
                    </h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="<?php echo $imageprofile; ?>" alt=""
                            style="width: 60px; height: 60px;">
                        <div
                            class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">
                            <?php echo $_SESSION['username'] ?>
                        </h6>
                        <span>
                            <?php echo $rank ?>
                        </span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="../manage_staff.php" class="nav-item nav-link active"><i
                            class="fa fa-address-book me-2"></i>จัดการบัญชีพนักงาน</a>';
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="navbar-nav align-items-center ms-auto">

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-lg-inline-flex">
                                <?php echo $_SESSION['username'] ?>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="logout/logout.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->

            <!-- Form Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-secondary rounded h-100 p-4">
                            <h6 class="mb-4">แก้ไขข้อมูลร้านค้า</h6>
                            <form action="update_shop_edit.php?id=<?php echo $id; ?>" method="post">
                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">ชื่อร้าน</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="fname"
                                            value="<?php echo $row_user['shop_name'] ?>" name="shop_name">
                                    </div>
                                </div>
                                <fieldset class=" row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">สถานะ</legend>
                                    <div class="col-sm-10">
                                        <?php
                                        if ($row_user['status'] == 1) {
                                            echo '<div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios1" value="1" checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                active
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios2" value="0">
                                            <label class="form-check-label" for="gridRadios2">
                                                non active
                                            </label>
                                        </div>
                                    </div>';
                                        } else {

                                            echo '<div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios1" value="1" >
                                            <label class="form-check-label" for="gridRadios1">
                                                active
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios2" value="0" checked>
                                            <label class="form-check-label" for="gridRadios2">
                                                non active
                                            </label>
                                        </div>
                                    </div>';
                                        }
                                        ?>
                                </fieldset>

                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-secondary rounded h-100 p-4">
                            <h6 class="mb-4">เพิ่มเมนู</h6>
                            <form action="add_product.php" method="post">
                                <div class="row mb-3">
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="search-input1" placeholder=""
                                            name="shop_name" autocomplete="off" required style="display: none;"
                                            value="<?php echo $row_user['shop_name'] ?>">
                                        <input type="text" class="form-control" id="search-input1" placeholder=""
                                            name="id_user" autocomplete="off" required style="display: none;"
                                            value="<?php echo $row_user['id_user'] ?>">
                                        <input type="text" class="form-control" id="search-input1" placeholder=""
                                            name="user" autocomplete="off" required style="display: none;"
                                            value="<?php echo $row_user['user'] ?>">
                                        <input type="text" class="form-control" id="search-input1" placeholder=""
                                            name="id" autocomplete="off" required style="display: none;"
                                            value="<?php echo $row_user['id'] ?>">

                                        <input type="text" class="form-control" id="search-input1"
                                            placeholder="ชื่อเมนู" name="product_name" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-10">
                                        <label class="form-label">ราคา</label>
                                        <input type="number" class="form-control" placeholder="จำนวนเงิน" name="price"
                                            autocomplete="off" step="0.01" placeholder="รหัสผ่านใหม่" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">เงินเข้าระบบ</h6>
                        <a href="showall.php?money_in=1">Show All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-white">
                                    <th scope="col">ชื่อรายการ</th>
                                    <th scope="col">ราคา</th>
                                    <th scope="col" class="text-primary">DELETE</th>
                                    <th scope="col">วันที่เพิ่มในระบบ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($result_product)) {
                                    $row['price'] = number_format((float) $row['price'], 2, '.', '');
                                    echo "<tr><td>" . $row['product_name'] . "</td> ";
                                    echo "<td>" . $row['price'] . "</td> ";
                                    echo "<td><a class='btn btn-sm btn-primary' href='del_product.php?id=".$row['id']."'>DELETE</td> ";
                                    echo "<td>" . $row['time'] . "</td> ";
                                    echo " </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <!-- Form End -->


            <!-- Footer Start -->

            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/chart/chart.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../lib/tempusdominus/js/moment.min.js"></script>
    <script src="../lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="../lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>