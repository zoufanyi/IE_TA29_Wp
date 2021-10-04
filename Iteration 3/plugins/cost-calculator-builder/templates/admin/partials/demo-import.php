<div id="ccb-demo-import">
    <div class="existing-wrapper">
        <div class="existing-list header">
            <div class="list-title"><?php esc_html_e('Demo Import', 'cost-calculator-builder') ?></div>
            <div class="list-title">
                <a @click.prevent="$store.commit('updateIsExisting', true)"><i class="fas fa-arrow-circle-left"></i> &nbsp; <?php esc_html_e('Go back', 'cost-calculator-builder') ?></a>
            </div>
        </div>
        <div class="existing-list content">
            <div v-if="demoImport.finish" class="panel-custom p-t-30 p-b-30 text-center">
                <h2><?php _e("Finish :)", "cost-calculator-builder")?></h2>
            </div>
            <div v-else>
                <div class="p-t-15">
                    <div class="text-center p-t-15 p-b-15" v-if="demoImport.step_progress">
                        <h4> <?php _e("Import step progress", "cost-calculator-builder")?> :
                            <strong>{{ demoImport.step_progress }}</strong>
                        </h4>
                    </div>

                    <div v-if="demoImport.progress_load" class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                             :aria-valuenow="demoImport.progress" aria-valuemin="0" aria-valuemax="100"
                             :style="'width: '+demoImport.progress+'%'">
                            {{ demoImport.progress }}%
                        </div>
                    </div>

                    <hr v-if="demoImport.step_progress || demoImport.progress_load">
                </div>
                <div v-if="demoImport.load" class="text-center" style="position: relative">
                    <loader style="left: 45%"></loader>
                </div>
                <template v-if="!demoImport.load && !demoImport.progress_load">

                    <div class="demo-btn-wrapper">
                        <div class="demo-btn-item">
                            <span class="ccb-demo-import-button default" @click="runImport()">
                                <?php esc_html_e('Run Default Demo Import', 'cost-calculator-builder') ?>
                            </span>
                        </div>
                        <span class="demo-btn-item or"><?php esc_html_e('OR', 'cost-calculator-builder')?></span>

                        <div class="demo-btn-item ccb-file-upload">
                            <input v-model="demoImport.image['file']"
                                   type="file"
                                   id="ccb-file"
                                   hidden="hidden"
                                   accept=".txt"
                                   @change="loadImage()"
                                   ref="image-file" />
                            <div class="ccb-file-container">
                                <span id="ccb-upload" @click="applyImporter"><?php esc_html_e('Choose File', 'cost-calculator-builder');?></span>
                                <span id="ccb-file-text">{{demoImport.noFile}}</span>
                            </div>
                            <span class="ccb-demo-import-button" @click="runCustomImport" :disabled="demoImport.noFile === 'No file chosen'" id="ccb-file-button">
                                <?php esc_html_e('Import From File', 'cost-calculator-builder') ?>
                            </span>
                        </div>
                    </div>

                </template>
                <textarea v-if="demoImport.progress_load"  rows="7" disabled id="progress_data_textarea" class="form-control" v-model="demoImport.progress_data"></textarea>
            </div>
        </div>
    </div>
</div>