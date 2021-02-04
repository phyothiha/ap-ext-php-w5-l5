<?php  
    require '../../config/bootstrap.php';
    require '../../core/Validate.php';
    require 'logic/store.php';

    $stmt = $pdo->prepare("
        SELECT 
            * 
        FROM 
            `categories` 
        WHERE 
            `id` = ?
    ");

    $stmt->execute([$_GET['id']]);

    $category = $stmt->fetch();
?>

<?php require '../template/header-dashboard.php' ?>
    
    <?php if (! empty($category)): ?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- place_content -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="bg-transparent px-3 py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h3 class="card-title">Edit Blog Category</h3>
                            <div>
                                <a href="add.php" class="btn btn-sm btn-success">Add New</a>
                            </div>
                        </div>
                        <form role="form" action="" method="POST">
                            <?php method('PUT'); ?>
                            <?php csrf(); ?>
                            <input type="hidden" name="id" value="<?php echo $category->id; ?>">

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control <?php echo error('name') ? 'is-invalid' : ''; ?>" id="name" value="<?php echo e( old('name', $category->name) ); ?>">

                                    <?php if ( error('name') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('name') ); ?></div>
                                    <?php endif ?>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control <?php echo error('description') ? 'is-invalid' : ''; ?>" id="description" name="description" rows="6" ><?php echo e( old('description', $category->description) ); ?></textarea>

                                    <?php if ( error('description') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('description') ); ?></div>
                                    <?php endif ?>
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
    <?php endif; ?>
    
<?php require '../template/footer-dashboard.php' ?>