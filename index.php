<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="css/index.css" rel="stylesheet" />
</head>
<body>
<div id="header">Zwayo</div>
    <div id="make_party" class="item padded">
       <h2 onclick="">Make a Party</h2>
            <div class="extra">
                <form action="login_no_fb.php" method="POST">
                    <input type="hidden" name="action" value="make_party"/>
                    <input type="hidden" class="party_name rounded" name="party_name" placeholder="Your Party Name" /><br />
                    <a class="button submit" onclick="make_party(event)">Make Party</a>
                </form>
            </div>
        </form></div>
    </div>
    <div id="join_party" class="item padded open">
        <h2 onclick="">Join a Party</h2>
            <div class="extra" style="display: block;">
                <form action="login_no_fb.php" method="POST">
                    <input type="hidden" name="action" value="join_party"/>
                    <input type="text" class="party_name rounded" name="party_name" placeholder="Secret Name" /><br />
                    <a class="button submit" onclick="join_party(event)">Let's Party!</a>
                </form>
            </div>
        </form></div>
    </div>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
    $('.party_name, .button').on('click', function(e){e.stopPropagation();})
    $('div.item').on('click', function(){
        div = $(this)
        $('div.item.open').not(div).removeClass('open').find('div.extra').slideUp()
        div.addClass('open')
        div.find('div.extra').slideDown()
    })
    $('.submit').on('click', function(){
        $(this).closest('form').submit()
    })
    </script>
</body>
</html>
