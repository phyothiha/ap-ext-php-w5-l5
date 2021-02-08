<?php 
    require 'config/bootstrap.php';

    include 'header.php'; 

    /** Category */
    $stmt = $pdo->query("
        SELECT 
            *
        FROM 
            `categories`
        ORDER BY
            `id` DESC
    ");
    $stmt->execute();
    $categories = $stmt->fetchAll();

    if (
        empty($_GET['search']) &&
        empty($_COOKIE['search'])
    ) {
        $stmt = $pdo->query("
            SELECT 
                COUNT(*) as 'total' 
            FROM 
                `products`
        ");
        $aggregate = $stmt->fetch();
        $total = ceil($aggregate->total / $per_page_items);

        $stmt = $pdo->prepare("
            SELECT
                *
            FROM 
                `products` 
            ORDER BY 
                `id` DESC 
            LIMIT ?, ?
        ");
        $stmt->bindValue(1, $offset, PDO::PARAM_INT);
        $stmt->bindValue(2, $per_page_items, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll();
    }


    if (isset($_GET['category'])) {
        $stmt = $pdo->prepare("
            SELECT
                `products`.*
            FROM
                `products`
            JOIN
                `categories`
            ON 
                `products`.`category_id` = `categories`.`id`
            WHERE
                `categories`.`id` = ?
        ");

        $stmt->execute([$_GET['category']]);
        $products = $stmt->fetchAll();     
    }
?>

<div class="container">
    <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-5">
            <div class="sidebar-categories">
                <div class="head">Browse Categories</div>
                <ul class="main-categories">
                    <li class="main-nav-list">

                        <?php foreach ($categories as $category) : ?>

                        <a href="?category=<?php echo $category->id; ?>">
                            <span class="lnr lnr-arrow-right"></span><?php echo e($category->name); ?>
                        </a>
                        
                        <?php endforeach; ?>

                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-xl-9 col-lg-8 col-md-7">
            <!-- Start Filter Bar -->
            <div class="filter-bar d-flex flex-wrap align-items-center">
                <div class="pagination">
                    <a href="?page=1" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
                    <a href="<?php echo ($current_page <= 1) ? '#' : '?page=' . ($current_page - 1); ?>" class="prev-arrow"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                    <a href="#" class="active"><?php echo $current_page; ?></a>
                    <a href="<?php echo ($current_page >= $total) ? '#' : '?page=' . ($current_page + 1); ?>" class="next-arrow"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                    <a href="?page=<?php echo $total; ?>" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
        	<!-- End Filter Bar -->

        	<!-- Start Best Seller -->
        	<section class="lattest-product-area pb-40 category-list">
        		<div class="row">

                    <?php foreach ($products as $product): ?>
                        
        			<!-- single product -->
        			<div class="col-lg-4 col-md-6">
        				<div class="single-product">
                            <?php if ($product->image) : ?>
                                <img src="<?php echo e( image_asset_url($product->image) ); ?>" alt="No Featured Image" width="150" class="img-fluid">
                            <?php else: ?>
                                <img class="img-fluid" src="public/custom-theme-assets/img/product/p1.jpg" alt="">
                            <?php endif; ?>

        					<div class="product-details">
        						<h6><?php echo e($product->name); ?></h6>

        						<div class="price">
        							<h6>$ <?php echo e($product->price); ?></h6>
        						</div>

        						<div class="prd-bottom">
        							<a href="" class="social-info">
        								<span class="ti-bag"></span>
        								<p class="hover-text">add to bag</p>
        							</a>
        							<a href="/product-detail.php?id=<?php echo e($product->id); ?>" class="social-info">
        								<span class="lnr lnr-move"></span>
        								<p class="hover-text">view more</p>
        							</a>
        						</div>

        					</div>
        				</div>
        			</div>

                    <?php endforeach ?>
        		</div>
        	</section>
        	<!-- End Best Seller -->
        </div>
    </div>
</div>

<?php include 'footer.php' ?>