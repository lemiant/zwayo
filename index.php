<do
<html>
<head>
    <style>
        body{
            font-family: Arial, sans-serif;
            background-color: #333333;
            color: white;
        }
    </style>
</head>
<body>
<div id="msg"></div>
<div id="header">Zwayo</div>
<form action="server/join_party.php" method="POST" style="display: block">
    <input type="hidden" name="action" value="make_party" />
    <label>Make Party</label><br />
    Party name: <input type="text" name="party_name" /><br />
    Your name: <input type="text" name="guest_name" />
    <input type="submit" value="Make party" />
</form>
<div id="party_list"></div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
    var x = document.getElementById("msg");
    function getLocation()
    {
        console.log('gl')
        if (navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(request_parties);
        }
        else{x.innerHTML = "Geolocation is not supported by this browser.";}
    }
    function request_parties(position)
    {
        console.log('rq')
        window.lat = position.coords.latitude;
        window.long =  position.coords.longitude;
        $.ajax({
            url: 'server/get_party_list.php',
            type: 'post',
            dataType: 'json',
            data: {lat: lat,
                    long: long},
            success: populate_parties
        })
    }
    function populate_parties(result, status){
        console.log('pop')
        console.log(result);
        for(i=0; i<result.items.length; i++){
            row = result.items[i];
            $("#party_list").append('<div style="display: block">'+row.party_name+'<form action="server/join_party.php" method="POST">'+
                '<input type="hidden" name="party_id" value="'+row.id+'" />'+
                'Your name: <input type="text" name="guest_name" />'+
                '<input type="submit" value="Join party" /></form>'+'</div>')
        }
        $('form').append('<input type="hidden" name="lat" value="'+lat+'" />'+'<input type="hidden" name="long" value="'+long+'" />' )
    }
    getLocation();
</script>
</body>
</html>