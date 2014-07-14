<div id="main-content" class="<?php echo twbs_set_columns(12, 12, 12, 12) ?>">
    <div class="jumbotron">

        <h3>te<?php echo $panel_title ?></h3>
        <p><?php echo implode('</p><p>', ( !is_array($panel_body) ? array($panel_body) : $panel_body )) ?></p>

    </div> <!-- .jumbotron -->
</div> <!-- #main-content -->
