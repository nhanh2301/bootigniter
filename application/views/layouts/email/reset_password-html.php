<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Your new password on <?php echo get_app_config('skpd_name') ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Your new password on <?php echo get_app_config('skpd_name') ?></h2>
You have changed your password.<br />
Please, keep it in your records so you don't forget it.<br />
<br />
<?php if (strlen($username) > 0) { ?>Your username: <?php echo $username; ?><br /><?php } ?>
Your email address: <?php echo $email; ?><br />
<?php /* Your new password: <?php echo $new_password; ?><br /> */ ?>
<br />
<br />
Thank you,<br />
The <?php echo get_app_config('app_name') ?> Team
</div>
</body>
</html>