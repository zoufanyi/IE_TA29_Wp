<div class="text-area-wrapper">
    <div class="list-row">
        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Element Name', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="text" placeholder="<?php esc_attr_e('- Field Label -', 'cost-calculator-builder')?>" v-model="textField.label">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Element Placeholder', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="text" placeholder="<?php esc_attr_e('- Field Placeholder -', 'cost-calculator-builder')?>" v-model="textField.placeholder">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Element Description', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="text" placeholder="<?php esc_attr_e('- Field Description -', 'cost-calculator-builder')?>" v-model="textField.description">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Description Position', 'cost-calculator-builder' ) ?></label>
            </div>
            <select v-model="textField.desc_option">
                <option v-for="(value, key) in getDescOptions" :value="key">{{value}}</option>
            </select>
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_html_e('Additional Classes', 'cost-calculator-builder')?></label>
            </div>
            <textarea v-model="textField.additionalStyles"></textarea>
        </div>
    </div>

    <div class="list-row" style="margin-top: 30px">
        <div class="list-content ccb-flex">
            <div class="list-content--header">
                <button type="button" class="green"  @click="$emit( 'save', textField, id, index)">
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