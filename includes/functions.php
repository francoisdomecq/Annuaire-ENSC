<?php 

function escape($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
}

function redirect($url) {
    header("Location: $url");
}

function getDb() 
{
 	$BDD = new PDO( "mysql:host=localhost;dbname=id16475581_projetweb;charset=utf8", "id16475581_francoisdmq","\C38K1>6#@Kx9CFC",
 	array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION));
	; // connexion serveur de BD MySql et choix base
	return $BDD;
}

//Fonctions servant à la recherche filtrée
function AfficherListeComp($lib){
	$Requete = getDb()->query("SELECT * FROM competences");
	echo "<select name='$lib'>";
	echo "<option selected>--Choisir une compétence--</option>";
	while ($Tuple = $Requete ->fetch() )
	{
		echo "<option value='$Tuple[id_comp]'>$Tuple[$lib]</option>";
	}
	echo "</select><br/><br/>";
}

//Fonction qui sert à empêcher de créer deux étudiants ayant le même username
function doublonEtudiant($PTable,$login)
			{		
				$booleen=true;
				$Requete1 = "SELECT * FROM $PTable";
				$Resultat1 = getDb()-> query($Requete1);
				while($Tuple = $Resultat1 -> fetch() )
					{
						if($Tuple['username']==$login)
								$booleen=false;
					}	
				return $booleen;
			}

//===============================================================//
//FONCTIONS UTILISEES POUR AFFICHER LES INFORMATIONS D'UN ETUDIANT
function recupInfoEtu($PTable, $info)
{
	foreach($_GET as $key => $value);
	$requete = getDb() -> query("SELECT $info FROM $PTable WHERE id_etu=$value");
	$MonRs=$requete->fetch();
	return $MonRs[$info];
}

function recupExp($id_exp)
{
	foreach($_GET as $key => $value);
	$RequeteIdEXp = getDb() -> query("SELECT * FROM experience WHERE id_etu=$value and id_exp=$id_exp");
	$Tuple=$RequeteIdEXp->fetch();
	print"<article>";
		print"<h3><a class='boldblack'href='experience.php?id=$Tuple[id_exp]'>$Tuple[libelle_exp] $Tuple[date_deb] $Tuple[date_fin]</h3></a><br/>";
		echo$Tuple['description'];
	print"</article>";
}
?>