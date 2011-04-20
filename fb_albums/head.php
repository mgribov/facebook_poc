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

            var state = 'none';
            function showhide(layer_ref) {
                if (state == 'block') {
                    state = 'none';
                } else {
                    state = 'block';
                }

                if (document.all) { //IS IE 4 or 5 (or 6 beta)
                    eval( "document.all." + layer_ref + ".style.display = state");
                }

                if (document.layers) { //IS NETSCAPE 4 or below
                    document.layers[layer_ref].display = state;
                }

                if (document.getElementById &&!document.all) {
                    hza = document.getElementById(layer_ref);
                    hza.style.display = state;
                }
            }
        </script> 

        <style type="text/css">
            body {
                width:520px;
                margin:0; padding:0; border:0;
                background: none repeat scroll 0 0 #FFFFFF;
                color: #333333;
                direction: ltr;
                font-family: "lucida grande",tahoma,verdana,arial,sans-serif;
                font-size: 11px;
                text-align: left;
                unicode-bidi: embed;
            }
        </style>

