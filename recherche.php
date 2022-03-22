<?php 
session_start();
require_once"includes/functions.php";

/*Pour la recherche, on regarde d'abord quels sont les champs remplis pour ne pas créer d'erreur.
On créé aussi un tableau qui contiendra les étudiants ayant un lien avec la recherche.
Puis pour chaque select rempli dans la page index.php, on récupère l'étudiant associé et on l'ajoute dans le tableau*/
$tabEtu =array();
if(!empty($_POST['libelle_comp1'])&&$_POST['libelle_comp1']!="--Choisir une compétence--")
{
	$id_comp = $_POST['libelle_comp1'];
	$RequeteExp = getDb() -> query ("SELECT * FROM experience WHERE id_comp=$id_comp");
	$IdEtu = $RequeteExp->fetch();
	$id=$IdEtu['id_etu'];
	$Requete = getDb()->query("SELECT * FROM etudiant WHERE id_etu=$id");
	$Etu = $Requete->fetch();
	$RequeteExp = getDb()->query("SELECT * FROM experience where id_etu=$id");
	$Exp = $RequeteExp->fetch();
	if($Exp['bool_exp']==1)
		$tabEtu[] = $Etu; 	
}

if(!empty($_POST['libelle_comp2'])&&$_POST['libelle_comp2']!="--Choisir une compétence--")
{
	$id_comp = $_POST['libelle_comp2'];
	$RequeteExp = getDb() -> query ("SELECT * FROM experience WHERE id_comp=$id_comp");
	$IdEtu = $RequeteExp->fetch();
	$id=$IdEtu['id_etu'];
	$Requete = getDb()->query("SELECT * FROM etudiant WHERE id_etu=$id");
	$Etu = $Requete->fetch();
	$RequeteExp = getDb()->query("SELECT * FROM experience where id_etu=$id");
	$Exp = $RequeteExp->fetch();
	if($Exp['bool_exp']==1)
		$tabEtu[] = $Etu;
}

if(!empty($_POST['libelle_comp3'])&&$_POST['libelle_comp3']!="--Choisir une compétence--")
{
	$id_comp = $_POST['libelle_comp3'];
	$RequeteExp = getDb() -> query ("SELECT * FROM experience WHERE id_comp=$id_comp");
	$IdEtu = $RequeteExp->fetch();
	$id=$IdEtu['id_etu'];
	$Requete = getDb()->query("SELECT * FROM etudiant WHERE id_etu=$id");
	$Etu = $Requete->fetch();
	$RequeteExp = getDb()->query("SELECT * FROM experience where id_etu=$id");
	$Exp = $RequeteExp->fetch();
	if($Exp['bool_exp']==1)
		$tabEtu[] = $Etu;
}

if(!empty($_POST['libelle_sec'])&&$_POST['libelle_sec']!="--Choisir un secteur--")
{
	$id_sec= $_POST['libelle_sec'];
	$RequeteExp = getDb() -> query ("SELECT * FROM experience WHERE id_sec=$id_sec");
	$IdEtu = $RequeteExp->fetch();
	$id=$IdEtu['id_etu'];
	$Requete = getDb()->query("SELECT * FROM etudiant WHERE id_etu=$id");
	$Etu = $Requete->fetch();
	$RequeteExp = getDb()->query("SELECT * FROM experience where id_etu=$id");
	$Exp = $RequeteExp->fetch();
	if($Exp['bool_exp']==1)
		$tabEtu[] = $Etu;
}

if(!empty($_POST['region_org'])&&$_POST['region_org']!="--Choisir une région--")
{
	$id_org= $_POST['region_org'];
	$RequeteExp = getDb() -> query ("SELECT * FROM experience WHERE id_sec=$id_org");
	$IdEtu = $RequeteExp->fetch();
	$id=$IdEtu['id_etu'];
	$Requete = getDb()->query("SELECT * FROM etudiant WHERE id_etu=$id");
	$Etu = $Requete->fetch();
	$RequeteExp = getDb()->query("SELECT * FROM experience where id_etu=$id");
	$Exp = $RequeteExp->fetch();
	if($Exp['bool_exp']==1)
		$tabEtu[] = $Etu;
}

//Dans l'éventualité où un étudiant aurait été en lien avec la recherche plusieurs fois, on enlève tous les éléments duppliqués dans le tableau.
$tabFinal = array_unique($tabEtu,SORT_REGULAR);
?>

<html>
<?php include('includes/head.php');?>
<body>
	<?php include('includes/header.php');?>
	<div class="container  marginTop">
		<div class="well">
			<h1 class="text-center">Liste des étudiants</h1>
			<div class="well well-bg-white">
				<div class="col-xs-offset-2 col-sm-offset-3 col-md-offset-4">
					<?php 
					//Pour chaque étudiant dans le tableau, on affiche ses informations
					foreach($tabFinal as $Etu)
					{
						$idP = getDb()->prepare("SELECT * FROM promotion WHERE id_promo =?");
						$idP->execute(array($Etu['id_promo']));
						$idPromo = $idP->fetch();
						print"<a style='color:black;'href='profil.php?id=$Etu[id_etu]'>$Etu[nom_etu]  $Etu[prenom_etu] ($idPromo[annee_debut]-$idPromo[annee_fin])</a><br/>";
					}?>
				</div>
			</div>
		</div>
	</div>
	<?php include('includes/footer.php');?>
</body>
</html>

	
