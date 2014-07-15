<div id="main-content" class="<?php echo twbs_set_columns(12, 12, 12, 12) ?>">
    <div class="jumbotron">

        <h1 class="page-title"><?php echo $panel_title ?></h1>
        <p><?php echo implode('</p><p>', ( !is_array($panel_body) ? array($panel_body) : $panel_body )) ?></p>
		<?php echo anchor('login', 'Login', 'class="btn btn-primary btn-lg"') ?>
		<?php echo anchor('register', 'Register', 'class="btn btn-success btn-lg"') ?>

    </div> <!-- .jumbotron -->
</div> <!-- #main-content -->
