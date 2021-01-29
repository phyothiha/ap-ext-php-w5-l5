<?php  
    require '../../bootstrap.php';
    require '../../src/Validate.php';
    require 'logic/store.php';
?>

<?php get_header( null, [
    'body_classes' => 'sidebar-mini'
]); ?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- place_content -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="bg-transparent px-3 py-3 border-bottom">
                            <h3 class="card-title">Add New Blog Post</h3>
                        </div>
                        <form role="form" action="" method="POST" enctype="multipart/form-data">
                            <?php csrf(); ?>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" class="form-control <?php echo error('title') ? 'is-invalid' : ''; ?>" id="title" value="<?php echo e( old('title') ); ?>">

                                    <?php if ( error('title') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('title') ); ?></div>
                                    <?php endif ?>
                                </div>
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea class="form-control <?php echo error('content') ? 'is-invalid' : ''; ?>" id="content" name="content" rows="6" ><?php echo e( old('content') ); ?></textarea>

                                    <?php if ( error('content') ): ?>
                                        <div class="invalid-feedback"><?php echo e( error('content') ); ?></div>
                                    <?php endif ?>
                                </div>
                                <div class="form-group">
                                    <label for="featured_image">Featured Image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php echo error('featured_image') ? 'is-invalid' : ''; ?>" id="featured_image" name="featured_image">
                                            <label class="custom-file-label" for="featured_image">Upload Featured Image</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="">Upload</span>
                                        </div>
                                    </div>

                                    <?php if ( error('featured_image') ): ?>
                                        <div class="invalid-feedback d-block"><?php echo e( error('featured_image') ); ?></div>
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

<?php get_footer(); ?>