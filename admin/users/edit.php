<?php  
    require '../../config/bootstrap.php';
    require '../../core/Validate.php';
    require 'logic/store.php';

    $stmt = $pdo->prepare("
        SELECT * FROM `users` WHERE `id` = ?
    ");
    $stmt->execute([$_GET['id']]);

    $user = $stmt->fetch();
?>

<?php require '../template/header-dashboard.php' ?>
    
    <?php if (! empty($user)) : ?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- place_content -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="bg-transparent px-3 py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h3 class="card-title">Edit User</h3>
                            <div>
                                <a href="add.php" class="btn btn-sm btn-success">Add New</a>
                            </div>
                        </div>
                        <form role="form" action="" method="POST">
                            <?php method('PUT'); ?>
                            <?php csrf(); ?>
                            <input type="hidden" name="id" value="<?php echo $user->id; ?>">

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control <?php echo error('name') ? 'is-invalid' : ''; ?>" id="name" value="<?php echo e( old('name', $user->name) ); ?>">

                                    <?php if ( error('name') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('name') ); ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control <?php echo error('email') ? 'is-invalid' : ''; ?>" id="email" value="<?php echo e( old('email', $user->email) ); ?>">

                                    <?php if ( error('email') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('email') ); ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password <small class="text-success ml-1">* This user already has a password</small></label>
                                    <input type="password" name="password" class="form-control <?php echo error('password') ? 'is-invalid' : ''; ?>" id="password">

                                    <?php if ( error('password') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('password') ); ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control <?php echo error('address') ? 'is-invalid' : ''; ?>" id="address" name="address" value="<?php echo e( old('address', $user->address) ); ?>">

                                    <?php if ( error('address') ): ?>
                                        <div class="invalid-feedback d-block"><?php echo e( error('address') ); ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control <?php echo error('phone') ? 'is-invalid' : ''; ?>" id="phone" name="phone"value="<?php echo e( old('phone', $user->phone) ); ?>">

                                    <?php if ( error('phone') ): ?>
                                        <div class="invalid-feedback d-block"><?php echo e( error('phone') ); ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="role" name="role" <?php echo ($user->role) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="role" value>Admin</label>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="index.php" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <?php else: ?>
        <?php not_found(); ?>
    <?php endif; ?>

<?php require '../template/footer-dashboard.php' ?>