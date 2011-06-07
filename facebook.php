<?php

require_once './php-sdk/src/facebook.php';
$error = array();

$facebook = new Facebook(array(
  'appId'  => 'id',
  'secret' => 'secret',
  'cookie' => true,
));

$session = $facebook->getSession();
$me = null;

// Session based API call.
if ($session) {
    try {
        $uid = $facebook->getUser();
        $me = $facebook->api('/me');
    } catch (FacebookApiException $e) {
        $error[] = ($e->getMessage());
    }
}

// login or logout url will be needed depending on current user state.
if ($me) {
    $logoutUrl = $facebook->getLogoutUrl();
} else {
    $loginUrl = $facebook->getLoginUrl();
}


$filepath = '/images/';


if ($_POST['a'] == 'Send') {

    if ($_FILES['image']['error'] == 0) {
        $filename = $_FILES["image"]["name"];
        $ret = move_uploaded_file($_FILES["image"]["tmp_name"], dirname(__FILE__) . $filepath . $_FILES["image"]["name"]);
    }

    if ($_FILES['icon']['error'] == 0) {
        $icon = $_FILES["icon"]["name"];
        $ret = move_uploaded_file($_FILES["icon"]["tmp_name"], dirname(__FILE__) . $filepath . $_FILES["icon"]["name"]);
    }


    $msg = array(
            'message' => $_POST['msg'], 
            'caption' => $_POST['cap'],
            'description' => $_POST['desc'],
            'name' => $_POST['nm'],
            'actions' => array('name' => 'View on UD', 'link' => 'http://www.urbandaddy.com',
                                'name' => 'View Field Report', 'link' => 'http://www.urbandaddy.com'),
            );


    if ($_FILES['image']['error'] == 0) {
        $msg['picture'] = 'http://sigilsoftware.com/facebook' . $filepath . $filename;
    }

    if ($_FILES['icon']['error'] == 0) {
        $msg['icon'] = 'http://sigilsoftware.com/facebook' . $filepath . $icon;
    }


    if ($_POST['link']) {
        $msg['link'] = $_POST['link'];
    }

    try {
        $update = $facebook->api('/me/feed', 'POST', $msg);
        $error[] = 'Successfully posted to facebook';
    } catch (FacebookApiException $e) {
        $error[] = ($e->getMessage());
    }
}

if ($_POST['a'] == 'FQL Query') {
    $fql_url = 'http://api.facebook.com/method/fql.query?query=' . urlencode($_POST['fql']);
    $fql_ret = file_get_contents($fql_url);
var_dump($fql_url, $fql_ret);exit;
}


?>

<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <title>Facebook Post Test</title>
    </head>
    <body>
    <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId   : '<?php echo $facebook->getAppId(); ?>',
          session : <?php echo json_encode($session); ?>, // don't refetch the session when PHP already has it
          status  : true, // check login status
          cookie  : true, // enable cookies to allow the server to access the session
          xfbml   : true // parse XFBML
        });

        // whenever the user logs in, we refresh the page
        FB.Event.subscribe('auth.login', function() {
          window.location.reload();
        });
      };

      (function() {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>
        <?php
            if (count($error)) {
                echo '' . implode('<br>', $error) . '<br>';
            }
        ?> 
        <br>
        <?php if ($me): ?>
        Hi <img src="https://graph.facebook.com/<?php echo $uid; ?>/picture"><?=$me['name'];?> 
            <a href="<?php echo $logoutUrl; ?>"><img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif"></a>
        <?php else: ?>
        <fb:login-button perms="read_stream,publish_stream"></fb:login-button>
        <?php endif; ?>
        <form method="post" action="facebook.php" enctype="multipart/form-data">
            Image: <input type='file' name='image'><br>
            Icon: <input type='file' name='icon'><br>
            Link: <input type="text" name="link" value='<?=$_POST['link'];?>'><br>
            Name: <input type="text" name="nm" value='<?=$_POST['nm'];?>'><br>
            Caption: <input type="text" name="cap" value='<?=$_POST['cap'];?>'><br>
            Description: <input type="text" name="desc" value='<?=$_POST['desc'];?>'><br>
            Message: <textarea name='msg'><?=$msg['message'];?></textarea><br>
            <input type='submit' name='a' value='Send'>
        </form>
        <!--
        <form method="post" action="facebook.php" enctype="multipart/form-data">
            FQL Query: <textarea name='fql'><?=$_POST['fql'];?></textarea><br>
            FQL Return: <textarea name='fql_ret'><?=$fql_ret;?></textarea><br>
            <input type='submit' name='a' value='FQL Query'>
        </form>
        -->
    </body>
</html>
