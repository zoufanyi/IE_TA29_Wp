<?php if(ccb_pro_active()) :
    do_action('render-woo-checkout');
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
            <h6><?php esc_html_e('Enable WooCommerce Checkout', 'cost-calculator-builder') ?></h6>
        </div>
        <div>
            <div class="list-content" style="margin-top: 0">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Product Category', 'cost-calculator-builder' ) ?></label>
                </div>
                <select>
                    <option value="" selected><?php esc_html_e('- Select WooCommerce Product -', 'cost-calculator-builder') ?></option>
                </select>
            </div>

            <div class="list-content">
                <h6><?php esc_html_e('Redirect after Submits', 'cost-calculator-builder') ?></h6>
                <div class="ccb-radio-wrapper">
                    <input id="redirect_to_cart" type="radio"  name="redirect_to" value="cart" checked>
                    <label for="redirect_to_cart">
                        <?php esc_html_e('to Cart page', 'cost-calculator-builder') ?>
                    </label>

                    <input id="redirect_to_checkout" type="radio" name="redirect_to" value="checkout">
                    <label for="redirect_to_checkout">
                        <?php esc_html_e('to Checkout page', 'cost-calculator-builder') ?>
                    </label>
                </div>
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