<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('New Address', 'wedevs-academy') ?></h1>
    <?php //var_dump($this->errors); ?>
    <?php if(isset($_GET['inserted'])) :?>
        <div class="notice notice-success">
            <p><?php _e('Insert has been successfully', 'wedevs-academy') ?></p>
        </div>
    <?php endif; ?>
    
    <form action="" method="post">
        <div>
            <label for="name"><?php _e('Name', 'wedevs-academy') ?></label>    
            <input type="text" name="name" id="name" value="">
        </div>
        
        <div>
            <label for="address"><?php _e('Address', 'wedevs-academy') ?></label>
            <textarea name="address" id="address"></textarea>
        </div>     
        <div>
            <label for="phone"><?php _e('Phone', 'wedevs-academy') ?></label>
            <input type="text" name="phone" id="phone" value="">
        </div>
        <input type="hidden" name="submit_frontend_address" value="submit_frontend_address">
        <?php wp_nonce_field('new-address'); ?>
        <?php submit_button(__('Add Address', 'wedevs-academy'), 'primary', 'submit_address') ?>
    </form>
</div>