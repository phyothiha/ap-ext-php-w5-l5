<?php 
    require '../../config/bootstrap.php';
    require 'logic/store.php';

    $search_col = 'products.name';
    $searchPreparedQuery = "
        SELECT
            `products`.`id`, `products`.`name`,
            `products`.`quantity`, 
            `products`.`price`, `products`.`image`,
            `categories`.`name` as 'category_name'
        FROM 
            products
        JOIN
            `categories`  
        ON
            `products`.`category_id` = `categories`.`id`
        WHERE 
            $search_col LIKE ? 
        ORDER BY 
            `id` DESC 
        LIMIT ?, ?
    ";

    require '../template/header-dashboard.php'; 

    if (! isset($_GET['search'])) {
        $stmt = $pdo->query("
            SELECT 
                COUNT(*) as 'total' 
            FROM 
                products
        ");
        $aggregate = $stmt->fetch();
        $total = ceil($aggregate->total / $per_page_items);

        $stmt = $pdo->prepare("
            SELECT
                `products`.`id`, `products`.`name`,
                `products`.`quantity`, 
                `products`.`price`, `products`.`image`,
                `categories`.`name` as 'category_name'
            FROM 
                products
            JOIN
                `categories`  
            ON
                `products`.`category_id` = `categories`.`id`;
            ORDER BY 
                `id` DESC 
            LIMIT ?, ?
      ");
        $stmt->bindValue(1, $offset, PDO::PARAM_INT);
        $stmt->bindValue(2, $per_page_items, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll();
    }
?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- place_content -->
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title">Products Listing</h3>
                        </div>
                        
                        <div class="card-body">
                            <a href="/admin/products/add.php" class="btn btn-success mb-3">Add New</a>

                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th style="width: 300px">Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th width="150px">Image</th>
                                        <th style="width: 40px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                    if ($products) : 
                                        foreach ($products as $index => $product) : 
                                ?>
                                        <tr>
                                            <td><?php echo $index + 1 + $offset; ?></td>
                                            <td><?php echo e($product->name); ?></td>
                                            <td><?php echo e($product->category_name) ?></td>
                                            <td><?php echo e($product->quantity) ?></td>
                                            <td><?php echo e($product->price) ?></td>
                                            <td>
                                                <?php if ($product->image) : ?>
                                                    <img src="<?php echo e( image_asset_url($product->image) ); ?>" alt="No Featured Image" width="150">
                                                <?php else: ?>
                                                    No Product Image
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="/admin/products/edit.php?id=<?php echo $product->id; ?>" class="btn rounded-0 btn-sm btn-outline-info mr-2"><i class="fa fa-edit"></i></a>
                                                    <form role="form" method="POST">
                                                        <?php method('DELETE'); ?>
                                                        <?php csrf(); ?>
                                                        <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                                                        <button onclick="return confirm('Are you sure you want to delete this item?');" type="submit" class="btn rounded-0 btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                <?php   endforeach; 
                                    else : 
                                ?>
                                    <tr>
                                        <td colspan="7" class="text-center bg-info">No Records</td>
                                    </tr>
                                <?php endif; ?>

                                </tbody>
                            </table>
                        </div>
                        
                        <?php require '../template/_pagination.php' ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require '../template/footer-dashboard.php' ?>