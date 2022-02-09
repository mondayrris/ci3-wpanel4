<?php
$field_status = isset($field_status) ? $field_status : '0';
?>
<div class="form-group">
    <label for="status"><?= wpn_lang('field_status'); ?></label>
    <?php
    // status options
    $options = array(
        '0' => 'Draft',
        '1' => 'Published'
    );
    echo form_dropdown('status', $options, $field_status, array('class' => 'form-control'));
    ?>
</div>