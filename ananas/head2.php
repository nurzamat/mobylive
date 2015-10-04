<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gebo Admin v3.1</title>

    <!-- Bootstrap framework -->
    <link rel="stylesheet" href="gebo3/bootstrap/css/bootstrap.min.css" />
    <!-- jQuery UI theme -->
    <link rel="stylesheet" href="gebo3/lib/jquery-ui/css/Aristo/Aristo.css" />
    <!-- breadcrumbs -->
    <link rel="stylesheet" href="gebo3/lib/jBreadcrumbs/css/BreadCrumb.css" />
    <!-- tooltips-->
    <link rel="stylesheet" href="gebo3/lib/qtip2/jquery.qtip.min.css" />
    <!-- colorbox -->
    <link rel="stylesheet" href="gebo3/lib/colorbox/colorbox.css" />
    <!-- code prettify -->
    <link rel="stylesheet" href="gebo3/lib/google-code-prettify/prettify.css" />
    <!-- sticky notifications -->
    <link rel="stylesheet" href="gebo3/lib/sticky/sticky.css" />
    <!-- aditional icons -->
    <link rel="stylesheet" href="gebo3/img/splashy/splashy.css" />
    <!-- flags -->
    <link rel="stylesheet" href="gebo3/img/flags/flags.css" />
    <!-- datatables -->
    <link rel="stylesheet" href="gebo3/lib/datatables/extras/TableTools/media/css/TableTools.css">

    <!-- font-awesome -->
    <link rel="stylesheet" href="gebo3/img/font-awesome/css/font-awesome.min.css" />
    <!-- calendar -->
    <link rel="stylesheet" href="gebo3/lib/fullcalendar/fullcalendar_gebo.css" />

    <!-- main styles -->
    <link rel="stylesheet" href="gebo3/css/style.css" />
    <!-- theme color-->
    <link rel="stylesheet" href="gebo3/css/blue.css" id="link_theme" />

    <!--<link href='http://fonts.useso.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>-->

    <!-- favicon -->
    <link rel="shortcut icon" href="gebo3/favicon.ico" />

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="gebo3/css/ie.css" />
    <![endif]-->

    <!--[if lt IE 9]>
    <script src="gebo3/js/ie/html5.js"></script>
    <script src="gebo3/js/ie/respond.min.js"></script>
    <script src="gebo3/lib/flot/excanvas.min.js"></script>
    <![endif]-->

    <style>
        #not_found_text {
            font-family: 'PT Sans', sans-serif;
            font-size: 16px;
            color:#333;
            text-align: center;
            width: 100%;
            padding-top: 150px;
        }

        #loader {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 1000;
            background-color: #000;
            opacity: 0.5;
            filter:alpha(opacity=50); /* IE's opacity*/
            display: none;
        }

        .file_uploader {
            text-align: center;
            overflow: hidden;
            width: 100px;
            height: 17px;
        }

        .file_uploader input {
            margin-top: -50px;
            margin-left:-410px;
            -moz-opacity: 0;
            filter: alpha(opacity=0);
            opacity: 0;
            font-size: 150px;
            height: 100px;
        }

        #change_password_container, .search_field_labels {
            display: none;
        }

        #old_pwd_error, #new_pwd_error, #repeat_pwd_error {
            display: none;
            color: red;
        }

        .form_actions_remove_btmmargin {
            margin-bottom: 0;
        }

        .heading_space {
            height: 20px;
        }

        .form-horizontal .control-group {
            margin-bottom: 25px;
        }

        .formSep {
            padding-bottom: 25px;
        }

        .accordion-inner ul li {
            padding-bottom: 3px;
            padding-top: 3px;
        }
    </style>
</head>

<body class="full_width">
<div id="loader"><div style="position:fixed;top:50%;left:50%;margin:-32px 0 0 -5px;"><img width="64px" height="10px" src="gebo3/img/ajax_loader.gif" alt="loading"></div></div>

<div id="change_password_container">
    <div id="password_changed">
        Password is changed
    </div>

    <div id="change_password_form">
        <form class="form-horizontal form_actions_remove_btmmargin" id="change_password">
            <p class="f_legend">Change password</p>

            <div class="control-group formSep">
                <label class="control-label">Current Password</label>
                <div class="controls">
                    <input type="password" id="old_pwd">
                    <span class="help-block" id="old_pwd_error">password is not valid</span>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">New Password:</label>
                <div class="controls">
                    <input type="password" id="new_pwd">
                    <span class="help-block">Enter new password</span>
                    <span class="help-block" id="new_pwd_error">password must be 4 char minimum</span>
                </div>
            </div>
            <div class="control-group formSep">
                <div class="controls">
                    <input type="password" id="repeat_pwd">
                    <span class="help-block">Repeat password</span>
                    <span class="help-block" id="repeat_pwd_error">password doesn't match</span>
                </div>
            </div>
            <div class="form-actions form_actions_remove_btmmargin">
                <button class="btn btn-inverse" type="submit" id="change_password_btn">Save changes</button>
                <button class="btn" onclick="parent.jQuery.fancybox.close();">Cancel</button>
            </div>
        </form>
    </div>
