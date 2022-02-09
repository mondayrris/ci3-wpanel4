<?php /** @noinspection PhpUndefinedVariableInspection */
/** @noinspection PhpUndefinedVariableInspection */
echo $this->wpanel->load_editor();
?>

<section class="content-header">
    <h1>
        <?= wpn_lang('module_title'); ?>
        <small><?= wpn_lang('module_description'); ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?= wpn_lang('wpn_menu_dashboard'); ?></a></li>
        <li><a href="<?= site_url('admin/pages'); ?>"><i class="fa fa-files-o"></i> <?= wpn_lang('module_title'); ?></a></li>
        <li><?= wpn_lang('module_edit'); ?></li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?= wpn_lang('module_edit'); ?></h3>
        </div>
        <div class="box-body">
            <?= form_open_multipart('admin/pages/edit/'.$id, array('role'=>'form')); ?>
                <div class="form-group" >
                    <label for="title"><?= wpn_lang('field_title'); ?></label>
                    <input type="text" name="title" value="<?= $row->title; ?>" class="form-control"  />
                    <?= form_error('title'); ?>
                </div>
                <div class="form-group" >
                    <label for="description"><?= wpn_lang('field_description'); ?></label>
                    <textarea name="description" cols="40" rows="3" class="form-control" ><?= $row->description; ?></textarea>
                </div>
                <div class="form-group" >
                    <label for="content"><?= wpn_lang('field_content'); ?></label>
                    <textarea name="content" cols="40" rows="10" class="form-control ckeditor" id="editor" ><?= $row->content; ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group" >
                            <label for="userfile"><?= wpn_lang('field_folder'); ?></label>
                            <input type="file" name="userfile" value="" class="form-control" />
                            <?php
                            if(file_exists('./media/capas/'.$row->image)){
                                echo img(array('src'=>'media/capas/'.$row->image, 'class'=>'img-responsive img-thumbnail', 'style'=>'margin-top:5px;'));
                            } else {
                                echo '<p>'.wpn_lang('no_folder_image').'</p>';
                            }
                            ?>
                            <div class="checkbox" >
                                <label>
                                    <input type="checkbox" name="alterar_imagem" value="1" class="checkbox"  />
                                    <?= wpn_lang('change_folder'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" >
                            <label for="tags"><?= wpn_lang('field_tags'); ?></label>
                            <textarea name="tags" cols="40" rows="5" class="form-control" ><?= $row->tags; ?></textarea>
                            <?= wpn_lang('use_comma'); ?>
                        </div>
                    </div>
                    <div class="col-md-3 " id="">
                        <div class="form-group" >
                            <label for="status"><?= wpn_lang('field_status'); ?></label>
                            <?php
                            $options = array(
                                '0'  => 'Rascunho',
                                '1'  => 'Publicado'
                            );
                            echo form_dropdown('status', $options, array($row->status), array('class'=>'form-control'));
                            ?>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row ">
                    <div class="col-md-12 ">
                        <button name="submit" type="submit" class="btn btn-primary" ><?= wpn_lang('wpn_bot_save'); ?></button>
                        <?= anchor('admin/pages', wpn_lang('wpn_bot_cancel'), array('class'=>'btn btn-danger')); ?>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</section>