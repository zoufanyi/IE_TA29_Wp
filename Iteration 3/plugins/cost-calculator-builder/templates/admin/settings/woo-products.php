<?php if (ccb_pro_active()) :
    do_action('render-woo-products');
?>
<?php else: ?>
    <div class="list-row">
        <?php
        echo \cBuilder\Classes\CCBTemplate::load('/admin/partials/pro-feature');
        ?>
        <div class="list-header">
            <div class="ccb-switch">
                <input type="checkbox"/>
                <label></label>
            </div>
            <h6><?php esc_html_e('Enable Calculator for WooCommerce Products', 'cost-calculator-builder') ?></h6>
        </div>
        <div>
            <div class="list-content" style="margin-top: 0">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Product Category', 'cost-calculator-builder' ) ?></label>
                </div>
                <select>
                    <option value="" selected><?php esc_html_e('All Categories', 'cost-calculator-builder') ?></option>
                </select>
            </div>

            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Calculator Position', 'cost-calculator-builder' ) ?></label>
                </div>
                <select>
                    <option value="" selected><?php esc_html_e('- Select Hook For Showing Calculator -', 'cost-calculator-builder') ?></option>
                </select>
            </div>

            <div class="list-header" style="margin-top: 30px">
                <div class="ccb-switch">
                    <input type="checkbox"/>
                    <label></label>
                </div>
                <h6><?php esc_html_e('Hide WooCommerce Add To Cart Form', 'cost-calculator-builder') ?></h6>
            </div>

            <div class="list-header" >
                <div class="ccb-switch">
                    <input type="checkbox"/>
                    <label></label>
                </div>
                <h6><?php esc_html_e('Disable Checkout From Calculator', 'cost-calculator-builder') ?></h6>
            </div>

            <div class="list-content" style="margin-top: 10px;">
                <h6><?php esc_html_e('Link WooCommerce Meta to Calculator Fields:', 'cost-calculator-builder-pro') ?></h6>

                <div class="list-content woo-links" style="margin-top: 15px">
                    <div class="ccb-select-wrap">
                        <label class="ccb-select-label"><?php esc_html_e('WooCommerce Meta', 'cost-calculator-builder-pro') ?></label>
                        <label class="ccb-select-label"><?php esc_html_e('Action', 'cost-calculator-builder-pro') ?></label>
                        <label class="ccb-select-label"><?php esc_html_e('Calculator Field', 'cost-calculator-builder-pro') ?></label>
                    </div>
                </div>

                <div class="list-content" style="margin-top: 15px">
                    <div class="list-btn-item">
                        <button type="button" class="green">
                            <i class="fas fa-plus"></i>
                            <span><?php esc_html_e('Add New Link', 'cost-calculator-builder')?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>