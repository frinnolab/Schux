
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-anchor"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("posts_categories/index")?>"> <?=$this->lang->line('menu_posts_categories')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_posts_categories')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    <?php
                        if(form_error('posts_categories'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="posts_categories" class="col-sm-2 control-label">
                            <?=$this->lang->line("posts_categories_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="posts_categories" name="posts_categories" value="<?=set_value('posts_categories', $posts_categories->posts_categories)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('posts_categories'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('posts_description'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="posts_description" class="col-sm-2 control-label">
                            <?=$this->lang->line("posts_categories_description")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" style="resize:none;" id="posts_description" name="posts_description"><?=set_value('posts_description', $posts_categories->posts_description)?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('posts_description'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_posts_categories")?>" >
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

