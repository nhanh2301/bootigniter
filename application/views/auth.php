<div id="main-content" class="<?php echo twbs_set_columns(4, 4, 4, 4) ?>">
    <div class="panel panel-default">

        <div class="panel-heading"><h3 class="panel-title"><?php echo $panel_title ?></h3></div>
        <div class="panel-body"><?php echo $panel_body ?></div>

    </div>
</div> <!-- #main-content -->

<div id="main-content" class="<?php echo twbs_set_columns(8, 8, 8, 8) ?>">
    <div class="jumbotron">

        <p><?php echo implode('</p><p>', ( !is_array($page_body) ? array($page_body) : $page_body )) ?></p>

    </div> <!-- .jumbotron -->
</div> <!-- #main-content -->
