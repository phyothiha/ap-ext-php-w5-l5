<?php  
    require '../../config/bootstrap.php';
    require '../../core/Validate.php';
    require 'logic/store.php';
?>

<?php require '../template/header-dashboard.php' ?>

    <div class="description">
        <div class="container-fluid">
            <div class="row">
                <!-- place_description -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="bg-transparent px-3 py-3 border-bottom">
                            <h3 class="card-title">Add New Category</h3>
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
                                    <label for="description">Description</label>
                                    <textarea class="form-control <?php echo error('description') ? 'is-invalid' : ''; ?>" id="description" name="description" rows="6" ><?php echo e( old('description') ); ?></textarea>

                                    <?php if ( error('description') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('description') ); ?></div>
                                    <?php endif ?>
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