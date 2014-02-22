if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(
		function(position) {
			var latitude = position.coords.latitude;
			var longitude = position.coords.longitude;
	});
} else {
	document.getElementsByTagName("html")[0].innerHTML="Geolocation not available :(";
}

$.post("../php/get_party_list.php", 
	{
		lat		: latitude,
		long	: longitude, 
	}, 
	function(data, status) {
		alert("Data: " + data + "\\nStatus: " + status);
		
	}
);

/*
   send lat, long
   send back JSON
   result success/failure, items
   party objects
   id , party_name votes
*/