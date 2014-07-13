<section class="container">
    <div class="row">
        <div id="main-content" class="col-md-12">

            <header id="page-header">
                <h3 class="page-header"><?php echo $heading ?></h3>
            </header> <!-- #page-header -->

            <div class="page-content">
                <p><?php echo implode('</p><p>', ( !is_array($message) ? array($message) : $message )) ?></p>
            </div> <!-- #page-content -->

        </div> <!-- #main-content -->
    </div> <!-- .row -->
</section> <!-- .container-->
