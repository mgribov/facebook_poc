<?php

// @see: http://developers.facebook.com/docs/authentication/signed_request/
function parse_fb() {
    if ($_POST['signed_request']) {
        $fb = $_POST['signed_request'];
    } elseif ($_GET['fb']) {
        $fb = $_GET['fb'];
    }
    return $fb;
}

function verify_fb($fb) {
    $json = parse_signed_request($fb, APP_SECRET);
    return $json;
}

function  get_album_icon($album_name) {
    if (is_dir(ALBUM_DIR)) {
        $a = scandir(ALBUM_DIR . '/' . $album_name);
        foreach ($a as $i) {
            if ($i == ALBUM_ICON . '.png' || $i == ALBUM_ICON . '.jpg' || $i == ALBUM_ICON . '.gif') {
                return $i;
            }
        }
    }
    return null;
}

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

