<?php
session_set_cookie_params($lifetime);
session_start();

$token=$_GET['token'];



?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >

    <title>UO - Servizi gionalieri</title>
<?php 
require_once('./req.php');

the_page_title();

/*if ($_SESSION['test']==1) {
  require_once ('./conn_test.php');
} else {*/
require_once('./conn.php');
//}
?> 






</head>

<body>

<?php 
require_once('./navbar_up.php');
$name=dirname(__FILE__);
?>

<div class="container-fluid" style="padding-top:195px;">

<h3> Provenienza dei dati </h3>

<h5> Testate e percorsi </h5>

assterritorio@amiu.genova.it

<h5> Sportelli</h5>

Trapasso / 

<h5> Personale </h5>



</div>

<?php
require_once('req_bottom.php');
require_once('./footer.php');
?>



</body>

</html>