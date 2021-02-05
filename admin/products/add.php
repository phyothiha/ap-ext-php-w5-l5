<?php  
    require '../../config/bootstrap.php';
    require '../../core/Validate.php';
    require 'logic/store.php';

    $stmt = $pdo->query("
        SELECT 
          `categories`.`id`,
          `categories`.`name`
        FROM
          `categories`
    ");
    $stmt->execute();
    $categories = $stmt->fetchAll();
?>

<?php require '../template/header-dashboard.php' ?>

    <div class="description">
        <div class="container-fluid">
            <div class="row">
                <!-- place_description -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="bg-transparent px-3 py-3 border-bottom">
                            <h3 class="card-title">Add New Product</h3>
                        </div>
                        <form role="form" action="" method="POST" enctype="multipart/form-data">
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
                                    <textarea class="form-control <?php echo error('description') ? 'is-invalid' : ''; ?>" id="description" name="description" rows="4" ><?php echo e( old('description') ); ?></textarea>

                                    <?php if ( error('description') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('description') ); ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group">
                                    <label for="category_id">Category</label>

                                    <select 
                                        name="category_id" 
                                        id="category_id" 
                                        class="form-control <?php echo error('category_id') ? 'is-invalid' : ''; ?>"
                                    >
                                        <option value="">-- Select Category --</option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option 
                                              value="<?php echo e($category->id); ?>"
                                              <?php echo e(old('category_id')) == $category->id ? 'selected' : ''; ?>
                                            >
                                                <?php echo e($category->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <?php if ( error('category_id') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('category_id') ); ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" name="quantity" class="form-control <?php echo error('quantity') ? 'is-invalid' : ''; ?>" id="quantity" min="1" value="<?php echo e( old('quantity') ); ?>">

                                    <?php if ( error('quantity') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('quantity') ); ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" name="price" class="form-control <?php echo error('price') ? 'is-invalid' : ''; ?>" id="price" min="1" value="<?php echo e( old('price') ); ?>">

                                    <?php if ( error('price') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('price') ); ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group">
                                    <label for="image">Product Image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php echo error('image') ? 'is-invalid' : ''; ?>" id="image" name="image">
                                            <label class="custom-file-label" for="image">Upload Featured Image</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="">Upload</span>
                                        </div>
                                    </div>

                                    <?php if ( error('image') ): ?>
                                        <div class="invalid-feedback d-block"><?php echo e( error('image') ); ?></div>
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