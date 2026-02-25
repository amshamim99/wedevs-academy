<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Eidit Address', 'wedevs-academy') ?></h1>
    <?php //var_dump($address); ?>
    <?php if(isset($_GET['address-updated'])) :?>
        <div class="notice notice-success">
            <p><?php _e('Update has been successfully', 'wedevs-academy') ?></p>
        </div>
    <?php endif; ?>
    <form action="" method="post">
        <table class="from-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="name"><?php _e('Name', 'wedevs-academy') ?></label></th>
                    <td>
                        <input type="text" name="name" id="name" value="<?php echo esc_attr($address->name);?>">
                        <?php if($this->has_error('name')): ?>
                            <p  class="discription-error"><?php echo $this->get_error('name'); ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="address"><?php _e('Address', 'wedevs-academy') ?></label></th>
                    <td><textarea name="address" id="address"><?php echo esc_textarea($address->address);?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="phone"><?php _e('Phone', 'wedevs-academy') ?></label></th>
                    <td>
                        <input type="text" name="phone" id="phone" value="<?php echo esc_attr($address->phone);?>">
                        <?php if($this->has_error('phone')): ?>
                            <p  class="discription-error"><?php echo $this->get_error('phone'); ?></p>
                        <?php endif; ?>
                    </td>
                    
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="id" value="<?php echo esc_attr($address->id);?>">
        <?php wp_nonce_field('new-address'); ?>
        <?php submit_button(__('Update Address', 'wedevs-academy'), 'primary', 'submit_address') ?>
    </form>
</div>