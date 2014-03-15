<html>
<head>
    <meta name="viewport" content="width=device-width; initial-scale=1;">
    <link type="text/css" href="css/style.css" rel="stylesheet" />
<link type='text/css' href='css/index.css' rel='stylesheet' media='screen' />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.party_name, .button').on('click', function(e){e.stopPropagation(); e.preventDefault()})
        $('div.item').on('click', function(){
            div = $(this)
            if(!div.hasClass('open')){
                $('div.item.open').removeClass('open').find('div.extra').slideUp()
                div.addClass('open')
                div.find('div.extra').slideDown()
            }
            else{
                div.removeClass('open')
                div.find('div.extra').slideUp()
            }
        })
        $('.submit').on('click', function(){
            $(this).closest('form').submit()  
        })
    })
</script>
</head>
<body>
<div id="header">Zwayo</div>
    <div id="make_party" class="item padded">
        <h2 onclick="">Make a Party</h2>
            <div class="extra">
                <form action="login.php" method="POST">
                    <input type="hidden" name="action" value="make_party"/>
                    <input type="text" class="party_name rounded" name="party_name" placeholder="Your Party Name" /><br />
                    <a class="button submit" onclick="make_party(event)">Make Party</a>
                </form>
            </div>
        </form></div>
    </div>
    <div id="join_party" class="item padded open">
        <h2 onclick="">Join a Party</h2>
            <div class="extra" style="display: block;">
                <form action="login.php" method="POST">
                    <input type="hidden" name="action" value="join_party"/>
                    <input type="text" class="party_name rounded" name="party_name" placeholder="Secret Name" /><br />
                    <a class="button submit" onclick="join_party(event)">Let's Party!</a>
                </form>
            </div>
        </form></div>
    </div>
</body>
</html>

