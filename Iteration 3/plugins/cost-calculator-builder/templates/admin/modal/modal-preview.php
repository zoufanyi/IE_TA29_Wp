<div class="modal-header preview">
    <div class="modal-header__title">
        <i class="fas fa-eye"></i>
        <h4><?php esc_html_e('Preview', 'cost-calculator-builder')?></h4>
    </div>
</div>
<loader  v-if="loader"></loader>

<?php echo \cBuilder\Classes\CCBTemplate::load('/admin/partials/preview');?>
