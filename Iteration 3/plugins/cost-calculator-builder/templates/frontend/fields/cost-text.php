<div class="calc-item" v-if="Object.keys($store.getters.getCustomStyles).length" :class="textField.additionalStyles" :data-id="textField.alias">
    <div class="calc-item__title" :style="$store.getters.getCustomStyles['labels']">
        <span>{{ textField.label }}</span>
    </div>

    <p v-if="textField.desc_option == 'before'" class="calc-description" :style="$store.getters.getCustomStyles['descriptions']">{{ textField.description }}</p>

    <textarea :id="labelId" :placeholder="textField.placeholder" class="calc-textarea" :style="$store.getters.getCustomStyles['text-area']"></textarea>

    <p v-if="textField.desc_option === undefined || textField.desc_option == 'after'" class="calc-description" :style="$store.getters.getCustomStyles['descriptions']">{{ textField.description }}</p>
</div>
