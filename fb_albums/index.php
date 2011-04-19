<?php
// @see: http://developers.facebook.com/docs/authentication/signed_request/
if ($_POST['signed_request']) {
    
    // sigil albums app 
    $app_secret = '89119a0175ea7418ade40ee9522b71e9';
    $json = parse_signed_request($_POST['signed_request'], $app_secret);
    //var_dump($json);
}

$dir = '../images';
$images = scandir($dir);

function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  // check sig
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
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

        <p>Welcome!</p>

        <?php 
            if (count($images) > 2) {
                foreach ($images as $i) {
                    if ($i == '.' || $i == '..') {
                        continue;
                    }
                    echo "<a href='image.php?i=$i'><img height='50' width='50' src='/facebook/images/$i'></a>";
                }
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
