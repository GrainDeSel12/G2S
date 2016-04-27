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
$req = $bdd->prepare('SELECT *, round((noteAmbiance+noteService+noteCuisine+noteQP)/4,1) AS noteMoyenne FROM critique WHERE nomCritique = ?');
$req->execute(array($critique));
while ($reponse = $req->fetch()) {
	$id = $reponse['id'];
	$nomResto = $reponse['nomResto'];
	$pays = $reponse['pays'];
	$region = $reponse['region'];
	$adresse = wd_remove_accents(str_replace('\t', '+',$reponse['adresse']));
	$noteQP = $reponse['noteQP'];
	$noteAmbiance = $reponse['noteAmbiance'];
	$noteService = $reponse['noteService'];
	$noteCuisine = $reponse['noteCuisine'];
	$noteMoyenne = $reponse['noteMoyenne'];
	$dateDeVisite = $reponse['dateDeVisite'];
	$urlSite = $reponse['urlSite'];
	$tel = $reponse['tel'];
	$horaire = $reponse['horaire'];
	$nbPhoto = $reponse['nombreDePhoto'];
}
$req->closeCursor();
$req = $bdd->prepare('SELECT label FROM ambiance WHERE id IN (SELECT id_label FROM relation_ambiance WHERE id_critique = ? )');
$req->execute(array($id));
while ($reponse = $req->fetch()) {
	$tagAmbiance[] = $reponse['label'];
}
$req->closeCursor();
$req = $bdd->prepare('SELECT label FROM lieu WHERE id IN (SELECT id_label FROM relation_lieu WHERE id_critique = ? )');
$req->execute(array($id));
while ($reponse = $req->fetch()) {
	$tagLieu[] = $reponse['label'];
}
$req->closeCursor();
$req = $bdd->prepare('SELECT label FROM cuisine WHERE id IN (SELECT id_label FROM relation_cuisine WHERE id_critique = ? )');
$req->execute(array($id));
while ($reponse = $req->fetch()) {
	$tagCuisine[] = $reponse['label'];
}
$req->closeCursor();
$urlmap ="https://maps.googleapis.com/maps/api/geocode/json?address=".str_replace(' ','+',$adresse)."&key=AIzaSyBcB6f9LK2B3Cy_HJ9ZPXU0C1UvSl-4Q7Y";
$json = file_get_contents($urlmap);
$store_data = json_decode(str_replace("&quot;","\"",htmlentities($json)));
$lat = $store_data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
$lng = $store_data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
?>