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
        $users = $stmt->fetchAll();
    }
?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- place_content -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Users Listing</h3>
                        </div>
                        
                        <div class="card-body">
                            <a href="/admin/users/add.php" class="btn btn-success mb-3">Add New</a>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th width="150px">Role</th>
                                        <th style="width: 40px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php 
                                    if ($users) : 
                                        foreach ($users as $index => $user) : 
                                ?>
                                        <tr>
                                            <td><?php echo $index + 1 + $offset; ?></td>
                                            <td><?php echo e($user->name); ?></td>
                                            <td>
                                                <?php echo e($user->email); ?>
                                            </td>
                                            <td>
                                                <?php echo ($user->role) ? 'Admin' : 'User'; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="/admin/users/edit.php?id=<?php echo $user->id; ?>" class="btn rounded-0 btn-sm btn-outline-info mr-2"><i class="fa fa-edit"></i></a>
                                                    <?php if ($_SESSION['user_id'] != $user->id): ?>
                                                        <form role="form" action="" method="POST">
                                                            <?php method('DELETE'); ?>
                                                            <?php csrf(); ?>
                                                            <input type="hidden" name="id" value="<?php echo $user->id; ?>">
                                                            <button onclick="return confirm('Are you sure you want to delete this item');" type="submit" class="btn rounded-0 btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                                                        </form>
                                                    <?php endif ?>
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