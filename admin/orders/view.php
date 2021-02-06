<?php 
    require '../../config/bootstrap.php';

    $stmt = $pdo->prepare("
        SELECT
            `sale_orders`.`total_price`,
            `sale_orders_detail`.`sale_order_id`,
            `sale_orders_detail`.`quantity`,
            `users`.`name` as 'user_name',
            `products`.`name` as 'product_name'
        FROM
            `sale_orders`
        JOIN
            `users`
        ON
            `users`.`id` = `sale_orders`.`user_id`
        JOIN
            `sale_orders_detail`
        ON
            `sale_orders_detail`.`sale_order_id` = `sale_orders`.`id`
        JOIN
            `products`
        ON
            `products`.`id` = `sale_orders_detail`.`product_id` 
        WHERE
            `sale_orders`.`id` = 1;
    ");
    $stmt->execute();
    $item = $stmt->fetch();
?>

<?php require '../template/header-dashboard.php' ?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- place_content -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="bg-transparent px-3 py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h3 class="card-title">Order ID - <?php echo e($item->sale_order_id); ?></h3>
                            <div>
                                <a href="/admin/orders/index.php" class="btn btn-sm btn-outline-secondary">Back</a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td class="bg-secondary">User</td>
                                                <td><?php echo e($item->user_name); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="bg-secondary">Product</td>
                                                <td><?php echo e($item->product_name); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="bg-secondary">Quantity</td>
                                                <td><?php echo e($item->quantity); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="bg-secondary">Total Price</td>
                                                <td><?php echo e($item->total_price); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require '../template/footer-dashboard.php' ?>