</div>
<div class="style_switcher">
    <div class="sepH_c">
        <p>Colors:</p>
        <div class="clearfix">
            <a href="javascript:void(0)" class="style_item jQclr blue_theme style_active" title="blue">blue</a>
            <a href="javascript:void(0)" class="style_item jQclr dark_theme" title="dark">dark</a>
            <a href="javascript:void(0)" class="style_item jQclr green_theme" title="green">green</a>
            <a href="javascript:void(0)" class="style_item jQclr brown_theme" title="brown">brown</a>
            <a href="javascript:void(0)" class="style_item jQclr eastern_blue_theme" title="eastern_blue">eastern blue</a>
            <a href="javascript:void(0)" class="style_item jQclr tamarillo_theme" title="tamarillo">tamarillo</a>
        </div>
    </div>
    <div class="sepH_c">
        <p>Backgrounds:</p>
        <div class="clearfix">
            <span class="style_item jQptrn style_active ptrn_def" title=""></span>
            <span class="ssw_ptrn_a style_item jQptrn" title="ptrn_a"></span>
            <span class="ssw_ptrn_b style_item jQptrn" title="ptrn_b"></span>
            <span class="ssw_ptrn_c style_item jQptrn" title="ptrn_c"></span>
            <span class="ssw_ptrn_d style_item jQptrn" title="ptrn_d"></span>
            <span class="ssw_ptrn_e style_item jQptrn" title="ptrn_e"></span>
        </div>
    </div>
    <div class="sepH_c">
        <p>Layout:</p>
        <div class="clearfix">
            <label class="radio-inline"><input name="ssw_layout" id="ssw_layout_fluid" value="" checked="" type="radio"> Fluid</label>
            <label class="radio-inline"><input name="ssw_layout" id="ssw_layout_fixed" value="gebo-fixed" type="radio"> Fixed</label>
        </div>
    </div>
    <div class="sepH_c">
        <p>Sidebar position:</p>
        <div class="clearfix">
            <label class="radio-inline"><input name="ssw_sidebar" id="ssw_sidebar_left" value="" checked="" type="radio"> Left</label>
            <label class="radio-inline"><input name="ssw_sidebar" id="ssw_sidebar_right" value="sidebar_right" type="radio"> Right</label>
        </div>
    </div>
    <div class="sepH_c">
        <p>Show top menu on:</p>
        <div class="clearfix">
            <label class="radio-inline"><input name="ssw_menu" id="ssw_menu_click" value="" checked="" type="radio"> Click</label>
            <label class="radio-inline"><input name="ssw_menu" id="ssw_menu_hover" value="menu_hover" type="radio"> Hover</label>
        </div>
    </div>

    <div class="gh_button-group">
        <a href="#" id="showCss" class="btn btn-primary btn-sm">Show CSS</a>
        <a href="#" id="resetDefault" class="btn btn-default btn-sm">Reset</a>
    </div>
    <div class="hide">
        <ul id="ssw_styles">
            <li class="small ssw_mbColor sepH_a" style="display:none">body {<span class="ssw_mColor sepH_a" style="display:none"> color: #<span></span>;</span> <span class="ssw_bColor" style="display:none">background-color: #<span></span> </span>}</li>
            <li class="small ssw_lColor sepH_a" style="display:none">a { color: #<span></span> }</li>
        </ul>
    </div>
</div>
<div id="maincontainer" class="clearfix">
    <header>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="brand pull-left" href="introPage.php">Arzymo Admin</a>
                    <ul class="nav navbar-nav" id="mobile-nav">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-list-alt"></span> Forms <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="form_elements.html">Form elements</a></li>
                                <li><a href="form_extended.html">Extended form elements</a></li>
                                <li><a href="form_validation.html">Form Validation</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-th"></span> Components <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="alerts_btns.html">Alerts &amp; Buttons</a></li>
                                <li><a href="icons.html">Icons</a></li>
                                <li><a href="notifications.html">Notifications</a></li>
                                <li><a href="tables.html">Tables</a></li>
                                <li><a href="tables_more.html">Tables (more examples)</a></li>
                                <li><a href="tabs_accordion.html">Tabs &amp; Accordion</a></li>
                                <li><a href="tooltips.html">Tooltips, Popovers</a></li>
                                <li><a href="typography.html">Typography</a></li>
                                <li><a href="widgets.html">Widget boxes</a></li>
                                <li class="dropdown">
                                    <a href="#">Sub menu <b class="caret-right"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Sub menu 1.1</a></li>
                                        <li><a href="#">Sub menu 1.2</a></li>
                                        <li><a href="#">Sub menu 1.3</a></li>
                                        <li>
                                            <a href="#">Sub menu 1.4 <b class="caret-right"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Sub menu 1.4.1</a></li>
                                                <li><a href="#">Sub menu 1.4.2</a></li>
                                                <li><a href="#">Sub menu 1.4.3</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-wrench"></span> Plugins <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="charts.html">Charts</a></li>
                                <li><a href="calendar.html">Calendar</a></li>
                                <li><a href="datatable.html">Datatable</a></li>
                                <li><a href="dynamic_tree.html">Dynamic tree</a></li>
                                <li><a href="editable_elements.html">Editable elements</a></li>
                                <li><a href="file_manager.html">File Manager</a></li>
                                <li><a href="floating_header.html">Floating List Header</a></li>
                                <li><a href="google_maps.html">Google Maps</a></li>
                                <li><a href="gallery.html">Gallery Grid</a></li>
                                <li><a href="wizard.html">Wizard</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-file"></span> Pages <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="blank.html"> Blank</a></li>
                                <li><a href="blog_page.html"> Blog Page</a></li>
                                <li><a href="chat.html"> Chat</a></li>
                                <li><a href="error_5F404.html"> Error 404</a></li>
                                <li><a href="invoice.html"> Invoice</a></li>
                                <li><a href="mailbox.html">Mailbox</a></li>
                                <li><a href="search_page.html">Search page</a></li>
                                <li><a href="user_profile.html">User profile</a></li>
                                <li><a href="user_static.html">User profile (static)</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav user_menu pull-right">
                        <li class="hidden-phone hidden-tablet">
                            <div class="nb_boxes clearfix">
                                <a data-toggle="modal" data-backdrop="static" href="#myMail" data-placement="bottom" data-container="body" class="label bs_ttip" title="New messages">25 <i class="splashy-mail_light"></i></a>
                                <a data-toggle="modal" data-backdrop="static" href="#myTasks" data-placement="bottom" data-container="body" class="label bs_ttip" title="New tasks">10 <i class="splashy-calendar_week"></i></a>
                            </div>
                        </li>
                        <li class="divider-vertical hidden-sm hidden-xs"></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle nav_condensed" data-toggle="dropdown"><i class="flag-gb"></i> <b class="caret"></b></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="javascript:void(0)"><i class="flag-de"></i> Deutsch</a></li>
                                <li><a href="javascript:void(0)"><i class="flag-fr"></i> Français</a></li>
                                <li><a href="javascript:void(0)"><i class="flag-es"></i> Español</a></li>
                                <li><a href="javascript:void(0)"><i class="flag-ru"></i> Pусский</a></li>
                            </ul>
                        </li>
                        <li class="divider-vertical hidden-sm hidden-xs"></li>
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><img src="gebo3/img/user_avatar.png" alt="" class="user_avatar"><?=$_SESSION["user"]?> <b class="caret"></b></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="user_profile.html">My Profile</a></li>
                                <li><a id="change_pwd_link" href="javascript:void(0)">Change password</a></li>
                                <li class="divider"></li>
                                <li><a href="logout.php?logout=true">Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="modal fade" id="myMail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title">New Messages</h3>
                    </div>
                    <div class="modal-body">
                        <table class="table table-condensed table-striped" data-provides="rowlink">
                            <thead>
                            <tr>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Size</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Declan Pamphlett</td>
                                <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                                <td>23/05/2015</td>
                                <td>25KB</td>
                            </tr>
                            <tr>
                                <td>Erin Church</td>
                                <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                                <td>24/05/2015</td>
                                <td>15KB</td>
                            </tr>
                            <tr>
                                <td>Koby Auld</td>
                                <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                                <td>25/05/2015</td>
                                <td>28KB</td>
                            </tr>
                            <tr>
                                <td>Anthony Pound</td>
                                <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                                <td>25/05/2015</td>
                                <td>33KB</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default">Go to mailbox</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myTasks">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title">New Tasks</h3>
                    </div>
                    <div class="modal-body">
                        <table class="table table-condensed table-striped" data-provides="rowlink">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>Summary</th>
                                <th>Updated</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>P-23</td>
                                <td><a href="javascript:void(0)">Admin should not break if URL…</a></td>
                                <td>23/05/2015</td>
                                <td><span class="label label-danger">High</span></td>
                                <td>Open</td>
                            </tr>
                            <tr>
                                <td>P-18</td>
                                <td><a href="javascript:void(0)">Displaying submenus in custom…</a></td>
                                <td>22/05/2015</td>
                                <td><span class="label label-warning">Medium</span></td>
                                <td>Reopen</td>
                            </tr>
                            <tr>
                                <td>P-25</td>
                                <td><a href="javascript:void(0)">Featured image on post types…</a></td>
                                <td>22/05/2015</td>
                                <td><span class="label label-success">Low</span></td>
                                <td>Updated</td>
                            </tr>
                            <tr>
                                <td>P-10</td>
                                <td><a href="javascript:void(0)">Multiple feed fixes and…</a></td>
                                <td>17/05/2015</td>
                                <td><span class="label label-warning">Medium</span></td>
                                <td>Open</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default">Go to task manager</button>
                    </div>
                </div>
            </div>
        </div>

    </header>