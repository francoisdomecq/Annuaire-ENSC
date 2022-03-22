<?php 
if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}
require_once"includes/functions.php";

//L'utilisateur est obligé de remplir tous les champs du formulaire
if (!empty($_POST['login']) and !empty($_POST['password']) and!empty($_POST['typeUser'])) 
{
    $login = escape($_POST['login']);
    $password = escape($_POST['password']);
    $typeUser = $_POST['typeUser'];
    
    //Si l'utilisateur souhaite se connecter en tant que gestionnaire, nous faisons une requête dans la table gestionnaire
    if($typeUser=="Gestionnaire")
    {
    	$stmt = getDb()->prepare('select * from gestionnaire where username=? and password=?');
    	$stmt->execute(array($login, $password));
    	if ($stmt->rowCount() == 1) 
    	{
        	$_SESSION['typeUser'] = $typeUser;
        	$_SESSION['login'] = $login;
            redirect("index.php");
    	}
    	else
    	{
    	    $_SESSION['error'] = "Utilisateur non reconnu";
    	    redirect('index.php');
    	}
    	
   	}
   	 //Si l'utilisateur souhaite se connecter en tant qu'étudiant, nous faisons une requête dans la table étudiant
    else if($typeUser=="Etudiant")
    {
        $stmt = getDb()->prepare("SELECT * from etudiant where username=? and password=? and val_inscription=1");
        $stmt->execute(array($login, $password));
        if ($stmt->rowCount() == 1)
            {
                $_SESSION['typeUser']= $typeUser;
                $_SESSION['login'] = $login;
                redirect("index.php");
            }
            
        	else
    	{
    	    $_SESSION['error'] = "Utilisateur non reconnu";
    	    redirect('index.php');
    	}
    } 
}   
?>
    <!-- nous n'avons pas mis de balises <html> et <body> car l'utilisateur n'est jamais redirigé vers login.php. Pour se connecter, l'utilisateur est redirigé vers index.php où login.php est included!-->
   
    <div class="container text-center marginTop">
                <img src="images/logo_ensc.png" alt="logoensc" width="20%" height="20%" />
    </div><br/> 
    
     <?php if(isset($_SESSION['error']))
	    {
	        print"<div class='container'>";
	            print"<div class='col-md-offset-3 alert alert-danger text-center' style='width:50%;'>";
                    print"<strong>Erreur !</strong> $_SESSION[error]";
                print"</div>";
            print"</div>";

	    }?>
    
    <!-- Formulaire de connexion!-->
    <div class="container">
        <div class="well text-center">
            <h2>Connexion</h2><br/>
            <form class="form-signin form-horizontal" role="form" action="login.php" method="POST">
                <div class="form-group">
                    <div class="col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                        <input type="text" name="login" class="form-control text-center" placeholder="Entrez votre login" required="" autofocus="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                        <input type="password" name="password" class="form-control text-center" placeholder="Entrez votre mot de passe" required="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-1 col-xs-offset-3 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                	   <input type="radio" id="Etudiant" name="typeUser" value="Etudiant" required="">Etudiant
                	   <input type="radio" id="gestionnaire" name="typeUser" value="Gestionnaire" required="">Gestionnaire
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-6 col-xs-offset-3 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                        <button type="submit" class="btn btn-default btn-primary" >
                            <span class="glyphicon glyphicon-log-in"></span> <span>Se connecter</span>
                        </button>
                    </div>
                </div>   
                <a href="register.php">Pas de compte ? Inscrivez-vous !</a>     
            </form>
        </div>
    </div>
            



	<?php require_once "includes/footer.php"; ?>

