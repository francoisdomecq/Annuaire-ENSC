
<?php include('includes/head.php');
include('includes/functions.php');?>
<?php
  // Initialiser la session
  session_start();
  unset($_SESSION['login']);
  // Détruire la session.
  if(session_destroy())
  {
    // Redirection vers la page de connexion
    redirect('index.php');
  }
?>
