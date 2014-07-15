<div id="main-content" class="<?php echo twbs_set_columns(12, 12, 12, 12) ?>">
    <div class="row">
        <div id="page-contents" class="<?php echo twbs_set_columns(8, 8, 8, 8) ?>">
            <div class="row">
                <div class="<?php echo twbs_set_columns(12, 12, 12, 12) ?>">

                    <h1 class="page-title"><?php echo $panel_title ?></h1>
                    <div class="page-body">
                        <p><?php echo implode('</p><p>', ( !is_array($panel_body) ? array($panel_body) : $panel_body )) ?></p>
                    </div>

                </div>
            </div>
        </div>
        <div id="page-sidebars" class="<?php echo twbs_set_columns(4, 4, 4, 4) ?>">
            <div class="panel panel-default">

                <div class="panel-heading"><h3 class="panel-title"><?php echo $panel_title ?></h3></div>
                <div class="panel-body"></div>

            </div>
        </div>
    </div> <!-- .row -->
</div> <!-- #main-content -->
