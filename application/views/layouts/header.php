<!DOCTYPE html>
<html lang="<?php echo get_lang_code() ?>">
<head>
    <meta charset="<?php echo get_charset() ?>">
    <title><?php echo get_site_title() ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php echo load_scripts('head').load_styles() ?>
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('asset/img/favicon/icon-144.png') ?>">
    <link rel="shortcut icon" href="<?php echo base_url('asset/img/favicon/favicon.png') ?>">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body <?php echo get_body_attrs() ?>>
