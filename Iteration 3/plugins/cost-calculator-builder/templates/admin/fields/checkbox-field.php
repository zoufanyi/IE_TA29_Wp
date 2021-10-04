<?php
$pro_active = ccb_pro_active() ? '': 'active';
?>
<div class="checkbox-wrapper">
    <div class="list-row">

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Element Name', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="text" placeholder="<?php esc_attr_e('- Field Label -', 'cost-calculator-builder')?>" v-model.trim="checkboxField.label">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Element Description', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="text" placeholder="<?php esc_attr_e('- Field Description -', 'cost-calculator-builder')?>" v-model.trim="checkboxField.description">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Description Position', 'cost-calculator-builder' ) ?></label>
            </div>
            <select v-model="checkboxField.desc_option">
                <option v-for="(value, key) in getDescOptions" :value="key">{{value}}</option>
            </select>
        </div>

        <div class="list-header ccb-modal-list" style="margin-top: 38px">
            <div class="ccb-switch">
                <input type="checkbox" v-model="checkboxField.allowCurrency"/>
                <label></label>
            </div>
            <div class="list-content-label">
                <label><?php esc_html_e('Currency Symbol On Total Description', 'cost-calculator-builder')?></label>
            </div>
        </div>

        <div class="list-header ccb-modal-list">
            <div class="ccb-switch">
                <input type="checkbox" v-model="checkboxField.allowRound"/>
                <label></label>
            </div>
            <div class="list-content-label">
                <label><?php esc_html_e('Round Value', 'cost-calculator-builder')?></label>
            </div>
        </div>

        <div class="list-header ccb-modal-list">
            <div class="ccb-switch <?php echo esc_attr($pro_active)?>">
                <input type="checkbox" v-model="checkboxField.required"/>
                <label></label>
            </div>
            <div class="list-content-label">
                <label><?php esc_html_e('Required', 'cost-calculator-builder')?></label>
                <span class="pro-feature <?php echo esc_attr($pro_active)?>">
                    <a class="pro-tooltip" target="_blank" href="https://stylemixthemes.com/cost-calculator-plugin/?utm_source=wporg&utm_medium=landing&utm_campaign=2020">
                        pro
                        <span class="pro-tooltip-text">Feature Available <br> in Pro Version</span>
                    </a>
                </span>
            </div>
        </div>

        <div class="list-content">

            <div class="list-content-label">
                <label><?php esc_attr_e( 'Checkbox Options', 'cost-calculator-builder' ) ?></label>
            </div>

            <div class="list-content-options add-options" v-for="(option, index) in checkboxField.options">
                <div class="options">
                    <input  type="text" placeholder="<?php esc_attr_e('Option Label ...', 'cost-calculator-builder')?>"
                            v-model="option.optionText">
                </div>
                <div class="options">
                    <input type="number" placeholder="<?php esc_attr_e('Option Value ...', 'cost-calculator-builder')?>"
                           v-model="option.optionValue" >
                </div>
                <div class="options">
                    <textarea v-model="option.optionHint" placeholder="<?php esc_attr_e('Option Hint ...', 'cost-calculator-builder')?>"></textarea>
                </div>

                <div class="delete-option" @click.prevent="removeOption(index, option.optionValue)">
                    <span>
                        <i class="fas fa-trash-alt"></i>
                    </span>
                </div>
            </div>

            <div class="list-content--footer">
                <button type="button" class="green" @click="addOption">
                    <i class="fas fa-plus"></i>
                    <span><?php esc_html_e('Add Row', 'cost-calculator-builder')?></span>
                </button>
            </div>
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_html_e('Additional Classes', 'cost-calculator-builder')?></label>
            </div>
            <textarea  v-model="checkboxField.additionalStyles"></textarea>
        </div>
    </div>

    <div class="list-row" style="margin-top: 30px">
        <div class="list-content ccb-flex">

            <div class="list-content--header">
                <button type="button" class="green" @click="$emit( 'save', checkboxField, id, index)">
                    <i class="fas fa-save"></i>
                    <span><?php esc_html_e('Save Settings', 'cost-calculator-builder')?></span>
                </button>
            </div>

            <div class="list-content--header">
                <button type="button" class="white" @click="$emit( 'cancel' )">
                    <i class="far fa-times-circle"></i>
                    <span><?php esc_html_e('Cancel Settings', 'cost-calculator-builder')?></span>
                </button>
            </div>

        </div>
    </div>
</div>
