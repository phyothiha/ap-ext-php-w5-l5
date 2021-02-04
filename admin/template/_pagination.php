<div class="card-footer clearfix">
    <ul class="pagination pagination-sm m-0 float-right">
        <li class="page-item 
            <?php if ($current_page <= 1) { echo 'disabled'; } ?>
        ">
            <a class="page-link" href="?page=1">First</a>
        </li>
        <li class="page-item 
            <?php if ($current_page <= 1) { echo 'disabled'; } ?>
        ">
            <a 
                class="page-link" 
                href="<?php echo ($current_page <= 1) ? '#' : '?page=' . ($current_page - 1); ?>"
            >Prev</a>
        </li>
        <li class="page-item">
            <a class="page-link bg-primary" href="#"><?php echo $current_page; ?></a>
        </li>
        <li class="page-item 
            <?php if ($current_page >= $total) { echo 'disabled'; }  ?>
        ">
            <a 
                class="page-link"
                href="<?php echo ($current_page >= $total) ? '#' : '?page=' . ($current_page + 1); ?>"
            >Next</a>
        </li>
        <li class="page-item 
            <?php if ($current_page >= $total) { echo 'disabled'; }  ?>
        ">
            <a class="page-link" href="?page=<?php echo $total; ?>">Last</a>
        </li>
    </ul>
</div>