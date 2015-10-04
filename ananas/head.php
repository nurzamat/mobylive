<!DOCTYPE html>
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
    //old
    <![endif]-->
    <script type="text/javascript">
        $('#search_type').live('change', function(){
            if ($('#search_type').val() == 'LOT and Sequence Number') {
                $('.search_field_labels').css('display', 'block');
                $('#search_field_btn').css('display', 'none');
                $('#search_field_1').css('width', '185px');
            }
            else {
                $('.search_field_labels').css('display', 'none');
                $('#search_field_btn').css('display', 'inline');
                $('#search_field_1').css('width', '150px');
            }
        });

        $('#change_pwd_link').live('click', function() {
            $.fancybox({
                'href'          :   "#change_password_form",
                'transitionIn'	:	'elastic',
                'transitionOut'	:	'elastic',
                'speedIn'		:	600,
                'speedOut'		:	200,
                'autoDimensions':   true,
                'overlayShow'	:	false
            });
        });


        $('#change_password').live('submit', function(){
            $('#old_pwd_error').css('display', 'none');
            $('#new_pwd_error').css('display', 'none');
            $('#repeat_pwd_error').css('display', 'none');


            var old_pwd = $('#old_pwd').val();
            var new_pwd = $('#new_pwd').val();
            var repeat_pwd = $('#repeat_pwd').val();

            if (new_pwd.length < 4) {
                $('#new_pwd_error').css('display', 'block');
                return false;
            }

            if (new_pwd != repeat_pwd) {
                $('#repeat_pwd_error').css('display', 'block');
                return false;
            }

            $('#loader').css("display", "block");

            $.ajax({
                type	: 'POST',
                cache	: false,
                url		: 'ajax/change_pwd.php',
                data	: {
                    old_pwd:$('#old_pwd').val(),
                    new_pwd:$('#new_pwd').val()},

                success: function(res) {
                    $('#loader').css("display", "none");

                    if(res == "true") {
                        $.fancybox({
                            'href'          :   "#password_changed",
                            'transitionIn'	:	'elastic',
                            'transitionOut'	:	'elastic',
                            'speedIn'		:	600,
                            'speedOut'		:	200,
                            'autoDimensions':   true,
                            'overlayShow'	:	false
                        });
                    }
                    else {
                        $('#old_pwd_error').css('display', 'block');
                        return false;
                    }
                }
            });

            return false;
        });

        $('#onSite').live('click', function(){
            window.location = "onSite.php";
        });

        $('#onModeration').live('click', function(){
            window.location = "onModeration.php";
        });

        $('#hotAds').live('click', function(){
            window.location = "hotAds.php";
        });

        $('#rubrica').live('click', function(){
            window.location = "category.php";
        });
        $('#category').live('click', function(){
            window.location = "category.php";
        });
        $('#subcategory').live('click', function(){
            window.location = "subsubcategory.php";
        });

    </script>

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

<div id="maincontainer" class="clearfix">
    <!-- header -->
    <header>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="brand pull-left" href="introPage.php">Arzymo</a>
                    <ul class="nav navbar-nav user_menu pull-right">
                        <li class="hidden-phone hidden-tablet"></li>
                        <li class="divider-vertical hidden-sm hidden-xs"></li>
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=$_SESSION["user"]?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <!--<li><a href="user_profile.html">My Profile</a></li>-->
                                <li><a id="change_pwd_link" href="javascript:void(0)">Change password</a></li>
                                <li class="divider"></li>
                                <li><a href="logout.php?logout=true">Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </header>