<div class="<?php echo twbs_set_columns( 12, 12, 12, 12 ) ?>">
    <div class="qq-upload-selector">
        <div class="qq-upload-drop-area-selector" qq-hide-dropzone>
            <span><?php echo _x('biupload_drop_area_selector_text') ?></span>
        </div>
        <div class="qq-upload-button-selector btn btn-default">
            <span><?php echo _x('biupload_upload_button_selector_text') ?></span>
        </div>
        <div class="qq-drop-processing-selector qq-hide">
            <span class="qq-drop-processing-spinner-selector"></span>
            <span><?php echo _x('biupload_drop_processing_selector_text') ?></span>
        </div>
        <ul class="qq-upload-list-selector row panel-group" id="accordion">
            <li class="panel panel-default <?php echo twbs_set_columns( 12, 12, 12, 12 ) ?>">
            <div class="panel-heading">
                <span class="qq-upload-spinner-selector"></span>
                <a class="qq-upload-file-selector" data-toggle="collapse" data-parent="#accordion" href="#"></a>
                <span class="qq-edit-filename-icon-selector"></span>
                <input class="qq-edit-filename-selector" tabindex="0" type="text">
                <span class="qq-upload-size-selector"></span>
                <span class="qq-upload-status-text-selector"></span>
                <div class="upload-action-buttons btn-group">
                    <button type="button" class="btn btn-default qq-upload-cancel-selector"><i class="fa fa-ban"></i></button>
                    <button type="button" class="btn btn-default qq-upload-retry-selector"><i class="fa fa-refresh"></i></button>
                    <button type="button" class="btn btn-default qq-upload-delete-selector"><i class="fa fa-trash-o"></i></button>
                </div>
                <div class="qq-progress-bar-container-selector">
                    <div class="qq-progress-bar-selector"></div>
                </div>
            </div>
            <div id="" class="panel-collapse collapse"><div class="panel-body"></div></div>
            </li>
        </ul>
    </div>
</div>
