<?php echo \cBuilder\Classes\CCBTemplate::load('/admin/partials/feedback') ?>
<div class="ccb-panel-header">
    <div class="ccb-panel-header-content">
        <div class="ccb-panel-wrapper">
            <div class="left">
                <a href="<?php echo esc_url( get_admin_url() . 'admin.php?page=cost_calculator_builder' ); ?>" class="left-logo-wrapper">
                    <span>
                        <img src="<?php echo esc_url(CALC_URL . '/frontend/dist/img/icon-100x100.png') ?>">
                    </span>
                    <span> <?php esc_html_e('Cost Calculator', 'cost-calculator-builder') ?></span>
                </a>
                <span class="plugin-version"><?php echo esc_html( sprintf( __( 'v %s', 'cost-calculator-builder' ), CALC_VERSION ) ); ?></span>
                <?php if ( ! get_option( 'ccb_feedback_added', false ) ) { ?>
                    <a href="#" class="ccb-feedback-button">
                        <?php esc_html_e('Feedback', 'cost-calculator-builder') ?>
                        <img src="<?php echo esc_url(CALC_URL . '/frontend/dist/img/feedback.svg') ?>">
                    </a>
                <?php } ?>
            </div>
            <div class="right">
                <div class="button-wrapper">
                    <button :class="{ show: !calc_list }" style="display: none" @click.prevent="openExisting" :class="{'create-type': $store.getters.getModalType === 'existing'}">
                            <span>
                                <i class="fas fa-stream"></i>
                                <?php esc_html_e('My Calculators', 'cost-calculator-builder') ?>
                            </span>
                    </button>
                    <button :class="{'create-type': !loader && calc_list && $store.getters.getExisting?.length === 0, disabled: $store.getters.getCreateNew}" @click.prevent="createId(true)">
                        <span>
                            <i class="fas fa-plus-circle"></i>
                            <?php esc_html_e('Create new', 'cost-calculator-builder') ?>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>