<?php 
    require 'config/bootstrap.php';

    include 'header.php';

    $stmt = $pdo->prepare("
        SELECT
            `products`.*,
            `categories`.`name` as 'category_name'
        FROM
            `products`
        JOIN
            `categories`
        ON 
            `products`.`category_id` = `categories`.`id`
        WHERE
            `products`.`id` = ?
    ");

    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch();
?>

<!--================Single Product Area =================-->
<div class="product_image_area">
  <div class="container">
    <div class="row s_product_inner">
      <div class="col-lg-6 text-center">

        <?php if ($product->image) : ?>
            <img src="<?php echo e( image_asset_url($product->image) ); ?>" alt="No Featured Image" width="450" class="img-fluid">
        <?php else: ?>
            <img class="img-fluid" src="public/custom-theme-assets/img/product/p1.jpg" alt="">
        <?php endif; ?>

      </div>

      <div class="col-lg-5 offset-lg-1">
        <div class="s_product_text">
          <h3><?php echo e($product->name); ?></h3>
          <h2>$ <?php echo e($product->price); ?></h2>

          <ul class="list">
            <li><a class="active" href="#"><span>Category</span> : <?php echo e($product->category_name); ?></a></li>
            <li><a href="#"><span>Availibility</span> : In Stock</a></li>
          </ul>

          <p><?php echo e($product->description); ?></p>

          <div class="product_count">
            <label for="qty">Quantity:</label>
            <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
            <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
             class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
            <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
             class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
          </div>
          <div class="card_area d-flex align-items-center">
            <a class="primary-btn" href="#">Add to Cart</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><br>
<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<?php 
    include 'footer.php';
?>
