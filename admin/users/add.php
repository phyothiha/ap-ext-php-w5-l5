<?php  
    require '../../config/bootstrap.php';
    require '../../core/Validate.php';
    require 'logic/store.php';
?>

<?php require '../template/header-dashboard.php' ?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- place_content -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="bg-transparent px-3 py-3 border-bottom">
                            <h3 class="card-title">Add New User</h3>
                        </div>
                        <form role="form" action="" method="POST">
                            <?php csrf(); ?>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control <?php echo error('name') ? 'is-invalid' : ''; ?>" id="name" value="<?php echo e( old('name') ); ?>">

                                    <?php if ( error('name') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('name') ); ?></div>
                                    <?php endif ?>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control <?php echo error('email') ? 'is-invalid' : ''; ?>" id="email" value="<?php echo e( old('email') ); ?>">

                                    <?php if ( error('email') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('email') ); ?></div>
                                    <?php endif ?>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control <?php echo error('password') ? 'is-invalid' : ''; ?>" id="password">

                                    <?php if ( error('password') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('password') ); ?></div>
                                    <?php endif ?>

                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="role" name="role" <?php echo e( old('role') ) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="role">Admin</label>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="index.php" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

<?php require '../template/footer-dashboard.php' ?>