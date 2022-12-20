<?php

require_once('./token.php');

// Faccio il controllo su SIT

?>


<div class="banner"> <div id="banner-image"></div> 
<h3>  <a class="navbar-brand link-light" href="#">
    <img class="pull-left" src="img\amiu_small_white.png" alt="SIT" width="85px">
    <span>Unità Operative 2.0</span> 
  </a> 
</h3>
</div>
<nav class="navbar navbar-inverse navbar-fixed-top navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <!--a class="navbar-brand" href="#">
    <img class="pull-left" src="img\amiu_small_white.png" alt="SIT" width="85px">
    </a-->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="navbar-nav ms-auto flex-nowrap">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="http://webamiu:96/UO_09/Home.aspx?token=<?php echo $token;?>">Home UO 1.0</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./servizi_giornalieri.php?token=<?php echo $token;?>">Servizi del giorno</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./help.php?token=<?php echo $token;?>">Provenienza dei dati</a>
        </li>

        <?php if ($id_role_SIT > 0) { ?>
        <li class="nav-item">
          <a class="nav-link" href="./piazzole.php">Modifica piazzole</a>
        </li>
       
        <!--li class="nav-item">
          <a class="nav-link" href="./ordini.php"> Modifica percorsi</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="./chiusura.php">Chiusura interventi</a>
        </li-->
        <?php } ?>
        
      </ul>
      <div class="collapse navbar-collapse flex-grow-1 text-right" id="myNavbar">
        <ul class="navbar-nav ms-auto flex-nowrap">
          <i class="fas fa-user"></i>Connesso come <?php echo $username;?> (<?php echo $role_SIT;?>)
        </ul>
      </div>
  </div>
</nav>
