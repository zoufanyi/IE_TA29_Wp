<div class="range-wrapper">
    <div class="list-row">
        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Element Name', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="text" placeholder="<?php esc_attr_e('- Field Label -', 'cost-calculator-builder')?>" v-model.trim="rangeField.label">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Element Description', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="text" placeholder="<?php esc_attr_e('- Field Description -', 'cost-calculator-builder')?>" v-model.trim="rangeField.description">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Description Position', 'cost-calculator-builder' ) ?></label>
            </div>
            <select v-model="rangeField.desc_option">
                <option v-for="(value, key) in getDescOptions" :value="key">{{value}}</option>
            </select>
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Minimum Range Value', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="number" placeholder="<?php esc_attr_e('- Min Value -', 'cost-calculator-builder')?>" v-model="rangeField.minValue">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Maximum Range Value', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="number" placeholder="<?php esc_attr_e('- Max Value -', 'cost-calculator-builder')?>" v-model="rangeField.maxValue">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Range Step', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="number" placeholder="<?php esc_attr_e('- Step -', 'cost-calculator-builder')?>" v-model="rangeField.step">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Range Default Value', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="number" placeholder="<?php esc_attr_e('- Default Value -', 'cost-calculator-builder')?>" v-model="rangeField.default">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Currency Symbol', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="text" placeholder="<?php esc_attr_e('- Sign -', 'cost-calculator-builder')?>" v-model="rangeField.sign">
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_attr_e( 'Range Unit', 'cost-calculator-builder' ) ?></label>
            </div>
            <input type="number" placeholder="<?php esc_attr_e('- Unit -', 'cost-calculator-builder')?>" v-model="rangeField.unit">
        </div>

        <div class="list-header ccb-modal-list" style="margin-top: 38px">
            <div class="ccb-switch">
                <input type="checkbox" v-model="rangeField.allowCurrency"/>
                <label></label>
            </div>
            <div class="list-content-label">
                <label><?php esc_html_e('Currency Symbol On Total Description', 'cost-calculator-builder')?></label>
            </div>
        </div>

        <div class="list-header ccb-modal-list">
            <div class="ccb-switch">
                <input type="checkbox" v-model="rangeField.allowRound"/>
                <label></label>
            </div>
            <div class="list-content-label">
                <label><?php esc_html_e('Round Value', 'cost-calculator-builder')?></label>
            </div>
        </div>

        <div class="list-content">
            <div class="list-content-label">
                <label><?php esc_html_e('Additional Classes', 'cost-calculator-builder')?></label>
            </div>
            <textarea v-model="rangeField.additionalStyles"></textarea>
        </div>
    </div>

    <div class="list-row" style="margin-top: 30px">
        <div class="list-content ccb-flex">

            <div class="list-content--header">
                <button type="button" class="green" @click="$emit( 'save', rangeField, id, index)">
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
