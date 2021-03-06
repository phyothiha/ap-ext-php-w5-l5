<?php
    $uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $resource = $uri[1];
    $current_page = 1;
    $per_page_items = 10;

    if (isset($_GET['page'])) {
        $current_page = $_GET['page'];
    } else {
        unset($_COOKIE['search']);
        setcookie('search', null, 0, "/admin");
    }

    $offset = ($current_page - 1) * $per_page_items;

    if (
        isset($_GET['search']) || 
        isset($_COOKIE['search'])
    ) {
        $search = $_GET['search'] ?? $_COOKIE['search'];

        setcookie('search', $search, 0, "/admin");

        $stmt = $pdo->prepare("
            SELECT 
                COUNT(*) as 'total' 
            FROM 
                $resource 
            WHERE 
                $search_col LIKE ? 
            ORDER BY 
                `id` DESC
        ");
        $stmt->execute(["%$search%"]);
        $aggregate = $stmt->fetch();
        $total = ceil($aggregate->total / $per_page_items);

        if (isset($searchPreparedQuery)) {
          $stmt = $pdo->prepare($searchPreparedQuery);
        } else {
          $stmt = $pdo->prepare("
              SELECT
                  *
              FROM 
                  $resource 
              WHERE 
                  $search_col LIKE ?  
              ORDER BY 
                  `id` DESC 
              LIMIT ?, ?
          ");
        }

        $stmt->bindValue(1, "%$search%");
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->bindValue(3, $per_page_items, PDO::PARAM_INT);
        $stmt->execute();
        $$resource = $stmt->fetchAll();
    }
 ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>PHP Shopping</title>
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="/public/admin-lte-assets/plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="/public/admin-lte-assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="/public/admin-lte-assets/css/adminlte.min.css">
        <!-- Google Font: Source Sans Pro -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
    </head>
    
    <body class="hold-transition sidebar-mini">

        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>

                <!-- SEARCH FORM -->
                <?php 
                    if ( 
                        $resource != 'orders' &&
                        strpos($_SERVER['REQUEST_URI'], 'index.php')
                        // and maybe more
                    ) :
                 ?>
                
                <form class="form-inline ml-3" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="input-group input-group-sm">
                        <input name="search" class="form-control form-control-navbar" type="text" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-primary rounded-0 px-4">Clear</a>
                        </div>
                    </div>
                </form>

                <?php endif; ?>
                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="btn btn-danger" href="/admin/logout.php" role="button">
                            Logout <i class="fas fa-sign-out-alt ml-1"></i>
                        </a>
                  </li>
                </ul>
            </nav>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="/index.php" class="brand-link">
                    <img src="/public/admin-lte-assets/images/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                    <span class="brand-text font-weight-light">Blog Panel</span>
                </a>
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="/public/admin-lte-assets/images/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block"><?php echo $_SESSION['username']; ?></a>
                        </div>
                    </div>
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class
                            with font-awesome or any other icon font library -->
                            
                            <li class="nav-item">
                                <a href="/admin/products/index.php" class="nav-link">
                                    <i class="nav-icon fas fa-th"></i>
                                    <p>
                                        Products
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/categories/index.php" class="nav-link">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>
                                        Categories
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/users/index.php" class="nav-link">
                                    <i class="nav-icon fa fa-users"></i>
                                    <p>
                                        Users
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/orders/index.php" class="nav-link">
                                    <i class="nav-icon fa fa-table"></i>
                                    <p>
                                        Sale Orders
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper pt-5">
                