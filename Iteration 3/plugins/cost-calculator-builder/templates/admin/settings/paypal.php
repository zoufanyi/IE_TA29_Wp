<?php if(ccb_pro_active()) :
    do_action('render-paypal');
    ?>
<?php else:?>
    <div class="list-row">
        <?php
        echo \cBuilder\Classes\CCBTemplate::load('/admin/partials/pro-feature');
        ?>
        <div class="list-header">
            <div class="ccb-switch">
                <input type="checkbox"/>
                <label></label>
            </div>
            <h6><?php esc_html_e('Enable PayPal', 'cost-calculator-builder') ?></h6>
        </div>
        <div>
            <div class="list-content" style="margin-top: 0">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Email', 'cost-calculator-builder' ) ?></label>
                </div>
                <input type="text" placeholder="<?php esc_attr_e('- PayPal Email -', 'cost-calculator-builder') ?>">
            </div>

            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Currency', 'cost-calculator-builder' ) ?></label>
                </div>
                <select>
                    <option value="" selected><?php esc_attr_e('- Select Currency Symbol -', 'cost-calculator-builder') ?></option>
                </select>
            </div>

            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Account Type', 'cost-calculator-builder' ) ?></label>
                </div>
                <select>
                    <option value="" selected><?php esc_html_e('- Select type of .... -', 'cost-calculator-builder') ?></option>
                </select>
            </div>

            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Total Field Element', 'cost-calculator-builder' ) ?></label>
                </div>
                <textarea></textarea>
                <?php
                    $desc_url  = 'https://docs.stylemixthemes.com/cost-calculator-builder/pro-plugin-features/woo-checkout';
                    $desc_link = sprintf(
                        wp_kses(
                            __( 'Connection shortcode for linking a selling service or product to online payment systems. %s will be changed into total. <a href="%s">Read more</a>', 'cost-calculator-builder' ),
                            array( 'a' => array( 'href' => array() ) )
                        ),
                        '[ccb-total-0]',
                        esc_url( $desc_url )
                    );
                ?>
                <p class="list-content__desc"><?php echo $desc_link; ?></p>
            </div>
        </div>
    </div>
<?php endif;?>