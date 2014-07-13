<?php echo load_view('layouts/header') ?>

<div id="top" class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button> <!-- .navbar-toggle -->
            <?php echo anchor(site_url(), config_item('application_name'), 'class="navbar-brand"') ?>
        </div> <!-- .navbar-header -->
        <div class="collapse navbar-collapse">
            <?php echo get_navbar() ?>
        </div><!--/.nav-collapse -->
    </div> <!-- .container -->
</div> <!-- #top -->

<div id="contents">
    <section class="container">

        <?php echo form_alert() ?>
        <div class="row"><?php echo $contents ?></div> <!-- .row -->

    </section> <!-- .container-->
</div> <!-- #contents-->

<footer id="foots">
    <div class="container">
        <p class="text-muted pull-left">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
        <p class="text-muted pull-right"><?php echo config_item('application_name').' Ver. '.config_item('application_version') ?></p>
    </div> <!-- .container-->
</footer> <!-- #foots-->

<?php echo load_view('layouts/footer') ?>
