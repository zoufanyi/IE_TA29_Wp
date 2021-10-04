<div class="calc-item ccb-field" :class="{required: $store.getters.isUnused(dropDownField), [dropDownField.additionalStyles]: dropDownField.additionalStyles}" v-if="Object.keys($store.getters.getCustomStyles).length" :data-id="dropDownField.alias">
    <div class="calc-item__title" :style="$store.getters.getCustomStyles['labels']">
        <span>{{ dropDownField.label }}</span>
        <span v-if="dropDownField.required" class="calc-required-field">
            *
            <div class="ccb-field-required-tooltip">
                <span class="ccb-field-required-tooltip-text" :class="{active: $store.getters.isUnused(dropDownField)}" style="display: none;">{{ $store.getters.getSettings.notice.requiredField }}</span>
            </div>
        </span>
    </div>

    <p v-if="dropDownField.desc_option == 'before'" class="calc-description" :style="$store.getters.getCustomStyles['descriptions']">{{ dropDownField.description }}</p>

    <div :class="'calc_' + dropDownField.alias" class="ccb-drop-down">
        <select  class="calc-drop-down ccb-field vertical" v-model="selectValue" :style="$store.getters.getCustomStyles['drop-down']" >
            <option value="0" selected><?php esc_html_e('- Select value -', 'cost-calculator-builder')?></option>
            <option v-for="element in getOptions" :key="element.value" :value="element.value">{{element.label}}</option>
        </select>
    </div>

    <p v-if="dropDownField.desc_option === undefined || dropDownField.desc_option == 'after'" class="calc-description" :style="$store.getters.getCustomStyles['descriptions']">{{ dropDownField.description }}</p>
</div>