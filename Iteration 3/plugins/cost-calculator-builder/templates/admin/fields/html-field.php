<div class="html-wrapper">
    <div class="list-row">

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_html_e('HTML5 Code', 'cost-calculator-builder')?></label>
            </div>
            <textarea v-model="htmlField.htmlContent"></textarea>
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_html_e('Additional Classes', 'cost-calculator-builder')?></label>
            </div>
            <textarea  v-model="htmlField.additionalStyles"></textarea>
        </div>
    </div>

    <div class="list-row" style="margin-top: 30px">
        <div class="list-content ccb-flex">

            <div class="list-content--header">
                <button type="button" class="green" @click="$emit( 'save', htmlField, id, index)">
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