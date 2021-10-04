<div class="calc-item ccb-field" :class="{required: $store.getters.isUnused(radioField), [radioField.additionalStyles]: radioField.additionalStyles}" v-if="Object.keys($store.getters.getCustomStyles).length" :data-id="radioField.alias">
    <div class="calc-item__title" :style="$store.getters.getCustomStyles['labels']">
        <span> {{ radioField.label }} </span>
        <span v-if="radioField.required" class="calc-required-field">
            *
            <div class="ccb-field-required-tooltip">
                <span class="ccb-field-required-tooltip-text" :class="{active: $store.getters.isUnused(radioField)}" style="display: none;">{{ $store.getters.getSettings.notice.requiredField }}</span>
            </div>
        </span>
    </div>

    <p v-if="radioField.desc_option == 'before'" class="calc-description" :style="$store.getters.getCustomStyles['descriptions']">{{ radioField.description }}</p>

    <div class="calc-radio" :class="'calc_' + radioField.alias" :class="'calc_' + radioField.alias">
        <div class="calc-radio-item" v-for="(element, index) in getOptions">
            <input :id="radioLabel + index" type="radio" :name="radioLabel" v-model="radioValue" :value="element.value">
            <label :for="radioLabel + index" :style="$store.getters.getCustomStyles['radio-button']">{{ element.label }}</label>
        </div>
    </div>

    <p v-if="radioField.desc_option === undefined || radioField.desc_option == 'after'" class="calc-description" :style="$store.getters.getCustomStyles['descriptions']">{{ radioField.description }}</p>
</div>