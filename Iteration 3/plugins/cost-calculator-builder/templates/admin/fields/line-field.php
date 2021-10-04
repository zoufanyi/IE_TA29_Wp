<div class="radio-wrapper">
    <div class="list-row">
        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Line Size', 'cost-calculator-builder' ) ?></label>
            </div>
            <select v-model="lineField.size">
                <option value="" selected><?php esc_html_e('- Select Size -', 'cost-calculator-builder')?></option>
                <option value="1px"><?php esc_html_e('small', 'cost-calculator-builder')?></option>
                <option value="2px"><?php esc_html_e('medium', 'cost-calculator-builder')?></option>
                <option value="4px"><?php esc_html_e('large', 'cost-calculator-builder')?></option>
            </select>
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Line Style', 'cost-calculator-builder' ) ?></label>
            </div>
            <select v-model="lineField.style">
                <option value="" selected><?php esc_html_e('- Select Style -', 'cost-calculator-builder')?></option>
                <option value="solid"><?php esc_html_e('solid', 'cost-calculator-builder')?></option>
                <option value="dashed"><?php esc_html_e('dashed', 'cost-calculator-builder')?></option>
            </select>
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Line Length', 'cost-calculator-builder' ) ?></label>
            </div>
            <select v-model="lineField.len">
                <option value="" selected><?php esc_html_e('- Select length -', 'cost-calculator-builder')?></option>
                <option value="25%"><?php esc_html_e('short', 'cost-calculator-builder')?></option>
                <option value="50%"><?php esc_html_e('medium', 'cost-calculator-builder')?></option>
                <option value="100%"><?php esc_html_e('long', 'cost-calculator-builder')?></option>
            </select>
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_html_e('Additional Classes', 'cost-calculator-builder')?></label>
            </div>
            <textarea v-model="lineField.additionalStyles"></textarea>
        </div>
    </div>

    <div class="list-row" style="margin-top: 30px">
        <div class="list-content ccb-flex">

            <div class="list-content--header">
                <button type="button" class="green" @click="$emit( 'save', lineField, id, index)">
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
