<div class="ccb-footer" :class="{show: !disabled}">
    <div class="ccb-footer-buttons">
        <div class="calc-buttons calc-footer">
            <button type="button" @click.prevent="saveSettings">
                <?php esc_html_e('SAVE', 'cost-calculator-builder')?>
            </button>
            <button type="button" @click.prevnet="cancel">
                <?php esc_html_e('Cancel', 'cost-calculator-builder')?>
            </button>
            <button type="button" @click.prevent="openPreview" v-if="active_tab !== 'customize'">
                <?php esc_html_e('PREVIEW', 'cost-calculator-builder')?>
            </button>
        </div>

        <div class="calc-other calc-footer">
            <div class="short-code-desc">
                <div class="ccb-tooltip" @click.prevent="copyText($store.getters.getId)"  @mouseleave="copy.text = 'Copy'">
                    <h6><?php esc_html_e('Shortcode', 'cost-calculator-builder')?></h6>
                    <p><?php esc_html_e('Click to copy', 'cost-calculator-builder')?></p>
                    <span class="ccb-tooltip-text">{{copy.text}}</span>
                </div>
            </div>
            <div class="short-code ccb-tooltip">
                <p @click.prevent="copyText($store.getters.getId)"  @mouseleave="copy.text = 'Copy'">[stm-calc id="{{$store.getters.getId}}"]</p>
                <input :type="copy.type" class="calc-short-code" :data-id="$store.getters.getId" :value='`[stm-calc id="` + $store.getters.getId +`"]`'>
                <span class="ccb-tooltip-text">{{copy.text}}</span>
            </div>
        </div>
    </div>
</div>