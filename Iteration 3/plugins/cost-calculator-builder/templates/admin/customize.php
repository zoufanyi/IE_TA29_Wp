<div class="ccb-custom-container ccb-sticky-wrapper">
    <div class="container" style="position: sticky; align-self: flex-start; top: 0;">
        <div>
            <div class="ccb-wrapper">
                <div class="custom-content-wrapper">
                    <div class="ccb-page-contents">
                        <?php echo \cBuilder\Classes\CCBTemplate::load('/admin/partials/preview');?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ccb-custom-sidebar" v-if="hasAccess">
        <div class="ccb-accordion ccb-js-accordion">
            <template v-for="(field, key) in available_fields">
                <div class="ccb-accordion__item ccb-js-accordion-item" v-show="!containers.includes(key) || container === key">
                    <div class="ccb-accordion-header ccb-js-accordion-header">{{key}}</div>
                    <div class="ccb-accordion-body ccb-js-accordion-body">
                        <div class="ccb-accordion ccb-js-accordion">
                            <div class="ccb-accordion__item ccb-js-accordion-item"  v-for="(value, index) in field.fields">
                                <div class="ccb-accordion-header ccb-js-accordion-header">{{value.label}}</div>
                                <div class="ccb-accordion-body ccb-js-accordion-body">
                                    <div class="ccb-accordion-body__contents">
                                        <template v-if="Array.isArray(value.data)">
                                            <effects-field
                                                    v-bind:index="index"
                                                    @change="storeStyles"
                                                    v-bind:element="field"
                                                    v-bind:field="value"
                                            ></effects-field>
                                        </template>
                                        <template v-else>
                                            <component v-bind:is="value.type + '-field'"
                                                       v-bind:index="index"
                                                       @change="storeStyles"
                                                       v-bind:element="field"
                                                       v-bind:data="field.fields"
                                                       v-bind:field="value">
                                            </component>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>