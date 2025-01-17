<section class="content-header">
    <h1>
        <?= wpn_lang('module_title') ?>
        <small><?= wpn_lang('module_description') ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?= wpn_lang('wpn_menu_dashboard') ?></a></li>
        <li><i class="fa fa-shirtsinbulk"></i> <?= wpn_lang('module_title') ?></li>
        <li><?= wpn_lang('module_add') ?></li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?= wpn_lang('module_add') ?></h3>
        </div>
        <div class="box-body">
            <?= form_open_multipart('admin/banners/add', array('role'=>'form')); ?>
            <div class="form-group" >
                <label for="title"><?= wpn_lang('field_title'); ?></label>
                <input type="text" name="title" value="" class="form-control"  />
                <?= form_error('title'); ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" >
                        <label for="href"><?= wpn_lang('field_href'); ?></label>
                        <input type="text" name="href" value="" class="form-control"  />
                        <?= form_error('href'); ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" >
                        <label for="target"><?= wpn_lang('field_target'); ?></label>
                        <?php
                        $options_target = array(
                            '_self'  => wpn_lang('placeholder_target_self'),
                            '_blank'  => wpn_lang('placeholder_target_blank')
                        );
                        ?>
                        <?= form_dropdown('target', $options_target, null, array('class'=>'form-control')); ?>
                        <?= form_error('target'); ?>
                    </div>
                </div>
            </div>
            <div class="row " id="">
                <div class="col-md-2 " id="">
                    <div class="form-group" >
                        <label for="sequence"><?= wpn_lang('field_sequence'); ?></label>
                        <input type="text" name="sequence" value="" class="form-control"  />
                        <?= form_error('sequence'); ?>
                    </div>
                </div>
                <div class="col-md-2 " id="">
                    <div class="form-group" >
                        <label for="position"><?= wpn_lang('field_position'); ?></label>
                        <?php
                        $options = config_item('banner_positions');
                        ?>
                        <?= form_dropdown('position', $options, null, array('class'=>'form-control')); ?>
                        <?= form_error('position'); ?>
                    </div>
                </div>
                <div class="col-md-3 " id="">
                    <?php $this->load->view('widgets/field_avail_status_dropdown'); ?>
                </div>
            </div>
            <div class="form-group" >
                <label for="userfile"><?= wpn_lang('field_content'); ?></label>
                <input type="file" name="userfile" value="" class="form-control"  />
            </div>
            <hr/>
            <div class="row " id="">
                <div class="col-md-12 " id="">
                    <button name="submit" type="submit" class="btn btn-primary" ><?= wpn_lang('wpn_bot_save'); ?></button>
                    <?= anchor('admin/banners', wpn_lang('wpn_bot_cancel'), array('class' => 'btn btn-danger')); ?>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</section>