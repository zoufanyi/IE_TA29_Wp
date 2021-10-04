<?php if(ccb_pro_active()) :
    do_action('render-date-picker');
 ?>
<?php else:?>
    <div class="date-picker-wrapper">
        <?php
            echo \cBuilder\Classes\CCBTemplate::load('/admin/partials/pro-feature');
        ?>
        <div class="list-row">

            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Element Name', 'cost-calculator-builder' ) ?></label>
                </div>
                <input type="text" placeholder="<?php esc_attr_e('- Field Label -', 'cost-calculator-builder')?>">
            </div>

            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Element Description', 'cost-calculator-builder' ) ?></label>
                </div>
                <input type="text" placeholder="<?php esc_attr_e('- Field Description -', 'cost-calculator-builder')?>">
            </div>

            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Element Placeholder', 'cost-calculator-builder' ) ?></label>
                </div>
                <input type="text" placeholder="<?php esc_attr_e('- Field Placeholder -', 'cost-calculator-builder')?>">
            </div>

            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Description Position', 'cost-calculator-builder' ) ?></label>
                </div>
                <select>
                    <option value="before"><?php esc_html_e('Show before field', 'cost-calculator-builder');?></option>
                    <option value="after"><?php esc_html_e('Show after field', 'cost-calculator-builder');?></option>
                </select>
            </div>
            <div class="list-header ccb-modal-list" style="margin-top: 38px">
                <div class="ccb-switch">
                    <input type="checkbox" v-model="dateField.required"/>
                    <label></label>
                </div>
                <div class="list-content-label">
                    <label><?php esc_html_e('Required', 'cost-calculator-builder')?></label>
                </div>
            </div>
            <div class="list-content">
                <div class="list-content-label">
                    <label><?php esc_attr_e( 'Calendar Option', 'cost-calculator-builder' ) ?></label>
                </div>
                <select>
                    <option value="1"><?php esc_html_e('With range', 'cost-calculator-builder');?></option>
                    <option value="0"><?php esc_html_e('No range', 'cost-calculator-builder');?></option>
                </select>
            </div>

            <div class="list-content">
                <h6><?php esc_html_e('Additional Classes', 'cost-calculator-builder')?></h6>
                <textarea></textarea>
            </div>
        </div>

        <div class="list-row" style="margin-top: 30px">
            <div class="list-content ccb-flex">

                <div class="list-content--header">
                    <button type="button" class="green">
                        <i class="fas fa-save"></i>
                        <span><?php esc_html_e('Save Settings', 'cost-calculator-builder')?></span>
                    </button>
                </div>

                <div class="list-content--header">
                    <button type="button" class="white">
                        <i class="far fa-times-circle"></i>
                        <span><?php esc_html_e('Cancel Settings', 'cost-calculator-builder')?></span>
                    </button>
                </div>

            </div>
        </div>
    </div>
<?php endif;?>
