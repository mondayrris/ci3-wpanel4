<div class="form-group">
    <?php
    $extra = isset($extra) ? $extra : new stdClass();

    // Opções de skin
    $options = array(
        'black'  => 'Black',
        'black-light'  => 'Black-Light',
        'blue'  => 'Blue',
        'blue-light'  => 'Blue-Light',
        'green'  => 'Green',
        'green-light'  => 'Green-Light',
        'purple'  => 'Purple',
        'purple-light'  => 'Purple-Light',
        'red'  => 'Red',
        'red-light'  => 'Red-Light',
        'yellow'  => 'Yellow',
        'yellow-light'  => 'Yellow-Light'
    );
    ?>
    <label for="skin"><?= wpn_lang('field_skin'); ?></label>
    <?= form_dropdown('skin', $options, array($extra->skin), array('class'=>'form-control')); ?>
    <?= form_error('skin'); ?>
</div>