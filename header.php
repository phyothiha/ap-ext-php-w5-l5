<?php
    $current_page = 1;
    $per_page_items = 6;

    if (isset($_GET['page'])) {
        $current_page = $_GET['page'];
    } else {
        unset($_COOKIE['search']);
        setcookie('search', null, 0, "/");
    }

    $offset = ($current_page - 1) * $per_page_items;

    if (
        isset($_GET['search']) || 
        isset($_COOKIE['search'])
    ) {
        $search = $_GET['search'] ?? $_COOKIE['search'];

        setcookie('search', $search, 0, "/");

        $stmt = $pdo->prepare("
            SELECT 
                COUNT(*) as 'total' 
            FROM 
                `products` 
            WHERE 
                `name` LIKE ? 
            ORDER BY 
                `id` DESC
        ");
        $stmt->execute(["%$search%"]);
        $aggregate = $stmt->fetch();
        $total = ceil($aggregate->total / $per_page_items);

        $stmt = $pdo->prepare("
          SELECT
              *
          FROM 
              `products` 
          WHERE 
              `name` LIKE ?  
          ORDER BY 
              `id` DESC 
          LIMIT ?, ?
        ");

        $stmt->bindValue(1, "%$search%");
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->bindValue(3, $per_page_items, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll();
    }

    /** Category */
    $stmt = $pdo->query("
        SELECT 
            *
        FROM 
            `categories`
        ORDER BY
            `id` DESC
    ");
    $stmt->execute();
    $categories = $stmt->fetchAll();
 ?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="public/custom-theme-assets/img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>AP Shopping</title>

	<!--
            CSS
            ============================================= -->
	<link rel="stylesheet" href="public/custom-theme-assets/css/linearicons.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/owl.carousel.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/themify-icons.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/nice-select.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/nouislider.min.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/bootstrap.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/main.css">
</head>

<body id="category">

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="/"><h4>AP Shopping<h4></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse offset" id="navbarSupportedContent">
						<ul class="nav navbar-nav navbar-right">
							<li class="nav-item"><a href="#" class="cart"><span class="ti-bag"></span></a></li>
							<li class="nav-item">
								<button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</div>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between" accept="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="text" class="form-control" id="search_input" placeholder="Search Here" name="search">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Welcome</h1>

				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->
	<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Browse Categories</div>
					<ul class="main-categories">
						<li class="main-nav-list">

                            <?php foreach ($categories as $category) : ?>

                            <a href="#">
                                <span class="lnr lnr-arrow-right"></span><?php echo e($category->name); ?>
                            </a>
                            
                            <?php endforeach; ?>

						</li>
					</ul>
				</div>
			</div>
			<div class="col-xl-9 col-lg-8 col-md-7">
				
