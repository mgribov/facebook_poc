<?php
require_once 'config.php';
require_once 'functions.php';

$fb_raw = parse_fb();
$fb = verify_fb($fb_raw);
$show_admin = (is_array($fb) && $fb['oauth_token']['admin']);

$album = false;
if (strlen($_GET['a']) && !strstr($_GET['a'], '/')) {
     $album = urldecode($_GET['a']);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" id="facebook" class=" no_js">
    <head>
        <?php include_once('head.php');?>
    </head>

    <body>

        <a href='/facebook/fb_albums/index.php?fb=<?php echo $fb_raw;?>'>Back</a><br/>

        <?php 
            if ($show_admin) {
                $upload .= '<form action="album.php" enctype="multipart/form-data">';
                $upload .= '<input type="hidden" name="fb_oauth" value="' . $fb_raw . '">';
                $upload .= '<input type="hidden" name="album_name" value="' . $album . '">';
                $upload .= '<input type="file" name="image">';
                $upload .= '<input type="submit" name="a" value="' . ACTION_UPLOAD_IMAGE . '">';
                $upload .= '</form>';
            } 

            $images = '';
            if ($album) {
                $dir = ALBUM_DIR . '/' . $album;
                $a = scandir($dir);
                if (count($a) > 2) {
                    foreach ($a as $i) {
                        if ($i == '.' || $i == '..') {
                            continue;
                        }

                        $images .= "<img height='150' width='150' src='" . ALBUM_PATH . "/$album/" . $i . "'>";
                        if ($show_admin === true) {
                            $images .= '<form action="album.php">';
                            $images .= '<input type="hidden" name="fb_oauth" value="' . $fb_raw . '">';
                            $images .= '<input type="hidden" name="album_name" value="' . $album . '">';
                            $images .= '<input type="hidden" name="image_name" value="' . $i . '">';
                            $images .= '<input type="submit" name="a" value="' . ACTION_DELETE_IMAGE . '">';
                            $images .= '</form>';
                        }

                        $images .= '<br/>';
                    }
                }
            }
        ?>

        <?php echo $upload;?>
        <?php echo $images; ?>

        <?php include_once('fb_foot.php');?>
    </body>
</html>
