<?php
session_start();
require_once"includes/functions.php";
if(isset($_POST['Form']))
{
    //Cas où le premier formulaire est complété (formulaire d'adhésion)
    if ($_POST['Form']=="Accepter la demande d'adhésion")
    {
        //Si un étudiant est sélectionné, alors sa valeur dans val_inscription passe à 1.
        if(isset($_POST['etu']))
        {
            foreach($_POST['etu'] as $new)
            {
                $stmt = getDb() ->prepare("UPDATE etudiant SET val_inscription=1 WHERE id_etu='$new'");
                $stmt->execute();
                redirect("adhesion.php");
            }
        }
        //Si le bouton "Tout sélectionner" est coché, tous les étudiants en demande d'adhésion sont acceptés.
        if (isset($_POST['SelectAll']))
        {
            $stmt = getDb()->prepare("UPDATE etudiant SET val_inscription=1 WHERE val_inscription=0");
            $stmt->execute();
            redirect("adhesion.php");
        }
    }
    //Cas où le deuxième formulaire de la page est complété -> création d'une nouvelle promotion.
    else if ($_POST['Form']=="Promo")
    {
        $libPromo = escape($_POST['nomPromo']);
        $anneeDeb= escape($_POST['anneedeb']);
        $anneeFin =escape($_POST['anneefin']);
        $requete =  getDb() -> prepare("INSERT INTO promotion(libelle_promo,annee_debut,annee_fin) VALUES (:libelle_promo, :annee_debut, :annee_fin)");
        $requete ->bindValue('libelle_promo', $libPromo, PDO::PARAM_STR);
        $requete ->bindValue('annee_debut', $anneeDeb, PDO::PARAM_STR);
        $requete ->bindValue('annee_fin', $anneeFin, PDO::PARAM_STR);
        $requete->execute();
    }  
}?>
<!DOCTYPE HTML>
<html>
<?php include('includes/head.php');?>
<body>
    <?php include('includes/header.php');?>
    <div class="container marginTop ">
        <!-- Premier formulaire de la page. Formulaire d'adhésion !-->
        <h1 class="text-center ">Liste ahésion</h1>
        <div class="well well-w-70">
            <form method="POST" action="adhesion.php">
                <article>
                    <div class="well well-bg-white text-center ">
                        <?php
                        //On sélectionne tous les étudiants de notre base de données
                        $RequeteEtudiants="SELECT * FROM etudiant WHERE val_inscription=0 Order By nom_etu";
                        $etudiant = getDb() -> query($RequeteEtudiants);
                        foreach($etudiant as $etu)
                        {?>
                        <h3>
                            <input  type="checkbox" id="<?=$etu['nom_etu'];?>" name="etu[]" value="<?= $etu['id_etu'];?>"/>
                            <label for="<? $etu['nom_etu'];?>"> 
                                    <?= $etu['nom_etu']; print " "?><?= $etu['prenom_etu']?>    
                            </label>
                        </h3>
                        <span>
                            Promotion : <?php
                            $idP = $etu['id_promo'];
                            $Promo = getDb()->query("SELECT * FROM promotion WHERE id_promo=$idP");
                            $Promo1=$Promo ->fetch();
                            echo$Promo1['libelle_promo'];?>
                        </span>  
                        <?php }?>
                    </div><br/>
                </article>
            
                <div class="form-group text-center">
                    <input type="checkbox" name="SelectAll"/>Tout sélectionner
                </div>
                
                <div class="form-group text-center">
                    <button type="submit" name="Form" class="btn btn-default btn-primary" value="Accepter la demande d'adhésion">Accepter la demande d'adhésion
                        <span class="glyphicon glyphicon-ok"></span> 
                    </button>
                </div>
            </form>
        </div><br/>  
    </div>
    
    <!-- Deuxième formulaire : Formulaire pour créer une promotion !-->
    <div class="container">
        <h1 class="text-center">Créer une promotion</h1>
        <div class="well well-w-70">
            <form class="form-signin form-horizontal" role="form" action="adhesion.php" method="POST">
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <input type="text" name="nomPromo" class="form-control" placeholder="Nom de la promotion" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <input type="text" name="anneedeb" class="form-control" placeholder="Année de début" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <input type="text" name="anneefin" class="form-control" placeholder="Année de fin" required="">
                    </div>
                </div>
                <div class="form-group text-center">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <button type="submit" name="Form" value="Promo" class="btn btn-default btn-primary">
                                Créer une promotion
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Troisième formulaire de la page : Formulaire de création de comptes!-->
    <!-- Pour ne pas dupliquer de code, on include 'register.php' et on modifie le traitement du formulaire afin que val_inscription passe à 1 (car le compte est créé par le gestionnaire) et non à 0 (comme si un étudiant crééait un compte)!-->
    <h1 class="text-center">Ajouter un étudiant</h1>
    <?php include('register.php');?>

    
   <?php require_once "includes/footer.php"; ?>
</body>
</html>

