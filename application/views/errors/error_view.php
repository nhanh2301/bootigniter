<div id="main-content" class="<?php echo twbs_set_columns(12, 12, 12, 12) ?>">
    <div class="page">

        <div class="page-content">
            <p><?php echo implode('</p><p>', ( !is_array($message) ? array($message) : $message )) ?></p>
        </div> <!-- #page-content -->

    </div> <!-- .page -->
</div> <!-- #main-content -->
