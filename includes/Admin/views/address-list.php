<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Address book', 'wedevs-academy') ?></h1>
    <a class="page-title-action" href="<?php _e(admin_url('admin.php?page=wedevs-academy&action=new')) ?>"><?php _e('New Address', 'wedevs-academy') ?></a>
    <?php if(isset($_GET['inserted'])) :?>
        <div class="notice notice-success">
            <p><?php _e('Insert has been successfully', 'wedevs-academy') ?></p>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['address-deleted']) && $_GET['address-deleted'] = 'true') :?>
        <div class="notice notice-success">
            <p><?php _e('Address has been deleted successfully', 'wedevs-academy') ?></p>
        </div>
    <?php endif; ?>
    <form action="" method="post">
        <?php 
            $table = new Wedevs\Academy\Admin\Addresslist();
            $table->prepare_items();
            $table->search_box('search', 'search_id');
            $table->display();
        
        ?>
    </form>
</div>