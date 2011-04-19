<?php
$image = false;
if (strlen($_GET['i']) && !strstr($_GET['i'], '/')) {
     $image = $_GET['i'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" id="facebook" class=" no_js">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-language" content="en" />
        <title>Sigil Albums</title>

        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.Canvas.setSize();
            }
            // Do things that will sometimes call sizeChangeCallback()
            function sizeChangeCallback() {
                FB.Canvas.setSize();
            }
        </script>

        <style type="text/css">
            body {
                width:520px;
                margin:0; padding:0; border:0;
            }
        </style>

    </head>

    <body>

        <a href='/facebook/fb_albums/index.php'>Back</a><br/>

        <?php 
            if ($image) {
                echo "<img height='150' width='150' src='/facebook/images/$image'>";
            }
        ?>



        <div id="fb-root"></div>
        <script src="http://connect.facebook.net/en_US/all.js"></script>
        <script>
            FB.init({
                appId : '369079504615',
                status : true, // check login status
                cookie : true, // enable cookies to allow the server to access the session
                xfbml : true // parse XFBML
            });
 
            window.fbAsyncInit = function() {
                FB.Canvas.setAutoResize();
            }
        </script>

    </body>
</html>
