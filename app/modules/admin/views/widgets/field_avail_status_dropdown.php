<?php $field_status = isset($field_status) ? $field_status : 0 ?>

<div class="form-group">
    <label for="status"><?= wpn_lang('field_status'); ?></label>
    <select name="status" class="form-control">
        <option value="0" <?php if ($field_status == 0) {
            echo 'selected';
        } ?>>Unavailable</option>
        <option value="1" <?php if ($field_status == 1) {
            echo 'selected';
        } ?>>Published</option>
    </select>
</div>