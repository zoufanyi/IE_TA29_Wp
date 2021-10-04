 <div class="ccb-page-content calculator">
        <div class="content-wrapper">

            <div class="content-item left open">
                <div class="item-row input">
                    <i class="fas fa-edit"></i>
                    <i class="fas fa-plus-circle after" @click="allowAccess" v-if="!access"></i>
                    <i class="after"  @click="saveTitle" v-if="access && !$store.getters.getDisableInput"><?php esc_html_e("Apply", "cost-calculator-builder"); ?></i class="fas fa-check-circle after" >
                    <label for="create-input" class="ccb-create-input" :class="{disabled: $store.getters.getDisableInput}" @click="enableInput">
                        <input :disabled="$store.getters.getDisableInput" type="text" ref="title" id="create-input" v-model.trim="getTitle" placeholder="&nbsp;">
                        <span class="ccb-label-1"><?php esc_html_e("Name", "cost-calculator-builder"); ?></span>
                        <span class="border"></span>
                    </label>
                </div>
            </div>

            <div class="content-item right" :class="{open: access, disabled: access && !$store.getters.getDisableInput}">
                <div class="fields-header">
                    <h4><?php esc_html_e('Elements', 'cost-calculator-builder') ?></h4>
                    <p><?php esc_html_e('Drag & Drop or click for adding', 'cost-calculator-builder') ?></p>
                </div>
            </div>
        </div>

        <div class="content-wrapper" >
            <div class="content-item left" :class="{open: access, disabled: access && !$store.getters.getDisableInput}">
                <div class="item-row">
                    <div class="calc-block">
                        <draggable
                            @change="log"
                            group="fields"
                            :list="$store.getters.getBuilder"
                            v-model="getFields"
                            draggable=".item"
                            :key="$store.getters.getCount"
                            v-bind="dragOptions"
                        >
                            <div class="list-group-item flip-list-enter-to item" v-for="(field, key) in getFields" :key="key">
                                <div class="tools-field">
                                    <div>
                                        <i class="left-icon fas " :class="field.icon"></i>
                                        <span class="field-title">{{field.label}}</span>
                                        <span class="field-alias" v-if="field.alias">[{{field.alias}}]</span>
                                        <span class="field-type">- {{field.type}}</span>
                                    </div>

                                    <div class="extra-link">
                                        <span class="edit-field" @click.prevent="editField(field.type, key)">
                                            <span>
                                                <i class="fas fa-pencil-alt"></i>
                                                <?php esc_html_e('edit', 'cost-calculator-builder') ?>
                                            </span>
                                        </span>

                                        <i  class="fas fa-trash-alt delete"
                                            @click.prevent="removeFromBuilder(field._id)"
                                            title="<?php esc_attr_e('Remove Field', 'cost-calculator-builder') ?>">
                                        </i>
                                    </div>
                                </div>
                            </div>

                            <div
                                   class="list-group-item empty"
                                   :class="{item: $store.getters.getBuilder.length === 0}"
                            >
                                <span><?php esc_html_e('Place element here', 'cost-calculator-builder') ?></span>
                            </div>

                        </draggable>
                    </div>
                </div>
            </div>
            <div class="content-item right" :class="{open: access, disabled: access && !$store.getters.getDisableInput}">
                <div class="item-row">
                    <div class="fields-wrapper">

                        <draggable
                                :sort="false"
                                class="calc-field-row"
                                @change="log"
                                :list="$store.getters.getFields"
                                :group="{ name: 'fields', pull: 'clone', put: false }"
                        >

                            <div class="calc-field"  v-for="( field, index ) in $store.getters.getFields" >
                              <div class="calc-field__container">
                                  <i class="calc-field__icon" :class="field.icon"></i>
                                  <div class="calc-field__content">
                                      <h6 class="calc-field__title">{{field.name}}</h6>
                                      <p class="calc-field__desc">{{field.description}}</p>
                                  </div>
                                  <i class="fas fa-plus calc-field__add"
                                     @click="addField(field.type)"
                                     title="<?php esc_attr_e('Click to add', 'cost-calculator-builder') ?>">
                                  </i>
                              </div>
                            </div>

                        </draggable>

                    </div>
                </div>
            </div>
        </div>
    </div>