<?php echo load_view('layouts/header') ?>

<header id="top" class="navbar navbar-top navbar-fixed-top" role="banner">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="sidebar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                <span class="sr-only">Toggle Sidebar</span><span class="fa fa-bars"></span>
            </button> <!-- .navbar-toggle -->
            <?php echo anchor(site_url(), config_item('application_name'), 'class="navbar-brand"') ?>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle Navbar</span><span class="fa fa-bars"></span>
            </button> <!-- .navbar-toggle -->
        </div>
        <div class="collapse navbar-collapse" role="navigation">
            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-fw fa-search"></i></button>
                    </span>
                </div>
            </form>
            <ul class="nav navbar-nav navbar-right" role="navigation">
                <li class="active dropdown">
                    <a href="#" data-toggle="dropdown">
                        <i class="menu-icon fa fa-fw fa-plus"></i>
                        <span class="hidden-sm hidden-md">New Item</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="submenu dropdown-menu" role="menu">
                        <li class="active">
                            <a href="#">Navigation menu</a>
                        </li>
                        <li><a href="#">Navigation menu</a></li>
                    </ul>
                </li>
                <li><a href="#">
                    <i class="menu-icon fa fa-fw fa-bell"></i>
                    <span class="visible-xs-inline-block">Notification</span>
                </a></li>
                <li><a href="#">
                    <i class="menu-icon fa fa-fw fa-question-circle"></i>
                    <span class="visible-xs-inline-block">Help</span>
                </a></li>
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown">
                        <i class="menu-icon fa fa-fw fa-user"></i>User menu<span class="caret"></span>
                    </a>
                    <ul class="submenu dropdown-menu" role="menu">
                        <li><a href="#">Account Setting</a></li>
                        <li><a href="#">Administration</a></li>
                        <li class="divider"></li>
                        <li><a href="#">logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div> <!-- .container -->
</header> <!-- #top -->

<div id="sidebar" class="collapse sidebar-collapse width">
    <nav class="side-menu">
        <ul class="nav nav-sidebar" role="navigation">
            <li class="active">
                <a href="#">
                    <i class="menu-icon fa fa-fw fa-file"></i>
                    <span class="menu-text">Navigation menu</span>
                    <button type="button" class="menu-toggle collapsed" data-toggle="collapse" data-target="#submenu1">
                        <i class="fa fa-caret-down"></i>
                    </button>
                </a>
                <ul id="submenu1" class="submenu collapse" role="menu">
                    <li class="active">
                        <a href="#">
                            <i class="menu-icon fa fa-fw fa-file"></i>
                            <span class="menu-text">Navigation menu</span>
                            <button type="button" class="menu-toggle collapsed" data-toggle="collapse" data-target="#subsubmenu1">
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </a>
                        <ul id="subsubmenu1" class="submenu collapse" role="menu">
                            <li class="active"><a href="#">Navigation menu</a></li>
                            <li><a href="#">Navigation menu</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Navigation menu</a></li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <i class="menu-icon fa fa-fw fa-file"></i>
                    <span class="menu-text">Navigation menu</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php echo get_navbar() ?>
</div>

<div id="contents">
    <div class="container-fluid">
        <section id="main-contents">
            <?php echo form_alert() ?>
            <div class="row"><?php echo $contents ?></div> <!-- .row -->
        </section>
        <footer id="foots">
            <p class="text-muted pull-left">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
            <p class="text-muted pull-right"><?php echo config_item('application_name').' Ver. '.config_item('application_version') ?></p>
        </footer>
    </div>
</div>

<?php echo load_view('layouts/footer') ?>
