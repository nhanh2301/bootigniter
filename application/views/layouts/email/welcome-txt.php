Welcome to <?php echo get_app_config('skpd_name') ?>,

Thanks for joining <?php echo get_app_config('skpd_name') ?>. We listed your sign in details below. Make sure you keep them safe.
Follow this link to login on the site:

<?php echo site_url('/login/'); ?>

<?php if (strlen($username) > 0) { ?>
Your username: <?php echo $username; ?>
<?php } ?>

Your email address: <?php echo $email; ?>

<?php /* Your password: <?php echo $password; ?>

*/ ?>

Have fun!
The <?php echo get_app_config('skpd_name') ?> Team