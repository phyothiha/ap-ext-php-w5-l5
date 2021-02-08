<?php 
    require '../../config/bootstrap.php';
    require 'logic/store.php';

    $search_col = 'name';

    require '../template/header-dashboard.php'; 

    if (
        empty($_GET['search']) &&
        empty($_COOKIE['search'])
    ) {
        $stmt = $pdo->query("
            SELECT 
                COUNT(*) as 'total' 
            FROM 
                $resource
        ");
        $aggregate = $stmt->fetch();
        $total = ceil($aggregate->total / $per_page_items);

        $stmt = $pdo->prepare("
            SELECT
                *
            FROM 
                $resource 
            ORDER BY 
                `id` DESC 
            LIMIT ?, ?
        ");
        $stmt->bindValue(1, $offset, PDO::PARAM_INT);
        $stmt->bindValue(2, $per_page_items, PDO::PARAM_INT);
        $stmt->execute();
        $categories = $stmt->fetchAll();
    }
?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- place_content -->
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title">Categories Listing</h3>
                        </div>
                        
                        <div class="card-body">
                            <a href="/admin/categories/add.php" class="btn rounded-0 btn-success">Add New</a>

                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th style="width: 300px">Name</th>
                                        <th>Description</th>
                                        <th style="width: 40px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                    if ($categories) : 
                                        foreach ($categories as $index => $category) : 
                                ?>
                                        <tr>
                                            <td><?php echo $index + 1 + $offset; ?></td>
                                            <td><?php echo e($category->name); ?></td>
                                            <td>
                                                <?php echo e(substr($category->description, 0, 50)); ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="/admin/categories/edit.php?id=<?php echo $category->id; ?>" class="btn rounded-0 btn-sm btn-outline-info mr-2"><i class="fa fa-edit"></i></a>
                                                    <form role="form" method="POST">
                                                        <?php method('DELETE'); ?>
                                                        <?php csrf(); ?>
                                                        <input type="hidden" name="id" value="<?php echo $category->id; ?>">
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