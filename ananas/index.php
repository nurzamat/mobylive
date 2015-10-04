<?
include_once 'header.php';

if($loggedin)
    header("Location: introPage.php");

?>

<!DOCTYPE html>
<html lang="en" class="login_page">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Arzymo</title>

    <!-- Bootstrap framework -->
    <link rel="stylesheet" href="gebo3/bootstrap/css/bootstrap.min.css" />
    <!-- theme color-->
    <link rel="stylesheet" href="gebo3/css/blue.css" />
    <!-- tooltip -->
    <link rel="stylesheet" href="gebo3/lib/qtip2/jquery.qtip.min.css" />
    <!-- main styles -->
    <link rel="stylesheet" href="gebo3/css/style.css" />

    <!-- favicon -->
    <!--<link rel="shortcut icon" href="gebo3/favicon.ico" />-->
    <!--<link href='../fonts.googleapis.com/css@family=PT+Sans' rel='stylesheet' type='text/css'>-->

    <!--[if lt IE 9]>
    <script src="gebo3/js/ie/html5.js"></script>
    <script src="gebo3/js/ie/respond.min.js"></script>
    <![endif]-->

</head>
<body>

<div class="login_box">
    <form action="introPage.php" method="post" id="login_form">
        <div class="top_b">AUTHENTICATION</div>
        <? if(isset($_GET['reason'])) echo "<div class='alert alert-info alert-login'>".$_GET['reason']."</div>"; ?>
        <div class="cnt_b">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon input-sm"><i class="glyphicon glyphicon-user"></i></span>
                    <input class="form-control input-sm" type="text" id="username" name="pr_login" placeholder="Username" />
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon input-sm"><i class="glyphicon glyphicon-lock"></i></span>
                    <input class="form-control input-sm" type="password" id="password" name="pr_password" placeholder="Password" />
                </div>
            </div>
        </div>
        <div class="btm_b clearfix">
            <button class="btn btn-default btn-sm pull-right" type="submit">Sign In</button>
        </div>
    </form>
</div>

<script src="gebo3/js/jquery.min.js"></script>
<script src="gebo3/js/jquery.actual.min.js"></script>
<script src="gebo3/lib/validation/jquery.validate.js"></script>
<script src="gebo3/bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        //* boxes animation
        form_wrapper = $('.login_box');
        function boxHeight() {
            form_wrapper.animate({ marginTop : ( - ( form_wrapper.height() / 2) - 24) },400);
        };
        form_wrapper.css({ marginTop : ( - ( form_wrapper.height() / 2) - 24) });
        $('.linkform a,.link_reg a').on('click',function(e){
            var target	= $(this).attr('href'),
                    target_height = $(target).actual('height');
            $(form_wrapper).css({
                'height'		: form_wrapper.height()
            });
            $(form_wrapper.find('form:visible')).fadeOut(400,function(){
                form_wrapper.stop().animate({
                    height	 : target_height,
                    marginTop: ( - (target_height/2) - 24)
                },500,function(){
                    $(target).fadeIn(400);
                    $('.links_btm .linkform').toggle();
                    $(form_wrapper).css({
                        'height'		: ''
                    });
                });
            });
            e.preventDefault();
        });

        //* validation
        $('#login_form').validate({
            onkeyup: false,
            errorClass: 'error',
            validClass: 'valid',
            rules: {
                pr_login: { required: true, minlength: 3 },
                pr_password: { required: true, minlength: 3 }
            },
            highlight: function(element) {
                $(element).closest('.form-group').addClass("f_error");
                setTimeout(function() {
                    boxHeight()
                }, 200)
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass("f_error");
                setTimeout(function() {
                    boxHeight()
                }, 200)
            },
            errorPlacement: function(error, element) {
                $(element).closest('.form-group').append(error);
            }
        });
    });
</script>
</body>
</html>
