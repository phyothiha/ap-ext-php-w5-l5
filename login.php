<?php 
    require 'config/bootstrap.php';
    require 'core/Validate.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $validatedData = Validate::field([
            'email' => ['required', 'exists:users,email'],
            'password' => ['bail', 'required', 'exists:users,password'],
        ]);

        if (! empty($validatedData)) {
            extract($validatedData);

            $stmt = $pdo->prepare("
                SELECT 
                    `id`, 
                    `name`, 
                    `role`
                FROM 
                    `users` 
                WHERE 
                    `email` = ?
            ");

            $stmt->execute([$email]);
            $user = $stmt->fetch();

            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->name;
            $_SESSION['role'] = $user->role;
            $_SESSION['logged_in'] = time();

            header('Location: /index.php');
        }
    }
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
	<title>AP Shopping | Login</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="public/custom-theme-assets/css/linearicons.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/owl.carousel.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/themify-icons.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/nice-select.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/nouislider.min.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/bootstrap.css">
	<link rel="stylesheet" href="public/custom-theme-assets/css/main.css">
</head>

<body>

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container py-4">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <a class="navbar-brand logo_h p-0" href="/"><h4 class="mb-0">AP Shopping<h4></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                     aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
			</nav>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Log In</h1>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="login_box_img">
						<img class="img-fluid" src="public/custom-theme-assets/img/login.jpg" alt="">
						<div class="hover">
							<h4>New to our website?</h4>
							<p>There are advances being made in science and technology everyday, and a good example of this is the</p>
							<a class="primary-btn" href="register.php">Create an Account</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner">
						<h3>Log in to enter</h3>
						<form class="row login_form" action="" method="post" id="contactForm" novalidate="novalidate">
                            <?php csrf(); ?>

							<div class="col-md-12 form-group">
								<input type="email" class="form-control <?php echo error('email') ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'" value="<?php echo e( old('email') ); ?>">

                                <?php if ( error('email') ): ?>
                                    <div class="invalid-feedback d-block"><?php echo e( error('email') ); ?></div>
                                <?php endif ?>
							</div>

							<div class="col-md-12 form-group">
								<input type="password" class="form-control <?php echo error('password') ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">

                                <?php if ( error('password') ): ?>
                                    <div class="invalid-feedback d-block"><?php echo e( error('password') ); ?></div>
                                <?php endif ?>
							</div>

							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="primary-btn">Log In</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

	<!-- start footer Area -->
	<footer class="footer-area section_gap">
        <div class="container">
            <div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
                <p class="footer-text m-0">
                Copyright &copy; <script>document.write(new Date().getFullYear());</script> All rights reserved
                </p>
            </div>
        </div>
    </footer>
	<!-- End footer Area -->


	<script src="public/custom-theme-assets/js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="public/custom-theme-assets/js/vendor/bootstrap.min.js"></script>
	<script src="public/custom-theme-assets/js/jquery.ajaxchimp.min.js"></script>
	<script src="public/custom-theme-assets/js/jquery.nice-select.min.js"></script>
	<script src="public/custom-theme-assets/js/jquery.sticky.js"></script>
	<script src="public/custom-theme-assets/js/nouislider.min.js"></script>
	<script src="public/custom-theme-assets/js/jquery.magnific-popup.min.js"></script>
	<script src="public/custom-theme-assets/js/owl.carousel.min.js"></script>
	<script src="public/custom-theme-assets/js/gmaps.min.js"></script>
	<script src="public/custom-theme-assets/js/main.js"></script>
</body>

</html>