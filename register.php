<?php 
//Comme on include ce fichier dans adhesion, pour éviter d'avoir des erreurs de sessions qui s'activent deux fois, on regarde d'abord le status de la session.
if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

require_once"includes/functions.php";
//Pour créer un compte, tous les champs doivent être renseignés
if (!empty($_POST['name']) and !empty($_POST['first_name']) and !empty($_POST['password']) and !empty($_POST['adress']) and !empty($_POST['mail']) and !empty($_POST['phone']) and !empty($_POST['sexe'])) 
{
    $name = escape($_POST['name']);
    $first_name = escape($_POST['first_name']);
    //On créé l'username en récupérant la 1ère lettre du prénom et le nom
    $login = strtolower(substr($first_name,0,1)) ."" .strtolower(substr($name,0,strlen($name))); 
    $password = escape($_POST['password']);
    $adress= escape($_POST['adress']);
    $mail = escape($_POST['mail']);
    $phone = escape($_POST['phone']);
    $sexe=escape($_POST['sexe']);
    $promo = escape($_POST['promotion']);
    
    //Si la variable suivante existe, cela signifie que c'est un gestionnaire qui créé un compte. La valeur de val_inscription est donc 1.
    if(isset($_SESSION['typeUser']))
    {
        $val_inscription=1;
    }
    else
        {
            $val_inscription = 0;
        }
}

$requete = getDb()->prepare("INSERT INTO etudiant(prenom_etu, nom_etu, password, username, adresse_postale, adresse_electronique, telephone, sexe, val_inscription, id_promo) VALUES (:prenom_etu, :nom_etu, :password, :username, :adresse_postale, :adresse_electronique, :telephone, :sexe, :val_inscription, :id_promo)");

if (!empty($login))
    {
        $booleen=doublonEtudiant('etudiant',$login);
        if($booleen==true)
        {
            $requete->bindValue('prenom_etu', $first_name, PDO::PARAM_STR);
            $requete->bindValue('nom_etu', $name, PDO::PARAM_STR);
            $requete->bindValue('username', $login, PDO::PARAM_STR);
            $requete->bindValue('password', $password, PDO::PARAM_STR);
            $requete->bindValue('adresse_postale', $adress, PDO::PARAM_STR);
            $requete->bindValue('adresse_electronique', $mail, PDO::PARAM_STR);
            $requete->bindValue('telephone', $phone, PDO::PARAM_STR);
            $requete->bindValue('sexe', $sexe, PDO::PARAM_STR);
            $requete->bindValue('val_inscription', $val_inscription, PDO::PARAM_INT);
            $requete->bindValue('id_promo', $promo, PDO::PARAM_INT);
            $requete->execute();
            if (!isset($_SESSION['typeUser']))
            {
            redirect('index.php');
            }
            else
            {
            redirect('adhesion.php');
            }
        }   
        if($booleen==false&&isset($_SESSION['typeUser']))
        {
            redirect('adhesion.php');
        }
        if($booleen==false&&!isset($_SESSION['typeUser']))
        {
            redirect('register.php');
        }
        
       
    }
?>

<html>
<?php include('includes/head.php');?>

<body>
    <?php 
    if (!isset($_SESSION['login']))
    {
        include('includes/header.php');
        print"<div class='container marginTop'>";
            print"<h2 class='text-center'>Créer un compte</h2>";
    }
	else
    {
        print"<div class='container'>";
    }?>
        <div class="well well-w-70">
            <form class='form-signin form-horizontal' role='form' action='register.php' method='POST'>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <input type="text" name="name" class="form-control" placeholder="Entrez votre nom" required="">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <input type="text" name="first_name" class="form-control" placeholder="Entrez votre prénom" required="">
                    </div>
                </div>

                <div class="form-group">
                	<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                		<input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe" required="">
                	</div>
                </div>

                <div class="form-group">
                	<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                		<input type="text" name="adress" class="form-control" placeholder="Entrez votre adresse postale" required="">
                	</div>
                </div>

                <div class="form-group">
                	<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                		<input type="text" name="mail" class="form-control" placeholder="Entrez votre mail" required="">
                	</div>
                </div>

                <div class="form-group">
                	<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                		<input type="text" name="phone" class="form-control" placeholder="Entrez votre téléphone" required="">
                	</div>
                </div>

                <div class='form-group text-center'>
                	<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                		<?php 
                		echo"Sélectionner une promotion";print"<br/>";
                		$MaRequete="SELECT * FROM promotion";
						$MonRs = getDb() -> query( $MaRequete );
						echo "<select name='promotion'required='true'>";
						echo "<option selected>-- Choisir une promotion--</option>";
						while ($Tuple = $MonRs ->fetch())
						{ echo "<option value='$Tuple[id_promo]'>$Tuple[annee_debut] $Tuple[annee_fin]</option>";
						}
						echo "</select><br/>";?>
					</div>
				</div>


                <div class="form-group text-center">
                	<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
               			<input type="radio" id="Homme" name="sexe" value="Homme" required="">Homme
            			<input type="radio" id="Femme" name="sexe" value="Femme" required="">Femme
                	</div>
                </div>

                <div class="form-group text-center">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <button type="submit" name="Form" class="btn btn-default btn-primary" value="Etu">
                            Créer un compte
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </div>
                </div>

                <?php
                if(isset($booleen))
                {
                    if($booleen==false)
                    {
                    print"<div class='text-center'>";
                        print"Ce nom d'utilisateur appartient déjà à un compte";
                    print"</div>";
                    }
                }?>
            </form>
        </div>
    </div>
	<?php require_once "includes/footer.php"; ?>
</body>
</html>