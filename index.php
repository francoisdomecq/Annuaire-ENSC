<!DOCTYPE html>
<html>
<?php 
session_start();
require_once"includes/functions.php";
include('includes/head.php');?>

<body>
	<?php include('includes/header.php');?>
	<?php
	//Si jamais l'utilisateur n'est pas connecté, l'écran d'accueil devient un écran de connexion
	if (!isset($_SESSION['login']))
	{
		include('login.php');
	}
	else
	{ 
		{?>
		<div class="container  marginTop">
			<div class="well">
				<h2 class="text-center">Effectuez une recherche</h2>
				<hr>
				<form method="POST" action="recherche.php">
					<div class="row">
					    <!-- On affiche sous forme de 3 selectes les différentes compétences renseignées dans la BDD
					    Nous utilisons donc 3 fois la fonction AfficherListeComp() (que nous avons créé afin d'éviter la duplication de code) :
					     - 1 fois pour la colonne libelle_comp1
					     - 1 fois pour la colonne libelle_comp2
					     - 1 fois pour la colonne libelle_comp3!-->
						<div class="col-xs-12 col-md-4 col-sm-4">
							<?php
							echo "Recherche par compétences <br/>";
							AfficherListeComp('libelle_comp1');
							AfficherListeComp('libelle_comp2');
							AfficherListeComp('libelle_comp3');		
							?>	
						</div>
						<!-- On affiche dans le div suivant les secteurs d'activité renseignés. Nous n'avons pas créé de fonctions car nous prenons les données d'une seule colonne dans la BDD!-->
						<div class="col-xs-12 col-md-44 col-sm-4">
							<?php 
	                        echo "Recherche par secteur d'activité ";
                        	$MaRequete=  getDb() -> query ("SELECT * FROM secteur_activité");
                        	echo "<select name='libelle_sec'>";
                        	echo "<option selected>--Choisir un secteur--</option>";
                        	while ($Tuple = $MaRequete ->fetch() )
                        	{
                        		echo "<option value='$Tuple[id_sec]'>$Tuple[libelle_sec]</option>";
                        	}
                        	echo "</select><br/><br/>";
							?>
						</div>
						<!-- On affiche dans le div suivant les régions renseignées. Nous n'avons pas créé de fonctions car nous prenons les données d'une seule colonne dans la BDD!-->
						<div class="col-xs-12 col-md-4 col-sm-4	">
							<?php 
                        	echo "Recherche par lieu";print"<br/>";	
                        	$MaRequete=	getDb()->	query("SELECT * FROM organisme");
                        	echo "<select name='region_org'>";
                        	echo "<option selected>--Choisir une région--</option>";
                        	while ($Tuple = $MaRequete ->fetch() )
                        	{
                        		echo "<option value='$Tuple[id_org]'>$Tuple[region_org]</option>";
                        	}
                        	echo "</select><br/><br/>";
							?>
						</div>
					</div><br/>
					<div class="form-group">
                    	<div class="col-sm-6 col-sm-offset-4 col-md-4 col-md-offset-5">
                        	<button type="submit" class="btn btn-default btn-primary">
                            <span class="glyphicon glyphicon-search"></span>Effectuer ma recherche
                        	</button>
                    	</div>
                	</div>	
                </form><br/>
			</div>
		</div><br/>
	
		<div class="container">
			<div class="well">
				<div class="row">				
				<!-- Dans le div ci-dessous, on affiche la liste des promotions renseignées dans la BDD.
				    Lorsque l'utilisateur clique sur une promotion, il est redirigé vers index.php?id='id_promo'.
				    L'affichage des étudiants de la promotion se fait alors dans un bloc à droite de celui-ci!-->
					<div class="col-xs-6 col-md-4 col-sm-5">
						<h2 class="text-center" style="font-size:20px;">Promotions</h2><hr>
						<?php 
						$MaRequete="SELECT * FROM promotion";
                        $MonRs = getDb() -> query( $MaRequete );
                        print"<ul  style='list-style-type:none; '>";
                        while($Tuple = $MonRs ->fetch())
                        {
                    		print"<li><a class='boldblack' href='index.php?id=$Tuple[id_promo]'>$Tuple[libelle_promo] ($Tuple[annee_debut]-$Tuple[annee_fin])</a><li><br/>";       
                        }  
                        print"</ul>"; 
						?>
					</div>
					<div class="col-xs-6 col-md-8 col-sm-7">
						<?php 
						//On récupère la valeur de l'id de la promo sélectionnée
						foreach($_GET as $key => $value);
						//Si une promo est sélectionné, alors on affiche ses étudiants.
						if(isset($value))
						{
							$MaRequete="SELECT * FROM etudiant WHERE (id_promo=$value AND val_inscription=1) ORDER BY nom_etu ";
                            $MonRs = getDb() -> query( $MaRequete );
                            print"<ul style='list-style-type:none;'><h2 class='text-center' style='font-size:20px;'>Etudiants</h2><hr>";    
                            while($Tuple = $MonRs ->fetch())
                     	    {
                                print"<li><a class='boldblack'href='profil.php?id=$Tuple[id_etu]'>$Tuple[prenom_etu] $Tuple[nom_etu]</a></li><br/>";        
                    	    }	
                            print"</ul>"; 
						}?>
					</div>
				</div>
			</div>
		</div>
	<?php } } ?>

	<?php require_once "includes/footer.php"; ?>
</body>
</html>