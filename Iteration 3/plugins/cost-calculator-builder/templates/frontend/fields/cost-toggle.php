<div class="calc-item ccb-field" :class="{required: $store.getters.isUnused(toggleField), [toggleField.additionalStyles]: toggleField.additionalStyles}" v-if="Object.keys($store.getters.getCustomStyles).length" :data-id="toggleField.alias">
    <div class="calc-item__title" :style="$store.getters.getCustomStyles['labels']">
        <span> {{ toggleField.label }} </span>
        <span v-if="toggleField.required" class="calc-required-field">
        *
        <div class="ccb-field-required-tooltip">
            <span class="ccb-field-required-tooltip-text" :class="{active: $store.getters.isUnused(toggleField)}" style="display: none;">{{ $store.getters.getSettings.notice.requiredField }}</span>
        </div>
    </div>

    <p v-if="toggleField.desc_option == 'before'" class="calc-description" :style="$store.getters.getCustomStyles['descriptions']">{{ toggleField.description }}</p>

    <div class="calc-toggle" :class="'calc_' + toggleField.alias">
        <div class="calc-switch"  v-for="( element, index ) in getOptions">
            <input type="checkbox" :id="toggleLabel + index" :value="element.value" @change="change(event, element.label)">
            <label :for="toggleLabel + index"></label>
            <span @click="toggle(toggleLabel + index, element.label)" :style="$store.getters.getCustomStyles['toggle']">
                {{ element.label }}
                <span class="ccb-checkbox-hint" v-if="element.hint">
                    <img src="<?php echo esc_url(CALC_URL . '/frontend/dist/img/information.svg') ?>">
                    <span class="ccb-checkbox-hint__content">{{ element.hint }}</span>
                </span>
            </span>
        </div>
    </div>

    <p v-if="toggleField.desc_option === undefined || toggleField.desc_option == 'after'" class="calc-description" :style="$store.getters.getCustomStyles['descriptions']">{{ toggleField.description }}</p>
</div>
