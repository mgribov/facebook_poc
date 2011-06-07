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


if ($_POST['a'] == 'Send') {

    $msg = array(
            'subject' => $_POST['subject'],
            'message' => $_POST['msg'], 
            );


    try {
        $update = $facebook->api('/me/notes', 'POST', $msg);
        $error[] = 'Successfully posted to facebook';
    } catch (FacebookApiException $e) {
        $error[] = ($e->getMessage());
    }
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
        <form method="post" action="note.php" enctype="multipart/form-data">
            Subject: <input type="text" name="subject" value='<?=$_POST['subject'];?>'><br>
            Message: <textarea name='msg'><?=$_POST['msg'];?></textarea><br>
            <input type='submit' name='a' value='Send'>
        </form>
    </body>
</html>
