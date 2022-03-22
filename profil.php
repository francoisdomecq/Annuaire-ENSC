<?php 
session_start();
require_once"includes/functions.php";
foreach($_GET as $key => $value);

//Toutes ces informations seront réutilisées par la suite dans ce fichier.
$username = recupInfoEtu('etudiant','username');
$adresse = recupInfoEtu('etudiant','adresse_postale');
$password = recupInfoEtu('etudiant','password');
$prenom = recupInfoEtu('etudiant', 'prenom_etu');
$nom = recupInfoEtu('etudiant', 'nom_etu');
$mail = recupInfoEtu('etudiant','adresse_electronique');
$telephone = recupInfoEtu('etudiant', 'telephone');
$sexe = recupInfoEtu('etudiant', 'sexe');
$boolMail = recupInfoEtu('etudiant', 'bool_mail');
$boolTel = recupInfoEtu('etudiant', 'bool_telephone');
$boolAdresse = recupInfoEtu('etudiant', 'bool_adresse');

$idPromo=recupInfoEtu('etudiant','id_promo');
$requete =getDb() ->query("SELECT * FROM promotion WHERE $idPromo= id_promo");
$NomPromo = $requete->fetch();
?>

<?php 
//Cette page contient 3 formulaires. Pour les différencier, nous leur avons attribué 3 valeurs : Form1, Form2 et Form3.
if(isset($_POST['Form']))
{
    //Si l'utilisateur remplit Form1 (formulaire "Modifier mes informations")
	if($_POST['Form']=="Form1")
	{
	    //Dans un premier temps, nous regardons quels champs ont été complétés par l'utilisateur.
	    //Les 4 if ci-dessous servent à gérer les champs où l'utilisateur souhaite modifier ses informations (téléphone, adresse postale, adresse mail et mot de passe)
		if(!empty($_POST['mdp']))
		{
			$mdp= escape($_POST['mdp']);
			$requete = getDb() -> exec("UPDATE etudiant SET password='$mdp' WHERE id_etu=$value");
		}
		if(!empty($_POST['adress']))
		{
			$adress=escape($_POST['adress']);
			$requete2 =getDb() -> exec("UPDATE etudiant SET adresse_postale='$adress' WHERE id_etu=$value");
		}
		if(!empty($_POST['mail']))
		{
			$mail=escape($_POST['mail']);
			$requete3 = getDb() -> exec("UPDATE etudiant SET adresse_electronique='$mail' WHERE id_etu=$value");
		}
		if(!empty($_POST['phone']))
		{
			$phone=escape($_POST['phone']);
			$requete4 = getDb() -> exec("UPDATE etudiant SET telephone='$phone' WHERE id_etu=$value");
		}
		
		/*Les 3 if ci-dessous servent à gérer les champs où l'utilisateur souhaite modifier les informations affichées (téléphone, mail, adresse). Dans chacun des if, on regarde d'abord la valeur du booléen associé.
		S'il est égal à 1 (information affichée), alors sa valeur passe à 0 (information masquée) et inversement*/
        if(!empty($_POST['boolMail']))
        {
            if($boolMail==0)
                $requete4 = getDb() -> exec("UPDATE etudiant SET bool_mail=1 WHERE id_etu=$value");
            if($boolMail==1)
                $requete4 = getDb() -> exec("UPDATE etudiant SET bool_mail=0 WHERE id_etu=$value");
        }
        if(!empty($_POST['boolTel']))
        {
            if($boolTel==0)
                $requete4 = getDb() -> exec("UPDATE etudiant SET bool_telephone=1 WHERE id_etu=$value");
            if($boolTel==1)
                $requete4 = getDb() -> exec("UPDATE etudiant SET bool_telephone=0 WHERE id_etu=$value");
        }
        if(!empty($_POST['boolAdresse']))
        {
            if($boolAdresse==0)
                $requete4 = getDb() -> exec("UPDATE etudiant SET bool_adresse=1 WHERE id_etu=$value");
            if($boolAdresse==1)
                $requete4 = getDb() -> exec("UPDATE etudiant SET bool_adresse=0 WHERE id_etu=$value");
        }
	}
	//Si l'utilisateur remplit Form 2 (Formulaire d'ajout d'expérience)
	if($_POST['Form']=="Form2")
	{
	    /*Les 3 if suivants servent à s'assurer que l'utilisateur a bien rempli tous les champs concernant l'organisme, le secteur d'activité et les compétences requises. Lorsqu'un utilisateur ajoute une expérience, nous créons d'abord une ligne dans les tables organisme, secteur_activite et competences. Puis, l'ajout d'une expérience se fait en dernier (4ème if).*/
        if(!empty($_POST['type_org']) and !empty($_POST['adresse_org']) and !empty($_POST['nom_org']) and !empty($_POST['region_org']))
        {
                $typeOrg = escape($_POST['type_org']);
                $adresse_org= escape($_POST['adresse_org']);
                $nom_org = escape($_POST['nom_org']);
                $region_org = escape($_POST['region_org']);
                $requete = getDb() -> prepare("INSERT INTO organisme(type_org, adresse_org, nom_org, region_org)  VALUES (:type_org, :adresse_org, :nom_org, :region_org)");
                $requete->bindValue('type_org', $typeOrg, PDO::PARAM_STR);
                $requete->bindValue('adresse_org', $adresse_org,PDO::PARAM_STR);
                $requete->bindValue('nom_org', $nom_org,PDO::PARAM_STR);
                $requete->bindValue('region_org', $region_org,PDO::PARAM_STR);
                $requete->execute();
        }
        if(!empty($_POST['comp1']) and !empty($_POST['comp2']) and !empty($_POST['comp3']))
        {
                $comp1 = escape($_POST['comp1']);
                $comp3= escape($_POST['comp3']);
                $comp2= escape($_POST['comp2']);
                $requete2 =getDb()->prepare("INSERT INTO competences(libelle_comp1, libelle_comp2, libelle_comp3) VALUES (:libelle_comp1, :libelle_comp2, :libelle_comp3)");
                $requete2->bindValue('libelle_comp1',$comp1,PDO::PARAM_STR);
                $requete2->bindValue('libelle_comp2',$comp2,PDO::PARAM_STR);
                $requete2->bindValue('libelle_comp3',$comp3,PDO::PARAM_STR);
                $requete2->execute();
        }
        if(!empty($_POST['Sec_act']))
        {
                $secact= escape($_POST['Sec_act']);
                $requete3 = getDb() ->prepare("INSERT INTO secteur_activité(libelle_sec)VALUES(:libelle_sec)");
                $requete3->bindValue('libelle_sec', $secact, PDO::PARAM_STR);
                $requete3->execute();
        }
        
        /* Le 4ème if (ajout d'une expérience dans la table experience)
        Nous récupérons dans un premier temps les valeurs renseignées par l'utilisateur. Puis, dans un second temps, nous récupérons les id de l'organisme, du secteur d'activité et des compétences (ce sont des clés étrangères à insérer dans la table expérience) que l'utilisateur a renseigné auparavant. Puis nous insérons dans la table expérience toutes ces informations.*/
        if (!empty($_POST['libelle'])and !empty($_POST['datedeb']) and !empty($_POST['datefin']) and !empty($_POST['description']))
        {
                $libelle = escape($_POST['libelle']);
                $datedeb= escape($_POST['datedeb']);
                $datefin= escape($_POST['datefin']);
                $description = escape($_POST['description']);
                $salaire=escape($_POST['salaire']);

                $idO= getDb() -> prepare ("SELECT id_org FROM organisme WHERE type_org=? and adresse_org=? and nom_org=?and region_org=?");
                $idO->execute(array($typeOrg,$adresse_org, $nom_org, $region_org));
                $idOrg = $idO->fetch();

                $idS = getDb()->prepare("SELECT id_sec FROM secteur_activité WHERE libelle_sec=?");
                $idS->execute(array($secact));
                $idSec = $idS->fetch();

                $idC = getDb()->prepare("SELECT id_comp FROM competences WHERE libelle_comp1=? and libelle_comp2=? and libelle_comp3=?");
                $idC->execute(array($comp1,$comp2,$comp3));
                $idComp = $idC->fetch();

                $requete4 = getDb() -> prepare("INSERT INTO experience(libelle_exp, date_deb, date_fin, description, salaire, id_etu, id_org, id_sec, id_comp) VALUES (:libelle_exp, :date_deb, :date_fin, :description, :salaire, :id_etu, :id_org, :id_sec, :id_comp)");
                $requete4->bindValue('libelle_exp',$libelle, PDO::PARAM_STR);
                $requete4->bindValue('date_deb',$datedeb, PDO::PARAM_STR);
                $requete4->bindValue('date_fin',$datefin, PDO::PARAM_STR);
                $requete4 -> bindValue('description',$description, PDO::PARAM_STR);
                $requete4 -> bindValue('salaire',$salaire,PDO::PARAM_INT);
                $requete4 -> bindValue('id_etu',$value,PDO::PARAM_INT);
                $requete4 -> bindValue('id_org',$idOrg['id_org'],PDO::PARAM_INT);
                $requete4 -> bindValue('id_sec',$idSec['id_sec'],PDO::PARAM_INT);
                $requete4 -> bindValue('id_comp',$idComp['id_comp'],PDO::PARAM_INT);
                $requete4->execute();
        }
    }
    //Si l'utilisateur a rempli le formulaire 3 (formulaire d'affichage des expériences)
    if($_POST['Form']=="Form3")
    {
        //Nous regardons si une expérience a bien été selctionnée
        if(isset($_POST['exp']))
        {
            //Si c'est le cas, alors nous gérons l'éventualité où plusieurs expériences ont été sélectionnées
            foreach($_POST['exp'] as $exp)
            {
                $boolExp = getDb()->query("SELECT * FROM experience WHERE id_exp='$exp'");
                $BoolExp = $boolExp->fetch();
                echo$BoolExp['bool_exp'];
                //Même chose que pour l'affichage des informations, on regarde la valeur de bool_exp pour chaque expérience puis on gère les différents cas.
                if($BoolExp['bool_exp']==1)
                {
                    $stmt = getDb() ->prepare("UPDATE experience SET bool_exp=0 WHERE id_exp='$exp'");
                    $stmt->execute();
                }
                else if ($BoolExp['bool_exp']==0)
                {
                    $stmt = getDb() ->prepare("UPDATE experience SET bool_exp=1 WHERE id_exp='$exp'");
                    $stmt->execute();
                }
            }
        } 
    }
}
?>
<html>
<?php include('includes/head.php');?>
<body>
	<?php include('includes/header.php'); echo$_SESSION['typeUser'];?>
	<div class="container marginTop">
		<div class="well">
			<div class=" text-center">
				<h1><?php print"$prenom $nom";?></h1>
				<h5>Etudiant à l'ENSC appartenant à la promotion 
				<?php print"$NomPromo[libelle_promo] ($NomPromo[annee_debut]-$NomPromo[annee_fin])"; 
				?>
				</h5><br/><hr>
			</div>
			<div class="row">
				<div class='col-xs-5 col-md-5 col-sm-5'>
					<h3 class="text-center">Contact</h3>
					<div class='col-xs-12 col-md-6 col-md-offset-2'>
                        <?php 
                        //Si l'utilisateur n'est pas un gestionnaire ou le gérant du compte qu'il regarde, alors on regarde les valeurs de bool_mail, bool_telephone et bool_adresse pour voir si nous affichons les informations.
                        if($_SESSION['typeUser']!="Gestionnaire"&&$_SESSION['login']!=$username)
                        {
                            $requeteBools = getDb()->query("SELECT * FROM etudiant WHERE id_etu=$value");
                            $Bools = $requeteBools->fetch();
                            if ($Bools['bool_mail']==1)
                            {
                                print"Email : $mail<br/>";
                            }
                            if ($Bools['bool_telephone']==1)
                            {
                                print"Telephone : $telephone<br/>";
                            }
                            if ($Bools['bool_adresse']==1)
                            {
                                print"Adresse postale: $adresse<br/>";
                            }
                        }
                        //Si l'utilisateur est un gestionnaire ou qu'il regarde son propre profil, alors les informations sont affichées systématiquement.
                        if ($_SESSION['typeUser']=="Gestionnaire"||$_SESSION['login']==$username)
                        {
                             print"Email : $mail <br/>";
                             print"Telephone : $telephone <br/>";
                             print"Adresse postale : $adresse<br/>";
                        }
                    ?>
					</div>
					<?php 
					//Ces informations ne sont accessibles qu'à un gestionnaire et au titulaire du compte
					if ($_SESSION['typeUser']=="Gestionnaire"||$_SESSION['login']==$username)
					{  print"<br/><br/><br/><br/>";
                        print"<h3 class='text-center'>Informations du compte</h3>";
                        print"<div class='col-xs-12 col-md-6 col-md-offset-2'>";  
						print"Nom d'utilisateur : $username <br/>Mot de passe: $password<br/>";
                        print"</div>";
					}?>
				</div>
				<div class='container  col-xs-6 col-md-6   col-sm-6'>
					<h3>Expériences</h3>
					<?php 
                    $RequeteIdEXp = getDb() -> query("SELECT * FROM experience WHERE id_etu=$value");
                    $compteur=$RequeteIdEXp;
                    //Même chose que avant, si l'utilisateur n'est pas un gestionnaire ou qu'il regarde le profil d'un autre étudiant, on regarde la valeur de bool_exp pour savoir s'il peut voir l'expérience
                    if($username!=$_SESSION['login']&&($_SESSION['typeUser']!="Gestionnaire"))
                    {
                        while ($Tuple=$RequeteIdEXp->fetch())
                        {
                            if ($Tuple['bool_exp']==1)
                            {
                                recupExp($Tuple['id_exp']);
                            }
                        }
                    }
                    //Si l'utilisateur est un gestionnaire ou le titulaire du compte, alors l'expérience sera affichée systématiquement.
                    if ($username==$_SESSION['login']||($_SESSION['typeUser']=="Gestionnaire"))
                    {   
                    {?>
                    <?php 
                    print"<form class='form-signin form-horizontal ' role='form' action='profil.php?id=$value' method='POST'>";?>
                    <?php
                    while($Tuple=$RequeteIdEXp->fetch())
                    {?>
                        <div>
                        <br/>
                        <?php
                        //Un utilisateur peut décider si l'expérience est affichée à tous ou non
                        if($username==$_SESSION['login'])
                        {
                            print"<input type='checkbox' id='$Tuple[id_exp]' name='exp[]' value='$Tuple[id_exp]'/>";
                            if ($Tuple['bool_exp']==1)
                                print"Masquer ";
                            else
                                print"Afficher ";print"Cette expérience à tous";
                        }
                            recupExp($Tuple['id_exp']);
                            print"<br/>";
                        ?>   
                        </div>
                    <?php }?>
                    <?php if($username==$_SESSION['login'])
                    {
                        print"<button class='col-xs-offset-5 col-md-offset-8 col-sm-offset-8 btn btn-default btn-primary' type='submit' name='Form' value='Form3'/>Modifier mes informations";
                    }?>
                    </form>
                    <?php }?>
                    <?php }?>    
				</div>
			</div>
		</div>
	</div>
	<?php 
	//Si l'utilisateur va voir son propre profil, il peut modfifier ses informations.
	if($_SESSION['login']==$username)
	{ ?>
	<div class="container">
		<div class="jumbotron ">
			<div class="row">
			    <!-- Formulaire de modification des informations!-->
				<div class="col-md-5 col-sm-5 col-xs-5">
				<?php 
				print"<form class='form-signin form-horizontal ' role='form' action='profil.php?id=$value' method='POST'>";?>
    					<h2>Modifier mon compte</h2>
                    	<div class="form-group ">
                    		<div class="col-xs-12  col-sm-12  col-md-6">
                    			<input type="password" name="mdp" class="form-control" placeholder="Mot de passe" >
                    		</div>
                   	 	</div>
                    	<div class="form-group">
                    		<div class="col-xs-12  col-sm-12  col-md-6">
                    			<input type="text" name="adress" class="form-control" placeholder="Adresse postale" >
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-xs-12  col-sm-12  col-md-6">
                    			<input type="text" name="mail" class="form-control" placeholder="Email" >
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class=" col-sm-12  col-md-6">
                    			<input type="text" name="phone" class="form-control" placeholder="Téléphone" >
                    		</div>
                    	</div>
                        <div class="form-group">
                            <div class="col-xs-12  col-sm-12  col-md-6">
                                <input type="checkbox" name="boolMail">
                                <?php 
                                if($boolMail==0)
                                {
                                    print" Afficher ";
                                }
                                else
                                {
                                    print" Masquer ";
                                }?>mon adresse mail
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6  col-md-8">
                                <input type="checkbox" name="boolTel"><?php 
                                if($boolTel==0)
                                {
                                    print" Afficher ";
                                }
                                else
                                {
                                    print" Masquer ";
                                }?>mon téléphone
                            </div>
                        </div>
                        <div class="form-group text-left">
                                <div class="col-sm-6  col-md-8">
                                    <input type="checkbox" name="boolAdresse"><?php 
                                if($boolAdresse==0)
                                {
                                    print" Afficher ";
                                }
                                else
                                {
                                    print" Masquer ";
                                }?>mon adresse postale
                                </div>
                            </div>
                    	<div class="form-group">
                        	<div class="col-sm-6 col-md-8 col-xs-10">
                            	<button type="submit" name="Form" class="btn btn-default btn-primary" value="Form1">
                                    Valider 
                                	<span class="glyphicon glyphicon-ok"></span>  
                            	</button>
                        	</div>
                    	</div>
				    </form>
				</div>
				<!-- Formulaire d'ajout d'une expérience!-->
				<div class="col-md-6 col-sm-6 col-xs-6 col-xs-offset-1">
					<h2>Ajouter une expérience</h2>
					<?php
        			print"<form class='form-signin form-horizontal ' role='form' action='profil.php?id=$value' method='POST'>";?>
                		<h5>Expérience</h5>
                		<div class="form-group">
                        	<div class="col-xs-12  col-sm-10 col-md-8">
                        	<input type="text" name="libelle" class="form-control" placeholder="Intitulé de l'expérience   " required="">
                        	</div>
               			</div>  
                		<div class="form-group">
                            <div class="col-xs-12  col-sm-10 col-md-8">
                                <input type="text" name="datedeb" class="form-control" placeholder="Date de début AA-MM-JJ" required="">
                        	</div>
                		</div>
                		<div class="form-group">
                       			<div class="col-xs-12  col-sm-10 col-md-8">
                                <input type="text" name="datefin" class="form-control" placeholder="Date de fin AA-MM-JJ" required="" >
                        		</div>
                		</div>
                		<div class="form-group">
                        	<div class="col-xs-12  col-sm-10 col-md-8">
                                <textarea name="description" class="form-control" rows="5"  placeholder="Description" required=""></textarea>
                       		</div>
                		</div>
                		<div class="form-group">
                        	<div class="col-xs-12  col-sm-10 col-md-8">
                                <input type="text" name="salaire" class="form-control"  required=""placeholder="Salaire">
                        	</div>
                		</div>
                		<h5>Organisation</h5>
                		<div class="form-group">
                         	<div class="col-xs-12  col-sm-10 col-md-8">
                                <input type="text" name="type_org" class="form-control" placeholder="Type" required="">
                        	</div>
                		</div>
                		<div class="form-group">
                        	<div class="col-xs-12  col-sm-10 col-md-8">
                                <input type="text" name="adresse_org" class="form-control" placeholder="Adresse" required="">
                        	</div>
                		</div>
                		<div class="form-group">
                         	<div class="col-xs-12  col-sm-10 col-md-8">
                                <input type="text" name="nom_org" class="form-control" placeholder="Nom" required="">
                        	</div>
                		</div>
                		<div class="form-group">
                         	<div class="col-xs-12  col-sm-10 col-md-8">
                                <input type="text" name="region_org" class="form-control" placeholder="Région" required="">
                        	</div>
                		</div>
                		<h5>Secteur d'activité</h5>
                		<div class="form-group">
                         	<div class="col-xs-12  col-sm-10 col-md-8">
                                <input type="text" name="Sec_act" class="form-control" placeholder="Secteur d'activité" required="">
                        	</div>
                		</div>
                		<h5>Compétences requises</h5>
                		<div class="form-group">
                         	<div class="col-xs-12  col-sm-10 col-md-8">
                                <input type="text" name="comp1" class="form-control" placeholder="Compétence 1" required=""/>
                        	</div>
                		</div>
                		<div class="form-group">
                         	<div class="col-xs-12  col-sm-10 col-md-8">
                                <input type="text" name="comp2" class="form-control" placeholder="Compétence 2" required=""/>
                        	</div>
                		</div>
                		<div class="form-group">
                         	<div class="col-xs-12  col-sm-10 col-md-8">
                                <input type="text" name="comp3" class="form-control" placeholder="Compétence 3" required=""/>
                        	</div>
                		</div>
                		<div class="form-group">
                        	<div class="col-xs-offset-4 col-sm-10 col-md-8">
                                <button type="submit" name="Form" class="btn btn-default btn-primary" value="Form2">
                                    Ajouter
                                <span class="glyphicon glyphicon-plus"></span>
                                </button>
                        	</div>
                		</div>
        			</form>
				</div>
			</div>
		</div>
	</div>
	<?php }?>
	<?php require_once "includes/footer.php"; ?>

</body>
</html>