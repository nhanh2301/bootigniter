<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Create a new password on <?php echo get_app_config('skpd_name') ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Create a new password</h2>
Forgot your password, huh? No big deal.<br />
To create a new password, just follow this link:<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><?php echo anchor('/auth/reset_password/'.$user_id.'/'.$new_pass_key, 'Create new password', 'style="color: #3366cc;"'); ?>"</b></big><br />
<br />
Link doesn't work? Copy the following link to your browser address bar:<br />
<nobr><?php echo anchor(site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key), site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key), 'style="color: #3366cc;"'); ?><br />
<br />
You received this email, because it was requested by a <a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo get_app_config('skpd_name') ?></a> user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same.<br />
<br />
Thank you,<br />
The <?php echo get_app_config('skpd_name') ?> Team
</div>
</body>
</html>