<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('asset/lib/bootstrap/dist/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('asset/css/style.css') ?>">
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Project name</a>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#">Home</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</div>

<!-- Begin page content -->
<div class="container">
	<div class="page-header">
		<h1>Welcome to CodeIgniter!r</h1>
	</div>
	<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

	<p>If you would like to edit this page you'll find it located at:</p>
	<pre>application/views/welcome_message.php</pre>

	<p>The corresponding controller for this page is found at:</p>
	<pre>application/controllers/welcome.php</pre>

	<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
</div>

<footer>
	<div class="container">
		<p>Page rendered in <strong>{elapsed_time}</strong> seconds</p>
	</div>
</footer>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo base_url('asset/lib/bootstrap/dist/js/bootstrap.min.js') ?>"></script>

</body>
</html>
