<?php
require_once 'config.php';
require_once 'functions.php';

$fb_raw = parse_fb();
$fb = verify_fb($fb_raw);
$show_admin = (is_array($fb) && $fb['oauth_token']['admin']);

if ($_POST['a']) {
    switch (strtolower($_POST['a'])) {
        case ACTION_CREATE_ALBUM:
        break;

    }
}

$albums = null;
$a = scandir(ALBUM_DIR);
if (count($a) > 2) {
    foreach ($a as $i) {
        if ($i == '.' || $i == '..') {
            continue;
        }
        $album_icon = get_album_icon($i);
        $albums .= "<a href='album.php?a=$i&fb=$fb_raw'><img height='50' width='50' src='" . ALBUM_PATH . "/$i/$album_icon'> - $i</a>";
        if ($show_admin) {
            $albums .= '<form action="album.php?d=' . $i . '">';
            $albums .= '<input type="hidden" name="fb_oauth" value="' . $fb_raw . '">';
            $albums .= '<input type="hidden" name="album_name" value="' . $i . '">';
            $albums .= '<input type="submit" name="a" value="' . ACTION_DELETE_ALBUM . '">';
            $albums .= '</form>';
        }
        $albums .= '<br/>';
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" id="facebook" class=" no_js">
    <head>
    <?php include_once('head.php');?>
    </head>

    <body>

        <p>Welcome!</p>

        <?php if ($show_admin) : ?>
            <div id="nav">
                <a href="#" onClick="showhide('create_album')">Create Album</a>
            </div>
            
            <div id="create_album" style="display:none;">
                <form target='album.php' method="POST" enctype="multipart/form-data">
                <input type="hidden" name="fb_oauth" value="<?php echo $fb_raw?>">
                Album Name: <input type="text" name="album_name" value="<?php echo $album_name;?>"><br/>
                Album Icon: <input type="file" name="album_icon"><br/>
                <input type="submit" name="a" value="<?php echo ACTION_CREATE_ALBUM;?>"><br/>
            </div>

        <?php endif; ?>

        <?php 
            if ($albums !== null) {
                echo $albums;
            }
        ?>
        
        <?php include_once('fb_foot.php');?>
    </body>
</html>
