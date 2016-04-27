<header>
<!-- ********************************************************************************************************************************* -->
				<nav class="big"> <!--Début Menu Principal-->
					<a class="logo" href="http://localhost/g2s/"><img src="http://localhost/g2s/images/logositegrand.png" alt="Logo G2S" /></a>
					<ul id = "menumain">
						<li><a href="index.html">Accueil</a></li>
						<li><a href="index.html">Critiques<div class = "miniarrow">v</div></a>
							<ul class = "sub1">
								<li><a href="index.html">France<div class = "miniarrow">></div></a>
									<ul class = "sub2">
										<li><a href="index.html">Paris</a></li>
										<li><a href="index.html">Ile-de-France</a></li>
										<li><a href="index.html">Normandie</a></li>
										<li><a href="index.html">Les landes</a></li>
										<li><a href="index.html">Pays Basque</a></li>
									</ul>
								</li>
								<li><a href="index.html">Angleterre</a></li>
								<li><a href="index.html">Belgique</a></li>
							</ul>
						</li>
						<li><a href="index.html">A la carte</a></li>
						<li><a href="index.html"></a></li>
						<li><a href="index.html">Coups de coeur</a></li>
						<li><a href="index.html">Articles</a></li>
						<li><a href="index.html">A propos</a></li>
					</ul>	
				</nav>
				<nav class="tiny">
					<a class="logo" href="http://localhost/g2s/"><img src="http://localhost/g2s/images/logositegrand.png" alt="Logo G2S" /></a>
					<ul class="topnav">
						<li><a class="active" href="index.html">Accueil</a></li>
						<li><a href="index.html">Critiques</a></li>
						<li><a href="index.html">A la carte</a></li>
						<li><a href="index.html">Coups de coeur</a></li>
						<li><a href="index.html">Articles</a></li>
						<li><a href="index.html">A propos</a></li>
						<li class="icon"><a href="javascript:void(0);" onclick="myFunction()">☰</a></li>
					</ul>
				</nav>
				</header>
<script>
function myFunction() {
    document.getElementsByClassName("topnav")[0].classList.toggle("responsive");
}
</script>
<script>
$(window).bind('scroll', function () {
    if ($(window).scrollTop() > 50) {
        $('.tiny').addClass('fixed');
	  $('section').addClass('addOffset');
	  $('nav img').addClass('smallit');
    } else {
        $('.tiny').removeClass('fixed');
	  $('section').removeClass('addOffset');
	  $('nav img').removeClass('smallit');
    }
});

</script>
<!-- ********************************************************************************************************************************* -->	