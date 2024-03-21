<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title><?= $title ?? 'Dashboard' ?> - Admin</title>

    <!-- Custom fonts for this template-->
    <link href="<?= BASE_URL ?>assets/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= BASE_URL ?>assets/admin/css/sb-admin-2.min.css" rel="stylesheet">

    <?php 
        if (isset($style) && $style) {
            require_once PATH_VIEW_ADMIN . 'styles/' . $style . '.php';
        }

        if (isset($style2) && $style2) {
            require_once PATH_VIEW_ADMIN . $style2 . '.php';
        }
    ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once PATH_VIEW_ADMIN . "layouts/partials/sidebar.php"; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once PATH_VIEW_ADMIN . "layouts/partials/topbar.php"; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <?php require_once PATH_VIEW_ADMIN . $view . '.php'; ?>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php require_once PATH_VIEW_ADMIN . "layouts/partials/footer.php"; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php require_once PATH_VIEW_ADMIN . "components/logout-modal.php"; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= BASE_URL ?>assets/admin/vendor/jquery/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= BASE_URL ?>assets/admin/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= BASE_URL ?>assets/admin/js/sb-admin-2.min.js"></script>

    <?php 
        if (isset($script) && $script) {
            require_once PATH_VIEW_ADMIN . 'scripts/' . $script . '.php';
        }

        if (isset($script2) && $script2) {
            require_once PATH_VIEW_ADMIN . $script2 . '.php';
        }
    ?>

</body>

</html>