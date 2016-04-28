<?php 
function wd_remove_accents($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return $str;
}

try {
	$bdd = new PDO('mysql:host=localhost;dbname=g2s;charset=utf8', 'root', '');
}
catch (Exception $e) {
	die('Erreur : '.$e->getMessage());
}
/* Ambiance */
$tagsambiance = array();
$req = $bdd->prepare('SELECT label FROM ambiance');
$req->execute(array());
while ($reponse = $req->fetch()) {
	$tag = wd_remove_accents($reponse['label']);
	$tag = str_replace("'","",$tag);
	$tag = str_replace(" ","",$tag);
	$tagsambiance[$tag] = $reponse['label'];
}
$req->closeCursor();

/*Cuisine*/
$tagscuisine = array();
$req = $bdd->prepare('SELECT label FROM cuisine');
$req->execute(array());
while ($reponse = $req->fetch()) {
	$tag = wd_remove_accents($reponse['label']);
	$tag = str_replace("'","",$tag);
	$tag = str_replace(" ","",$tag);
	$tagscuisine[$tag] = $reponse['label'];
}
$req->closeCursor();

/*Lieu PAYS*/
$tagspays = array();
$req = $bdd->prepare('SELECT label FROM lieu WHERE pays = "0"');
$req->execute(array());
while ($reponse = $req->fetch()) {
	$tag = wd_remove_accents($reponse['label']);
	$tag = str_replace("'","",$tag);
	$tag = str_replace(" ","",$tag);
	$tagspays[$tag] = $reponse['label'];
}
$req->closeCursor();

/*Lieu Region*/
$tagsregion = array();
$req = $bdd->prepare('SELECT label FROM lieu WHERE pays = ?');
foreach ($tagspays as $pays) {
	$req->execute(array($pays));
	$tagsregion[$pays] = array();
	while ($reponse = $req->fetch()) {
		$tag = wd_remove_accents($reponse['label']);
		$tag = str_replace("'","",$tag);
		$tag = str_replace(" ","",$tag);
		$tagsregion[$pays][$tag] =  $reponse['label'];
	}
}

print_r ($tagsregion);
unset ($tagsregion['France']['Ile-de-France']);
$req->closeCursor();
?>

<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>Manager G2S</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manager.css" media="all">
    <script src='https://cdn.tinymce.com/4/tinymce.min.js'></script>
    <script>tinymce.init({ 
        selector:'#critique',
        theme: 'modern',
        plugins : ['advlist autolink link image lists charmap print preview hr',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'save table contextmenu directionality template paste textcolor'],
        toolbar: 'insertfile undo redo | bold italic | alignleft aligncenter alignjustify | bullist outdent indent | link | preview media fullpage | forecolor', 
        color_picker_callback: function(callback, value) {
    callback('#FF00FF');
  }
    });
</script>
</head>
<?php $formulairerempli = false;

// Définition des variables et mise à vide
$nom = $tel = $horaire = $siteweb = $adresse = $pays = $region = "";
$ncuisine = $nqp = $nservice = $nambiance = $date = $critique = "";
$tagscuisinechecked = $tagsambiancechecked = array();

//Définition des erreurs

