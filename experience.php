<!DOCTYPE HTML>
<html>
<?php 
session_start();
require_once"includes/functions.php";?>
<?php include('includes/head.php');?>
<body>
	<?php include('includes/header.php');?>
	<div class="container  marginTop">
	    <!-- On affiche l'intitulé de l'expérience et la description dans ce container !-->
		<div class="well col-md-7 col-sm-7 col-xs-7 ">
		<?php
		foreach($_GET as $key => $value);
        $RequeteIdEXp = getDb() -> query("SELECT * FROM experience WHERE id_exp=$value");	
        while ($Tuple=$RequeteIdEXp->fetch())
	    {
    	    print"<article>";
    	        print"<h3>";echo$Tuple['libelle_exp'];print" ";echo$Tuple['date_deb'];print" ";echo$Tuple['date_fin'];print"</h3></a>"	;
		            print"<br/>";
		        echo$Tuple['description'];
		    print"</article>";
	    }
        ?>
		</div>
		<!-- Dans ce container, on affiche les détails de l'expérience : l'organisme, les compétences, le secteur d'activité !-->
		<div class="well col-md-offset-1 col-md-4  col-sm-offset-1 col-sm-4 col-xs-4 col-xs-offset-1">
		<?php 
		//On récupère les informations sur l'organisme
        $RequeteIdOrg =getDb()-> query("SELECT id_org FROM experience WHERE id_exp = $value");
        $IdOrg1= $RequeteIdOrg->fetch();
    	$IdOrg2 = $IdOrg1['id_org'];
    	$RequeteOrg = getDb()->query ("SELECT * FROM organisme WHERE id_org=$IdOrg2");
        while($Tuple = $RequeteOrg->fetch())
        {
        	print"<article>";
            	print"<h3>Organisme - $Tuple[nom_org]</h3>";
            	print"Statut juridique : $Tuple[type_org]<br/>";
            	print"Adresse : $Tuple[adresse_org], $Tuple[region_org]<br/>";
            print"</article>";
        }
        
        //On récupère les informations sur les compétences    
        $RequeteIdComp =getDb()-> query("SELECT id_comp FROM experience WHERE id_exp = $value");
        $IdComp1= $RequeteIdComp->fetch();
        $IdComp2 = $IdComp1['id_comp'];
        $RequeteComp = getDb()->query ("SELECT * FROM competences WHERE id_comp=$IdComp2");
        while($Tuple=$RequeteComp->fetch())
        {
            print"<h3>Compétences requises </h3>";
            print"<ul>";
            	print"<li>$Tuple[libelle_comp1]</li>";
        		print"<li>$Tuple[libelle_comp2]</li>";
        		print"<li>$Tuple[libelle_comp3]</li>";
        	print"</ul>";
        }
          
        //On récupère les informations sur le secteur d'activité  
        $RequeteIdSec =getDb()-> query("SELECT id_sec FROM experience WHERE id_exp = $value");
        $IdSec1= $RequeteIdSec->fetch();
        $IdSec2 = $IdSec1['id_sec'];
        $RequeteSec = getDb()->query ("SELECT * FROM secteur_activité WHERE id_sec=$IdSec2");
        while($Tuple=$RequeteSec->fetch())
        {
        	print"<h3>Secteur d'activité - $Tuple[libelle_sec]</h3>";
        }?>

		</div>
	</div>
	<?php include('includes/footer.php');?>
</body>
</html>
