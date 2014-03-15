<html>
<head>
    <meta name="viewport" content="width=device-width; initial-scale=1;">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="css/login.css" rel="stylesheet" />
    <script type="text/javascript">
        $(document).ready(function(){
            new_secret_name()
            $('#go').on('click', function(){
                tmpl = '<form action="server/do_make_party.php" method="POST"><input type="hidden" name="secret_name" value="'+$('#secret_name').html()+'" /></form>';
                form = $(tmpl);
                form.submit();
            })
        })
        function new_secret_name(){
            $.ajax({url: 'server/new_secret_name.php',
                   dataType: 'json',
                   success: function(js){ if(js.result == 'success') $('#secret_name').html(js.secret_name); }
                })
        }
    </script>
</head>
<body>
<?php if(!empty($FB_error))echo $FB_error ?>
<div id="wrapper">
    <div id="cell" >
        <div id="inner" style="height: 180px">
            <p>Your party has a secret name:</p>
            <div>
                <div class="big" id="secret_name">
                </div><div class="big" id="refresh" onclick="new_secret_name()">
                    <img src="imgs/refresh.png" style="height: 30px; margin-top: 4px;" />
                </div>
            </div>
            <p>Give this to anyone you want to join the party.</p>
            <div id="go" class="button">Sounds Good!</div>
        </div>
    </div>
</div>
</body>