$nomerr = $telerr = $horaireerr = $siteweberr = $adresseerr = $payserr = $regionerr = $photoerr = "";
$ncuisineerr = $nqperr = $nserviceerr = $nambianceerr = $critiqueerr = $dateerr = $tagsambianceerr = $tagscuisineerr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["nom"])) {$nomerr = "Le nom est requis";} else {$nom = test_input($_POST["nom"]);}
	if (empty($_POST["horaire"])) {$horaireerr = "L'horaire est requis (max 4 lignes)";} else {$horaire = test_input($_POST["horaire"]);}
	if (empty($_POST["tel"])) {$telerr = "Le numéro de téléphone est requis";}else {$tel = test_input($_POST["tel"]);}
	if (empty($_POST["siteweb"])) {$siteweberr = "L'URL du site web est requise";} else {$siteweb = test_input($_POST["siteweb"]);}
	if (empty($_POST["adresse"])) {$adresseerr = "L'adresse est requise";} else {$adresse = test_input($_POST["adresse"]);}
	if (empty($_POST["date"])) {$adresseerr = "La date de visite est requise";} else {$date = test_input($_POST["date"]);}
	if (empty($_POST["pays"])) {$payserr = "Le pays est requis";} else {$pays = test_input($_POST["pays"]);}
	if (empty($_POST["region"])) {$regionerr = "La région est requise";} else {$region = test_input($_POST["region"][0]);}
	if (empty($_POST["notecuisine"])) {$ncuisineerr = "La note \"cuisine\" est requise";} else {$ncuisine = test_input($_POST["notecuisine"]);}
	if (empty($_POST["noterqp"])) {$nqperr = "La note \"rapport Q/P\" est requise";} else {$nqp = test_input($_POST["noterqp"]);}
	if (empty($_POST["noteservice"])) {$nserviceerr = "La note \"service\" est requise";} else {$nservice = test_input($_POST["noteservice"]);}
	if (empty($_POST["noteambiance"])) {$nambianceerr = "La note \"ambiance\" est requise";} else {$nambiance = test_input($_POST["noteambiance"]);}
	if (empty($_POST["critique"])) {$critiqueerr = "Le texte de la critique est requis";} else {$critique = test_input($_POST["critique"]);}
	foreach ($tagscuisine as $tagkey => $tag) {
		if (isset($_POST[$tagkey])) {
			$tagscuisinechecked[$tagkey] = $tag;
		}
	}
	foreach ($tagsambiance as $tagkey => $tag) {
		if (isset($_POST[$tagkey])) {
			$tagsambiancechecked[$tagkey] = $tag;
		}
	}
	if (empty($tagscuisinechecked)) {
		$tagscuisineerr = "Sélectionne au moins un tag !!!";
	}
	if (empty($tagsambiancechecked)) {
		$tagsambianceerr = "Sélectionne au moins un tag !!!";
	}
	if(count($_FILES['upload']['name']) == 0){
		$photoerr = "Ajoute au moins une photo";
	}
    
    if (empty("{$nomerr}{$telerr}{$horaireerr}{$siteweberr}{$adresseerr}{$payserr}{$regionerr}{$ncuisineerr}{$nqperr}{$nserviceerr}{$nambianceerr}{$critiqueerr}{$tagscuisineerr}{$tagsambianceerr}{$photoerr}")) {
        $formulairerempli = true;
    }
}
if ($formulairerempli) {
	echo 'OUI';
}else {
	echo 'NON';
}


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>


