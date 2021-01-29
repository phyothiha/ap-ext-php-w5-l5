<?php  
    require '../../config/bootstrap.php';
    require 'logic/store.php';
?>

<?php require '../template/header-dashboard.php' ?>

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
                            <a href="add.php" class="btn btn-success mb-3">Add New</a>

                            <!--  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require '../template/footer-dashboard.php' ?>