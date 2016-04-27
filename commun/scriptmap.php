		<script>
		function initialize() {
			var monCentre = new google.maps.LatLng(<?php echo $lat+0.003 ?>,<?php echo $lng ?>)
			var monCentrem = new google.maps.LatLng(<?php echo $lat ?>,<?php echo $lng ?>)
			var mapProp = {
			center:monCentre,
			zoom:14,
			mapTypeId:google.maps.MapTypeId.ROADMAP,
			panControl:false,
			zoomControl:true,
			mapTypeControl:false,
			scaleControl:false,
			streetViewControl:false
			};
			var map=new google.maps.Map(document.getElementById("map"),mapProp);
			var marker=new google.maps.Marker({position:monCentrem});
			marker.setMap(map);
			var stringmarker = <?php echo "\"".$nomResto."<br />"."22, rue de Frémicourt <br /> 75012 Paris"."<br /> <a style=\\\"font-size : 0.7em;\\\"href=\\\"http://maps.google.com/?q=".str_replace(" ","+",$nomResto)."&near=".$lat.",".$lng."\\\" target=_blank>Voir sur Google Maps</a>\"" ?>;
			infowindow = new google.maps.InfoWindow({ content:stringmarker });
			infowindow.open(map,marker);
			google.maps.event.addListener(marker, 'click', function() { infowindow.open(map,marker);});
		}
		</script>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcB6f9LK2B3Cy_HJ9ZPXU0C1UvSl-4Q7Y&callback=initialize" type="text/javascript"></script>