<?php 
    require '../config/bootstrap.php';
    require '../core/Validate.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $validatedData = Validate::field([
            'email' => ['required', 'email:com,net', 'exists:users,email'],
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

            header('Location: /admin/categories/index.php');
        }
    }
 ?>

<?php require 'template/header-login.php' ?>

    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Admin Panel</b></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="" method="post">
                    <?php csrf(); ?>

                    <div class="mb-3">
                        <div class="input-group">
                            <input type="email" name="email" class="form-control <?php echo error('email') ? 'is-invalid' : ''; ?>" placeholder="Email"  value="<?php echo e( old('email') ); ?>">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>

                        <?php if ( error('email') ): ?>
                            <div class="invalid-feedback d-block"><?php echo e( error('email') ); ?></div>
                        <?php endif ?>
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <input type="password" name="password" class="form-control <?php echo isset($_SESSION['errorMessageBag']['password']) ? 'is-invalid' : ''; ?>" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                        <?php if ( error('password') ): ?>
                            <div class="invalid-feedback d-block"><?php echo e( error('password') ); ?></div>
                        <?php endif ?>
                    </div>

                    <div class="row">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php require 'template/footer-login.php' ?>