<?php if(ccb_pro_active()) :
    do_action('render-recaptcha');
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
            <h6><?php esc_html_e('Enable reCAPTCHA', 'cost-calculator-builder') ?></h6>
        </div>
        <div>

            <div class="list-content" style="margin-top: 0">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'reCAPTCHA version', 'cost-calculator-builder' ) ?></label>
                </div>
                <select>
                    <option value="v2"><?php esc_html_e('- Google reCAPTCHA v2 -', 'cost-calculator-builder') ?></option>
                </select>
            </div>

            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Site Key', 'cost-calculator-builder' ) ?></label>
                </div>
                <input type="text" placeholder="<?php esc_attr_e('- Paste reCAPTCHA Site Key -', 'cost-calculator-builder') ?>">
            </div>

            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Secret Key', 'cost-calculator-builder' ) ?></label>
                </div>
                <input type="text" placeholder="<?php esc_attr_e('- Paste reCAPTCHA Secret Key -', 'cost-calculator-builder') ?>">
            </div>
        </div>
    </div>
<?php endif;?>