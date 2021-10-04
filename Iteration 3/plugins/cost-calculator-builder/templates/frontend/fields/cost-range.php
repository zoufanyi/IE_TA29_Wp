<div class="calc-item" v-if="Object.keys($store.getters.getCustomStyles).length" :class="rangeField.additionalStyles" :data-id="rangeField.alias" >
    <div class="calc-range" :class="'calc_' + rangeField.alias">
        <div class="calc-item__title" :style="$store.getters.getCustomStyles['labels']" style="display: flex; justify-content: space-between">
            <span> {{ rangeField.label }} </span>
            <span> {{ getFormatedValue }} {{ rangeField.sign ? rangeField.sign : '' }}</span>
        </div>

        <p v-if="rangeField.desc_option == 'before'" class="calc-description" :style="$store.getters.getCustomStyles['descriptions']">{{ rangeField.description }}</p>

        <div :class="['range_' + rangeField.alias]"></div>

        <p v-if="rangeField.desc_option === undefined || rangeField.desc_option == 'after'" class="calc-description" :style="$store.getters.getCustomStyles['descriptions']">{{ rangeField.description }}</p>
    </div>
</div>