<div class="calc-item ccb-hr" :class="lineField.additionalStyles" :data-id="'id_for_label_' + lineField._id" v-if="Object.keys($store.getters.getCustomStyles).length">
    <div class="ccb-line" :style="getLine"></div>
</div>