<?php if(!($formulairerempli)) : ?>
<body>
    <span id ="blocpage">
        <h1>Manager G2S</h1>
        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id ="form" class="form">
            <div>
                <fieldset>
                    <legend><b>Caractéristiques globales</b></legend>
                    <div>
                        <label for="nom">Nom du restaurant :</label>
                        <input type="text" name="nom" id="nom" placeholder="Ex : Nol Bou" size="30" maxlength="30" value="<?php echo $nom ;?>"/>
                        <span class="error">* <?php echo $nomerr;?></span>
                    </div>
                    <div>
                        <label for="tel">Numéro de téléphone :</label>
                        <input type="tel" name="tel" id="tel" placeholder="Ex : +33112345678" value="<?php echo $tel;?>" />
                        <span class="error">* <?php echo $telerr;?></span>
                    </div>
                    <div>
                        <label for="horaire">Horaire :</label>
                        <textarea type="text" name="horaire" id="horaire" placeholder="du mardi au sam:             11h30-14h00               18h30-23h00" cols="20" maxlength="100" rows = "4" value="<?php echo $horaire;?>"></textarea>
                        <span class="error">* <?php echo $horaireerr;?></span>
                    </div>
                    <div>
                        <label for="siteweb">Site web :</label>
                        <input type="url" name="siteweb" id="siteweb" placeholder="http://www.nolbou.fr" size="40" maxlength="40" value="<?php echo $siteweb;?>" />
                        <span class="error">* <?php echo $siteweberr;?></span>
                    </div>
			  <div>
                        <label for="date">Date de visite :</label>
                        <input type="date" name="date" id="date" placeholder="YYYY-mm-dd" value="<?php echo $date;?>" />
                        <span class="error">* <?php echo $dateerr;?></span>
                    </div>
                </fieldset>
                <fieldset id="regionfield">
                    <legend><b>Localisation</b></legend>
                    <div>
                        <label for="adresse">Adresse complète:</label>
                        <textarea type="text" name="adresse" id="adresse" placeholder="Adresse en deux lignes : 22 rue Frémicourt puis code postal et ville voir Pays" size="30" maxlength="50" value="<?php echo $adresse;?>"></textarea>
                        <span class="error">* <?php echo $adresseerr;?></span>
                    </div>
                    <div class="formregion">
                        <label for="pays">Pays</label><br />
                        <select name="pays" id="pays">
                            <option disabled selected> -- select an option -- </option>
				    <?php
					foreach ($tagspays as $tagkey => $tag) {
						echo "<option value = \"".$tagkey."\" ";
						if (isset($pays) && $pays==$tagkey) echo "selected";
						echo " >".$tag."</option>\n";
					}
 					?>
                        </select>
                        <span class="error">* <?php echo $payserr;?></span>
                    </div>
                    <div class="formregion">
                        <div style="display:inline;" id="divregion">
                            <label for="region">Région</label><br />
                            <select name="region[]" id="region">
                                <option disabled selected> -- select an option -- </option>
                                <!--> Populate with JS <-->
                            </select>
                            <span class="error">* <?php echo $regionerr;?></span>
                        </div>
                    </div>
                </fieldset>
		    <fieldset id="tags">
				<legend><b>Tags</b></legend>
				<fieldset id="tags1">
					<legend><b>Tags Cuisine</b></legend>
					<ul class="checkbox">
					<?php
					foreach ($tagscuisine as $tagkey => $tag) {
						echo "<li><label  for=\"".$tagkey."\"><input ";
						if (array_key_exists($tagkey, $tagscuisinechecked)) echo "checked ";
						echo"id=\"".$tagkey."\" name=\"".$tagkey."\" value=\"".$tagkey."\" type=\"checkbox\">".$tag."</label></li>\n";
					}
 					?>
					</ul>
					<span class="error">* <?php echo $tagscuisineerr;?></span>
				</fieldset>
				<fieldset id="tags2">
					<legend><b>Tags Ambiance</b></legend>
					<ul class="checkbox">
					<?php
					foreach ($tagsambiance as $tagkey => $tag) {
						echo "<li><label  for=\"".$tagkey."\"><input ";
						if (array_key_exists($tagkey, $tagsambiancechecked)) echo "checked ";
						echo"id=\"".$tagkey."\" name=\"".$tagkey."\" value=\"".$tagkey."\" type=\"checkbox\">".$tag."</label></li>\n";
					}
 					?>
					</ul>
					<span class="error">* <?php echo $tagsambianceerr;?></span>
				</fieldset>
                </fieldset>
                <fieldset id="notation">
                    <legend><b>Notation</b></legend>
                        <div>
                            <label for="notecuisine">Cuisine</label>
                            <input type="number" name="notecuisine" id="notecuisine" min="0" max="5" value="<?php echo $ncuisine;?>"/>
                            <span class="error">* <?php echo $ncuisineerr;?></span>
                        </div>
                        <div>
                            <label for="noterqp">Rapport Q/P</label>
                            <input type="number" name="noterqp" id="noterqp" min="0" max="5" value="<?php echo $nqp;?>"/>
                            <span class="error">* <?php echo $nqperr;?></span>
                        </div>
                        <div>
                            <label for="noteservice">Service</label>
                            <input type="number" name="noteservice" id="noteservice" min="0" max="5" value="<?php echo $nservice;?>"/>
                            <span class="error">* <?php echo $nserviceerr;?></span>
                        </div>
                        <div>
                            <label for="noteambiance">Ambiance</label>
                            <input type="number" name="noteambiance" id="noteambiance" min="0" max="5" value="<?php echo $nambiance;?>"/>
                            <span class="error">* <?php echo $nambianceerr;?></span>
                        </div>
                </fieldset>
                <fieldset id="critiquefield">
                    <legend><b>Critique</b></legend>
                        <textarea name="critique" id="critique" ></textarea>
				<span class="error">* <?php echo $critiqueerr;?></span>
                </fieldset>
                <fieldset id="Upload des photos">
			<legend><b>Upload Photos</b></legend>
                    <div>
                        <label for='upload'>Ajouter des fichiers :</label>
                        <input id='upload' name="upload[]" type="file" multiple="multiple" />
				 <span class="error">* <?php echo $photoerr;?></span>
                        <p>
                            <em style="font-size : 0.8em;">Sélectionner plusieurs images avec ctrl. Les images doivent être de 640*360.</em>
                        </p>
                    </div>
                </fieldset>
            </div>
            <p id="divsubmit"><input type="submit" name="submit" value="Submit"></p>
        </form>
    </span>
    <script type="text/javascript">
        // removes all option elements in select list 
        // removeGrp (optional) boolean to remove optgroups
        function removeAllOptions(sel, removeGrp) {
            var len, groups, par;
            if (removeGrp) {
                groups = sel.getElementsByTagName('optgroup');
                len = groups.length;
                for (var i=len; i; i--) {
                    sel.removeChild( groups[i-1] );
                }
            }
            len = sel.options.length;
            for (var i=len; i; i--) {
                par = sel.options[i-1].parentNode;
                par.removeChild( sel.options[i-1] );
            }
        }
        function appendDataToSelect(sel, obj) {
            var f = document.createDocumentFragment();
            var labels = [], group, opts;
            function addOptions(obj) {
                var f = document.createDocumentFragment();
                var o;
                for (var i=0, len=obj.text.length; i<len; i++) {
                    o = document.createElement('option');
                    o.appendChild( document.createTextNode( obj.text[i] ) );
                    o.value = obj.value[i];
                    f.appendChild(o);
                }
                return f;
            }
            if ( obj.text ) {
                opts = addOptions(obj);
                f.appendChild(opts);
            } 
            sel.appendChild(f);
            document.getElementById("region").options[0].disabled = true;
        }
        // anonymous function assigned to onchange event of controlling select list
        document.forms['form'].elements['pays'].onchange = function(e) {
            // name of associated select list
            var relName = 'region[]';
            // reference to associated select list 
            var relList = this.form.elements[ relName ];
            // get data from object literal based on selection in controlling select list (this.value)
            var obj = Select_List_Data[ relName ][ this.value ];
            // remove current option elements
            removeAllOptions(relList, true);
            // call function to add optgroup/option elements
            // pass reference to associated select list and data for new options
            appendDataToSelect(relList, obj);
        };
        // object literal holds data for optgroup/option elements
        var Select_List_Data = {
            // name of associated select list
            'region[]': {
		<?php 
		$lastkey = end(array_keys($tagspays));
		foreach ($tagspays as $tagkey => $tag) {
			echo "\t\t\t\t".$tagkey." : { \n";
			echo "\t\t\t\t\t text : ['-- select an option --','".implode("','", $tagsregion[$tagkey])."'],\n";
			echo "\t\t\t\t\t value : ['-- select an option --','".implode("','", array_keys($tagsregion[$tagkey]))."'],\n";
			echo "\t\t\t\t}";
			if ($lastkey != $tagkey) echo ",";
			echo "\n";			
		}                                                                                              
		?>
            }
        };
        // populate associated select list when page loads
        function addLoadEvent(func) { //define a new function called addLoadEvent which takes in one param which should be function
            var oldonload = window.onload; 
            if (typeof window.onload != 'function') { //if window.onload is not a function,  and thus has never been defined before elsewhere
                window.onload = func; //assign 'func' to window.onload event. set the function you passed in as the onload function
            } else {
                window.onload = function() {
                    var form = document.forms['form'];
                    // reference to controlling select list
                    var sel = form.elements['pays'];
                    sel.selectedIndex = 0;
                    // name of associated select list
                    var relName = 'region[]';
                    // reference to associated select list
                    var rel = form.elements[ relName ];
                    // get data for associated select list passing its name
                    // and value of selected in controlling select list
                    var data = Select_List_Data[ relName ][ sel.value ];
                    // add options to associated select list
                    appendDataToSelect(rel, data);
                };   
            }   
        }
    </script>			
