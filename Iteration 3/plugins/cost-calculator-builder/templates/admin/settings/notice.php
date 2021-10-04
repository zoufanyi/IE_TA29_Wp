<?php if(ccb_pro_active()) :
    do_action('render-notice');
    ?>
<?php else:?>
    <div class="list-row disabled">
        <div>
            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Required Field Notice', 'cost-calculator-builder' ) ?></label>
                </div>
                <input type="text" placeholder="<?php esc_attr_e('- Required field notice -', 'cost-calculator-builder') ?>">
            </div>
        </div>
    </div>
<?php endif;?>