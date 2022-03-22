<?php 
require_once"includes/functions.php";?>
<nav class="navbar navbar-default bg-dark navbar-expand-lg navbar-fixed-top">
    <div class="container">
        <div class="navbar-header ">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-target">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><span class="glyphicon glyphicon-home" style="color:white;"></span><span class="textwhite"> Accueil</span></a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse-target">
        	<?php 
        	if (isset($_SESSION['login'])&&($_SESSION['typeUser']=="Etudiant"))
        	{ 
                $MaRequete="SELECT id_etu FROM etudiant WHERE username='$_SESSION[login]'";
                $MonRs = getDb() -> query ($MaRequete);
                $Tuple = $MonRs->fetch();
        		print"<ul class='nav navbar-nav'>
                	<li><a href='profil.php?id=$Tuple[id_etu]'>Profil</a></li>";
            	print"</ul>";
            }
            if(isset($_SESSION['typeUser']))
            {
            	if($_SESSION['typeUser']=="Gestionnaire")
            	{
            		print"<ul class='nav navbar-nav'>";
                		print"<li><a href='adhesion.php'><span class='textwhite'>Adhesion</span></a></li>";
            		print"</ul>";
            	}
            }
            ?>
            <ul class='nav navbar-nav navbar-right'>;
                <?php 
                if (isset($_SESSION['login'])) 
                {
                 	print"<li class='dropdown'>";
                 	    print"<a href='#' class='dropdown-toggle' data-toggle='dropdown'>";
                     	    print"<span class='glyphicon glyphicon-user' style='color:white;'></span>";
                         	print"<span class='textwhite'>Bienvenue</span>";
                         	print"<span class='textwhite'> $_SESSION[login]</span>";
                         	print"<b class='caret' style='color:white;'></b>";
                        print"</a>";
                        print"<ul class='dropdown-menu'>";
                            print"<li><a href='logout.php'>Se déconnecter</a></li>";
                        print"</ul>";
                    print"</li>";
                }
                else 
                { 
                 	print"<li class='dropdown'>";
                        print"<a href='#'' class='dropdown-toggle' data-toggle='dropdown'>";
                            print"<span class='glyphicon glyphicon-user'></span> Non connecté <b class='caret'></b>";
                        print"</a>";
                        print"<ul class='dropdown-menu'>";
                            print"<li><a href='index.php'>Se connecter</a></li>";
                        print"</ul>";
                    print"</li>"; 
                }       
                ?>
            </ul>
        </div>
    </div>
</nav>

