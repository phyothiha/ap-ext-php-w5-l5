<?php  
    require '../../config/bootstrap.php';
    require '../../core/Validate.php';
    require 'logic/store.php';

    $stmt = $pdo->prepare("
        SELECT 
            * 
        FROM 
            `products` 
        WHERE 
            `id` = ?
    ");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch();

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

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- place_content -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="bg-transparent px-3 py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h3 class="card-title">Edit Product</h3>
                            <div>
                                <a href="add.php" class="btn btn-sm btn-success">Add New</a>
                            </div>
                        </div>
                        <form role="form" action="" method="POST" enctype="multipart/form-data">
                            <?php method('PUT'); ?>
                            <?php csrf(); ?>
                            <input type="hidden" name="id" value="<?php echo e($product->id); ?>">

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control <?php echo error('name') ? 'is-invalid' : ''; ?>" id="name" value="<?php echo e( old('name', $product->name) ); ?>">

                                    <?php if ( error('name') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('name') ); ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control <?php echo error('description') ? 'is-invalid' : ''; ?>" id="description" name="description" rows="4" ><?php echo e( old('description', $product->description) ); ?></textarea>

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
                                              <?php echo e(old('category_id', $product->category_id)) == $category->id ? 'selected' : ''; ?>
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
                                    <input type="number" name="quantity" class="form-control <?php echo error('quantity') ? 'is-invalid' : ''; ?>" id="quantity" min="1" value="<?php echo e( old('quantity', $product->quantity) ); ?>">

                                    <?php if ( error('quantity') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('quantity') ); ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" name="price" class="form-control <?php echo error('price') ? 'is-invalid' : ''; ?>" id="price" min="1" value="<?php echo e( old('price', $product->price) ); ?>">

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

                                    <?php if ($product->image) : ?>
                                    <div>
                                        <img src="<?php echo e( image_asset_url($product->image) ); ?>" width="150" class="mt-2 mb-1">
                                        <p><?php echo $product->image; ?></p>
                                    </div>
                                    <?php endif; ?>
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

<?php require '../template/footer-dashboard.php' ?>