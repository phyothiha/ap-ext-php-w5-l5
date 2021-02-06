<?php 
    require '../../config/bootstrap.php';
    // require 'logic/store.php';

    $search_col = 'name';

    require '../template/header-dashboard.php'; 

    if (! isset($_GET['search'])) {
        $stmt = $pdo->query("
            SELECT 
                COUNT(*) as 'total' 
            FROM 
                `sale_orders`
        ");
        $aggregate = $stmt->fetch();
        $total = ceil($aggregate->total / $per_page_items);

        $stmt = $pdo->prepare("
            SELECT
                `sale_orders`.*,
                `users`.`name` as 'user_name'
            FROM 
                `sale_orders`
            JOIN
                `users`
            ON
                `users`.`id` = `sale_orders`.`user_id`
            ORDER BY 
                `sale_orders`.`id` DESC 
            LIMIT ?, ?
        ");
        $stmt->bindValue(1, $offset, PDO::PARAM_INT);
        $stmt->bindValue(2, $per_page_items, PDO::PARAM_INT);
        $stmt->execute();
        $sale_orders = $stmt->fetchAll();
    }
?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- place_content -->
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title">Sale Orders Listing</h3>
                        </div>
                        
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th style="width: 300px">User</th>
                                        <th>Total</th>
                                        <th style="width: 150px">Ordered Date</th>
                                        <th style="width: 40px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                    if ($sale_orders) : 
                                        foreach ($sale_orders as $index => $order) : 
                                ?>
                                        <tr>
                                            <td><?php echo $index + 1 + $offset; ?></td>
                                            <td><?php echo e($order->user_name); ?></td>
                                            <td><?php echo e($order->total_price); ?></td>
                                            <td><?php echo e(date('Y-m-d', strtotime($order->order_date))); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="/admin/orders/view.php?id=<?php echo $order->id; ?>" class="btn rounded-0 btn-sm btn-outline-primary mr-2"><i class="fa fa-eye"></i></a>
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