<?php // rnfunctions.php


$dbhost  = 'localhost';    // Unlikely to require changing
$dbname  = 'mobylive'; // Modify these...
$dbuser  = 'root';     // ...variables according
$dbpass  = '';     // ...to your installation
$appname = "Arzymo"; // ...and preference

/*
$dbhost  = '176.126.165.135';    // Unlikely to require changing
$dbname  = 'user14011_aijan'; // Modify these...
$dbuser  = 'user14011_aijan';     // ...variables according
$dbpass  = 'mebelgidaijan14';     // ...to your installation
$appname = "MebelGid"; // ...and preference
*/
mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());

mysql_query('SET NAMES utf8');
mysql_query('SET CHARACTER SET utf8' );
mysql_query('SET COLLATION_CONNECTION="utf8_general_ci"' );


function createTable($name, $query)
{
    if (tableExists($name))
    {
        echo "Table '$name' already exists<br />";
    }
    else
    {
        queryMysql("CREATE TABLE $name($query)");
        echo "Table '$name' created<br />";
    }
}

function tableExists($name)
{
    $result = queryMysql("SHOW TABLES LIKE '$name'");
    return mysql_num_rows($result);
}

function queryMysql($query)
{
    $result = mysql_query($query) or die(mysql_error());
    return $result;
}

function destroySession()
{
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
}

function sanitizeString($var)
{
    $var = strip_tags($var);
    //$var = htmlentities($var);
    $var = htmlentities($var, ENT_QUOTES, "UTF-8");
    $var = stripslashes($var);
    return mysql_real_escape_string($var);
}

function showProfile($user)
{
    if (file_exists("$user.jpg"))
        echo "<img src='$user.jpg' border='1' align='left' />";

    $result = queryMysql("SELECT * FROM rnprofiles WHERE user='$user'");

    if (mysql_num_rows($result))
    {
        $row = mysql_fetch_row($result);
        echo stripslashes($row[1]) . "<br clear=left /><br />";
    }
}

function thumbnail_proportion($original_file_path, $max, $save_path="")
{
    $imgInfo = getimagesize($original_file_path);
    $imgExtension = "";

    switch ($imgInfo[2])
    {
        case 1:
            $imgExtension = '.gif';
            break;

        case 2:
            $imgExtension = '.jpg';
            break;

        case 3:
            $imgExtension = '.png';
            break;
    }

    if ($save_path=="") $save_path = "thumbnail".$imgExtension ;

    // Get new dimensions
    list($w, $h) = getimagesize($original_file_path);

    $tw  = $w;
    $th  = $h;

    if ($w > $h && $max < $w)
    {
        $th = $max / $w * $h;
        $tw = $max;
    }
    elseif ($h > $w && $max < $h)
    {
        $tw = $max / $h * $w;
        $th = $max;
    }
    elseif ($max < $w)
    {
        $tw = $th = $max;
    }
    // Resample
    $imageResample = imagecreatetruecolor($tw, $th);

    if ( $imgExtension == ".jpg" )
    {
        $image = imagecreatefromjpeg($original_file_path);
    }
    else if ( $imgExtension == ".gif" )
    {
        $image = imagecreatefromgif($original_file_path);
    }
    else if ( $imgExtension == ".png" )
    {
        $image = imagecreatefrompng($original_file_path);
    }

    imagecopyresampled($imageResample, $image, 0, 0, 0, 0, $tw, $th, $w, $h);

    imageconvolution($imageResample, array( // Sharpen image
        array(-1, -1, -1),
        array(-1, 16, -1),
        array(-1, -1, -1)
    ), 8, 0);
/*
    if ( $imgExtension == ".jpg" )
        imagejpeg($imageResample, $save_path.$imgExtension);
    else if ( $imgExtension == ".gif" )
        imagegif($imageResample, $save_path.$imgExtension);
    else if ( $imgExtension == ".png" )
        imagepng($imageResample, $save_path.$imgExtension);
*/
    //my jpg format
    $imgExtension = ".jpg";
    imagejpeg($imageResample, $save_path.$imgExtension, 100);
    //end my

    imagedestroy($imageResample);
    imagedestroy($image);
}

function thumbnail_image($original_file_path, $new_width, $new_height, $save_path="")
{
    $imgInfo = getimagesize($original_file_path);
    $imgExtension = "";

    switch ($imgInfo[2])
    {
        case 1:
            $imgExtension = '.gif';
            break;

        case 2:
            $imgExtension = '.jpg';
            break;

        case 3:
            $imgExtension = '.png';
            break;
    }

    if ($save_path=="") $save_path = "thumbnail".$imgExtension ;

    // Get new dimensions
    list($width, $height) = getimagesize($original_file_path);
    // Resample
    $imageResample = imagecreatetruecolor($new_width, $new_height);

    if ( $imgExtension == ".jpg" )
    {
        $image = imagecreatefromjpeg($original_file_path);
    }
    else if ( $imgExtension == ".gif" )
    {
        $image = imagecreatefromgif($original_file_path);
    }
    else if ( $imgExtension == ".png" )
    {
        $image = imagecreatefrompng($original_file_path);
    }

    imagecopyresampled($imageResample, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
/*
    if ($imgExtension == ".jpg")
        imagejpeg($imageResample, $save_path.$imgExtension);
    else if ( $imgExtension == ".gif" )
        imagegif($imageResample, $save_path.$imgExtension);
    else if ( $imgExtension == ".png" )
        imagepng($imageResample, $save_path.$imgExtension);
*/
    //my jpg format
    $imgExtension = ".jpg";
    imagejpeg($imageResample, $save_path.$imgExtension, 100);
    //end my

    imagedestroy($imageResample);
    imagedestroy($image);
}

function getExtension($str) {

    $i = strrpos($str,".");
    if (!$i) { return ""; }

    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}

function png2jpg($originalFile, $outputFile, $quality) {
    $image = imagecreatefrompng($originalFile);
    imagejpeg($image, $outputFile, $quality);
    imagedestroy($image);
}

/* Statuses
 *
 * --table users
 *
 * --table advertisements
 *
 * status values: 0, 1;
 * status = 0 - default status, on moderation
 * status = 1 - on site
 * status = 2 - hot ads
 * status = 10 - archive
 *
 *
 */

?>
