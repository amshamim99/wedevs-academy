<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('New Address', 'wedevs-academy') ?></h1>
    <?php //var_dump($this->errors); ?>
    <form action="" method="post">
        <table class="from-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="name"><?php _e('Name', 'wedevs-academy') ?></label></th>
                    <td>
                        <input type="text" name="name" id="name" value="">
                        <?php if($this->has_error('name')): ?>
                            <p  class="discription-error"><?php echo $this->get_error('name'); ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="address"><?php _e('Address', 'wedevs-academy') ?></label></th>
                    <td><textarea name="address" id="address"></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="phone"><?php _e('Phone', 'wedevs-academy') ?></label></th>
                    <td>
                        <input type="text" name="phone" id="phone" value="">
                        <?php if($this->has_error('phone')): ?>
                            <p  class="discription-error"><?php echo $this->get_error('phone'); ?></p>
                        <?php endif; ?>
                    </td>
                    
                </tr>
            </tbody>
        </table>
        <?php wp_nonce_field('new-address'); ?>
        <?php submit_button(__('Add Address', 'wedevs-academy'), 'primary', 'submit_address') ?>
    </form>
</div>