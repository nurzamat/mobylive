<?php
include_once 'header.php';

$error = "";
if (isset($_POST['pr_login']) && isset($_POST['pr_password']))
{
    $user = sanitizeString($_POST['pr_login']);
    $pass = sanitizeString($_POST['pr_password']);

    if ($user == "" || $pass == "")
    {
        $error = "Not+all+fields+entered";
    }
    else
    {
        /* //1-nd variand
        $query = "SELECT * FROM users WHERE user_login='$user' AND user_pass='$pass'";
        $userQ = queryMysql($query);

        if (mysql_num_rows($userQ) == 0)
        {
            $error = "Wrong+username+or+password";
        }
        else
        {
            $_SESSION['user'] = $user;
            $_SESSION['nicename'] = mysql_fetch_object($userQ)->user_nicename;
            $_SESSION['city'] = "Бишкек";
        }
       //end of 1-nd variand */

        //2-nd variand
        if($user == 'admin' && $pass == 'akniet12')
        {
            $_SESSION['user'] = $user;
        }
        else $error = "Wrong+username+or+password";
        //end of 2-nd variand
    }

    if($error != "")
    {
        header("Location: index.php?reason=".$error);
        exit();
    }
} elseif($loggedin == FALSE)
{
header("Location: index.php");
exit();
}

include('head2.php');
?>

    <div id="contentwrapper">
        <div class="main_content">
            <div class="row">

            </div>
        </div>
    </div>

<? include ("footer2.php"); ?>