</body>





<?php else : ?>
    <p>Formulaire correctement rempli</p>
    <p><?php echo$nom?></p>
    <p><?php echo wd_remove_accents(str_replace(' ','',$nom))?></p>
    <p><?php echo$tel ?></p>  
    <p><?php echo nl2br($horaire) ?></p> 
    <p><?php echo$siteweb ?></p>
    <p><?php echo$date ?></p> 
    <p><?php echo nl2br($adresse)?></p> 
    <p><?php echo$pays ?></p> 
    <p><?php echo$region ?></p>
    <p><?php print_r ($tagscuisinechecked) ?></p>
    <p><?php print_r ($tagsambiancechecked) ?></p>
    <p><?php echo$ncuisine ?></p>
    <p><?php echo$nqp ?></p>
    <p><?php echo$nservice ?></p>
    <p><?php echo$nambiance ?></p>
    <p><?php echo$critique ?></p>
<?php
    /* Remplace les departements d ile-de-france par la région */
		if ($pays == 'France') {
			if (in_array($region, array('Seine-et-Marne', 'Seine-Saint-Denis','Essones'))) {
				echo 'Ile-de-France';
				$forceRegion = 'Ile-de-France';
			}
		}
?>
    
    
<?php 
    /* Construct req for adding new db entry */
	$req = $bdd->prepare('INSERT INTO critique(nomCritique, nomResto, pays, region, adresse, noteQP, noteAmbiance, noteService, noteCuisine, dateDeVisite, dateAjout, urlSite, tel, horaire, nombreDePhoto, nombreDeVue)
	VALUES(:nomCritique, :nomResto, :pays, :region, :adresse, :noteQP, :noteAmbiance, :noteService, :noteCuisine, :dateDeVisite, :dateAjout, :urlSite, :tel, :horaire, :nombreDePhoto, :nombreDeVue)');
						     
	$req->execute(array(
	'nomCritique' => wd_remove_accents(str_replace(' ','',$nom)), 
	'nomResto' => $nom, 
	'pays' => $pays, 
	'region' => $region, 
	'adresse' => nl2br($adresse), 
	'noteQP' => $nqp, 
	'noteAmbiance' => $nambiance, 
	'noteService' => $nservice, 
	'noteCuisine' => $ncuisine, 
	'dateDeVisite' => $date, 
	'dateAjout' => date("Y-m-d H:i:s"), 
	'urlSite' => $siteweb, 
	'tel' => $tel, 
	'horaire' => nl2br($horaire), 
	'nombreDePhoto' => count($_FILES['upload']['name']), 
	'nombreDeVue' => 0
	));
	/* know get critique id */
	$req = $bdd->prepare('SELECT id FROM critique WHERE nomCritique="'.wd_remove_accents(str_replace(' ','',$nom)).'"'); 
	$req->execute(array());
	$reponse = $req->fetch();
	$idcritique = $reponse["id"];
	/* now add all label to correct table */
	/* Ambiance */
	$idsambiance = array();
	$tamb = array();
	foreach ($tagsambiancechecked as $tagkey => $tag) {
		$tamb[]=$tag;
	}
	$in = join(',', array_fill(0, count($tamb), '?'));
	$select = "SELECT id FROM ambiance WHERE label IN ($in)";
	$req = $bdd->prepare($select);
	$req->execute($tamb);
	while ($reponse = $req->fetch()) {
		$idsambiance[] = $reponse["id"];
	}
	$req->closeCursor();
	/* add relation */
	$req = $bdd->prepare('INSERT INTO relation_ambiance(id_critique, id_label)VALUES(:id_critique,:id_label)');
	foreach ($idsambiance as $id) {
		$req->execute(array('id_critique' => $idcritique, 'id_label' => $id));
	}
	/* Cuisine */
	$idscuisine = array();
	$tcui = array();
	foreach ($tagscuisinechecked as $tagkey => $tag) {
		$tcui[]=$tag;
	}
	$in = join(',', array_fill(0, count($tcui), '?'));
	$select = "SELECT id FROM cuisine WHERE label IN ($in)";
	$req = $bdd->prepare($select);
	$req->execute($tcui);
	while ($reponse = $req->fetch()) {
		$idscuisine[] = $reponse["id"];
	}
	$req->closeCursor();
	/* add relation */
	$req = $bdd->prepare('INSERT INTO relation_cuisine(id_critique, id_label)VALUES(:id_critique,:id_label)');
	foreach ($idscuisine as $id) {
		$req->execute(array('id_critique' => $idcritique, 'id_label' => $id));
	}
	/*Lieu */
	$idslieu = array();
	$tlieu = array();
	$tlieu[]=$region;
	$tlieu[]=$pays;
	if (isset($forceRegion)) {
		$tlieu[]=$forceRegion;
	}
	$in = join(',', array_fill(0, count($tlieu), '?'));
	$select = "SELECT id FROM lieu WHERE label IN ($in)";
	$req = $bdd->prepare($select);
	$req->execute($tlieu);
	while ($reponse = $req->fetch()) {
		$idslieu[] = $reponse["id"];
	}
	$req->closeCursor();
	/* add relation */
	$req = $bdd->prepare('INSERT INTO relation_lieu(id_critique, id_label)VALUES(:id_critique,:id_label)');
	foreach ($idslieu as $id) {
		$req->execute(array('id_critique' => $idcritique, 'id_label' => $id));
	}
?>


<?php
	/* constructpath */
	$path = "../critiques/".strtolower(wd_remove_accents(str_replace(' ','',$pays)))."/";
	if (isset($forceRegion)) {$path = $path.strtolower(wd_remove_accents(str_replace(' ','',$forceRegion)));}
	else {$path = $path.strtolower(wd_remove_accents(str_replace(' ','',$region)));}
	echo $path;
	if (!is_dir($path)) {
		mkdir($path, 0700, true);
	}
	if (!is_dir($path.'/Images/'.wd_remove_accents(str_replace(' ','',$nom)))) {
		mkdir($path.'/Images/'.wd_remove_accents(str_replace(' ','',$nom)), 0700, true);
	}
      $pathcommun = "C:/xampp/htdocs/g2s/commun/";
	
	/* Write critique in file */
	$handle = fopen($path.'/'.wd_remove_accents(str_replace(' ','',$nom)).".php", 'w');
	fwrite($handle, '<?php $critique = "'.wd_remove_accents(str_replace(' ','',$nom))."\" ?>\n");
	fwrite($handle, "<?php include(\"".$pathcommun."prepare.php\") ?>\n");
	fwrite($handle, "<html>\n");
	fwrite($handle, "\t<?php include(\"".$pathcommun."head.php\") ?>\n");
	fwrite($handle, "\t<body>\n");
	fwrite($handle, "\t\t<?php include(\"".$pathcommun."header.php\") ?>\n");
	fwrite($handle, "\t\t<div id=\"bloc_page\">\n");
	fwrite($handle, "\t\t\t<aside id=\"bloc_aside\">\n");
	fwrite($handle, "\t\t\t\t<?php include(\"".$pathcommun."aside.php\") ?>\n");
	fwrite($handle, "\t\t\t</aside>\n");
	fwrite($handle, "\t\t\t<section>\n");
	fwrite($handle, "\t\t\t\t<?php include(\"".$pathcommun."diapo.php\") ?>\n");
	fwrite($handle, "\t\t\t\t<article>\n");
	fwrite($handle, "\t\t\t\t\t".'<h1> <?php echo $nomResto; ?> </h1>'."\n");
	fwrite($handle, htmlspecialchars_decode($critique));
	fwrite($handle, "\n");
	fwrite($handle, "\t\t\t\t\t<?php include(\"".$pathcommun."enbref.php\") ?>\n");
	fwrite($handle, "\t\t\t\t</article>\n");
	fwrite($handle, "\t\t\t</section>\n");
	fwrite($handle, "\t\t\t</div>\n");
	fwrite($handle, "\t\t\t<?php include(\"".$pathcommun."footer.php\") ?>\n");
	fwrite($handle, "\t\t</body>\n");
	fwrite($handle, "\t</html>\n");
	fwrite($handle, "<?php include(\"".$pathcommun."scriptmap.php\") ?>\n");
	fwrite($handle, "<?php include(\"".$pathcommun."scriptflex.php\") ?>\n");
	fclose($handle);
	
	/* same img */
	for($i=0; $i<count($_FILES['upload']['name']); $i++) {
		//Get the temp file path
		$tmpFilePath = $_FILES['upload']['tmp_name'][$i];
		//Make sure we have a filepath
		if($tmpFilePath != ""){
			//save the filename
			$shortname = $i+1;
			//save the url and the file
			$filePath = $path.'/Images/'.wd_remove_accents(str_replace(' ','',$nom)).'/'.$shortname.'.jpg';
			//Upload the file into the temp dir
			move_uploaded_file($tmpFilePath, $filePath);
		}
	}
    ?>
<?php endif; ?>
</html>


