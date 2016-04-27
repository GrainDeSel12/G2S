
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
	infowindow = new google.maps.InfoWindow({ content:"Nol Bou"+"<br />"+"22, rue de Frémicourt"+"<br />"+"75012 Paris" });
	infowindow.open(map,marker);
	google.maps.event.addListener(marker, 'click', function() { infowindow.open(map,marker);});
}
google.maps.event.addDomListener(window, 'load', initialize);

$(document).ready(function () {
	$('.flexslider').flexslider({
		animation: 'slide',
		animation: 'easeOutElastic', useCSS: false,

		slideshowSpeed : 5000,
		animationSpeed : 1500,
		controlsContainer: '.flexslider'
	});
});
$(document).ready(function () {
	$('.flexslidermini').flexslider({
		animation: "fade",
		slideshowSpeed : 5000,
		animationSpeed : 800,
		controlsContainer: '.flexslidermini'
	});
});