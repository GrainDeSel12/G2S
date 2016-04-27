<div id = "enbref">
						<div id ="enbref2">
							<div id = "moyennenote"> <?php echo $noteMoyenne; ?> </div>
							<div id = "enbreftag">En bref...</div>
							<h2> <?php echo $nomResto; ?> </h2>
							<div id="divnote">
								<div id ="divnote1">
									<div id = "cuisinenote">
										<p class="libellenote">Cuisine</p>
										<p class="note"><?php echo $noteCuisine; ?>/5</p>
									</div>
									<div id = "servicenote">
										<p class="libellenote">Service</p>
										<p class="note"><?php echo $noteService; ?>/5</p>
									</div>
								</div>
								<div id ="divnote2">
									<div id = "ambiancenote">
										<p class="libellenote">Ambiance</p>
										<p class="note"><?php echo $noteAmbiance; ?>/5</p>
									</div>
									<div id = "rqpnote">
										<p class="libellenote">Qualité/Prix</p>
										<p class="note"><?php echo $noteQP;?>/5</p>
									</div>
								</div>
							</div>
							<div id = "listtag">
								<div id="listtagbas">
<?php
	foreach ($tagCuisine as $tag) {
		echo "\t\t\t\t\t\t\t\t\t<div class=\"tag\"><p>".$tag."</p></div>\n";
	}
	foreach ($tagAmbiance as $tag) {
		echo "\t\t\t\t\t\t\t\t\t<div class=\"tag\"><p>".$tag."</p></div>\n";
	}
	foreach ($tagLieu as $tag) {
		echo "\t\t\t\t\t\t\t\t\t<div class=\"tag\"><p>".$tag."</p></div>\n";
	}
?>
								</div>
							</div>
						</div>
						<div id = "enbref1">
							<div class = "mapconteneur">
								<div id ="map"></div>
							</div>
						</div>
						<div id="info">
								<div id=adresse>
									<p>22, rue de Frémicourt <br/>75015 Paris</p>
								</div>
								<div id=time>
									<p>Du mardi au dimanche : <br/>   12h00-14h00<br/>19h00-22h00</p>
								</div>
								<div id=websitetel>
									<div id=website>
										<p><a href= <?php echo "\"http://".$urlSite."\""; ?> id ="lienresto" target=_blank>Nol Bou</a></p>
									</div>
									<div id=tel>
										<p> <?php echo $tel; ?> </p>
									</div>
								</div>
							</div>
					</div>