<script>

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
</script>