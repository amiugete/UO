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





<script>
  function utScelta(val) {
    document.getElementById('open_ut').submit();
  }
  function dataScelta(val) {
    console.log(val);
    console.log(document.getElementById('js-date'));
    document.getElementById('open_ut').submit();
  }

</script>

<div class="rfix">
<form class="row" name="open_ut" method="post" id="open_ut" autocomplete="off" action="servizi_giornalieri.php?token=<?php echo $token;?>" >




<?php //echo $username;?>

<div class="form-group col-lg-4">
  <select class="selectpicker show-tick form-control" 
  data-live-search="true" name="ut" id="ut" onchange="utScelta(this.value);" required="">
  
  <?php 
  if ($_POST['ut']) {
    $query0='SELECT DESC_UO FROM anagr_uo WHERE ID_UO =:u1';
    $result0 = oci_parse($oraconn, $query0);

  oci_bind_by_name($result0, ':u1', $_POST['ut']);

  oci_execute($result0);

  while($r0 = oci_fetch_assoc($result0)) {
  ?>    
          <option name="ut" value="<?php echo $_POST['ut'];?>" ><?php echo $r0['DESC_UO']?></option>
  <?php } 
  oci_free_statement($result0);
  } else{
  ?>
    <option name="ut" value="NO">Seleziona una UT</option>
  
  
  <?php            
  }

  $query1='SELECT duu.ID_UO, au2.DESC_UO 
  FROM UNIOPE.DAT_USER_UO duu
  JOIN UNIOPE.ANAGR_USERS au ON au.ID_USER = duu.ID_USER
  JOIN UNIOPE.ANAGR_UO au2 ON au2.ID_UO = duu.ID_UO 
  WHERE au.USERNAME = :u1
  ORDER BY DESC_UO';

  //echo "<br>". $query;

  $result1 = oci_parse($oraconn, $query1);

  oci_bind_by_name($result1, ':u1', $username);

  oci_execute($result1);

  while($r1 = oci_fetch_assoc($result1)) {
  ?>    
          <option name="ut" value="<?php echo $r1['ID_UO'];?>" ><?php echo $r1['DESC_UO']?></option>
  <?php } 
  oci_free_statement($result1);
  ?>

  </select>  
  <!--small>L'elenco delle piazzole..  </small-->        
</div>











<div class="form-group col-lg-4 ">
  
<!--label for="data_inizio" >Data inizio (AAAA-MM-GG) </label-->                 
  <?php
  if ($_POST['data_inizio']){
    $datetime = DateTime::createFromFormat('d/m/Y', $_POST['data_inizio']);
  } else {
    $datetime = DateTime::createFromFormat('d/m/Y', date("d/m/Y/"));
  }
  //echo $datetime->format('D');
  ?>

  <input type="text" class="form-control" name="data_inizio" id="js-date" onchange="dataScelta(this.value);"
  <?php if ($_POST['data_inizio']){
    echo 'value="'.$_POST['data_inizio'].'"';
  }
  ?>
  required>
  <!--div class="input-group-addon" id="js-date" >
    <span class="glyphicon glyphicon-th"></span>
  </div-->
</div> 

<?php 
  if ($_POST['ut']) {

    $query2="SELECT DISTINCT osu.ORDINE, aspu.ID_SERVIZIO, as2.DESC_SERVIZIO 
    FROM ANAGR_SER_PER_UO aspu
    JOIN ANAGR_SERVIZI as2 ON as2.ID_SERVIZIO = aspu.ID_SERVIZIO 
    LEFT JOIN ORDINE_SERVIZI_UO osu ON osu.ID_SERVIZIO=aspu.ID_SERVIZIO AND osu.ID_UO = aspu.ID_UO 
    WHERE aspu.DTA_DISATTIVAZIONE > TO_DATE(:u0 , 'DD/MM/YYYY') 
    AND aspu.ID_UO = :u1
    ORDER BY 1";

    //echo "<br>". $query;

    $result2 = oci_parse($oraconn, $query2);

    oci_bind_by_name($result2, ':u0', substr($_POST['data_inizio'], 4));
    oci_bind_by_name($result2, ':u1', $_POST['ut']);

    oci_execute($result2);
    
    ?> 
    <div  name="conferma2" id="conferma2" class="form-group col-lg-4 ">
    <select class="selectpicker show-tick form-control" 
      data-live-search="true" name="servizio" id="servizio" onchange="location = this.value;" >
    <option name="servizio" value="NO">Zoom al servizio</option>

    <?php
    while($r2 = oci_fetch_assoc($result2)) {
      ?> 
      
         
        <option name="servizio" value="#link-<?php echo $r2['ID_SERVIZIO'] ?>"><?php echo $r2['DESC_SERVIZIO']?></option>
  


      <?php 
    }

  ?>
    </select>  
    </div>
  <?php
  } else {
?>

<div  name="conferma2" id="conferma2" class="form-group col-lg-4 ">
<!--input type="submit" name="submit" id=submit class="btn btn-info" value="Recupera dettagli piazzola"-->
<!--button type="submit" class="btn btn-info">
Recupera dettagli piazzola
</button-->

</div>

<?php } ?>

</form>
<hr>
</div>
<div class="container-fluid" style="padding-top:195px;">

<?php
if ($_POST['ut']) {

  // includo tutto dentro un accordion
  ?>
  <div class="accordion" id="servizi">
  <?php
  
  oci_execute($result2);
  while($r2 = oci_fetch_assoc($result2)) {
  ?> 
  
  <div class="accordion-item">
    <div class="anchor" id="link-<?php echo $r2['ID_SERVIZIO'] ?>" ></div>
    <h2 class="accordion-header" id="panel-heading-<?php echo $r2['ID_SERVIZIO'] ?>">
      <button class="accordion-button bg-primary text-light select-light" type="button" data-bs-toggle="collapse" data-bs-target="#panel-<?php echo $r2['ID_SERVIZIO'] ?>"
       aria-expanded="true" aria-controls="panel-<?php echo $r2['ID_SERVIZIO'] ?>">
       <?php echo $r2['DESC_SERVIZIO'] ?>
      </button>
    </h2>
    <div id="panel-<?php echo $r2['ID_SERVIZIO'] ?>" class="accordion-collapse collapse show" aria-labelledby="panel-<?php echo $r2['ID_SERVIZIO'] ?>">
      <div class="accordion-body">
          

      <div class="accordion" id="accordion-<?php echo $r2['ID_SERVIZIO'] ?>">

      <?php
      
      // ora carico le testate per quel servizio
      /*$query3= "SELECT as2.DESC_SERVIZIO, aspu.ID_SER_PER_UO, aspu.ID_PERCORSO,
                aspu.DESCRIZIONE, aspu.FREQUENZA_NEW, at2.CODICE_TURNO, 
                at2.DESCR_ORARIO, aspu.DURATA, as3.DESC_SQUADRA 
                FROM ANAGR_SER_PER_UO aspu 
                JOIN ANAGR_SERVIZI as2 ON as2.ID_SERVIZIO = aspu.ID_SERVIZIO 
                JOIN ANAGR_TURNI at2 ON at2.ID_TURNO = aspu.ID_TURNO 
                JOIN ANAGR_SQUADRE as3 ON as3.ID_SQUADRA = aspu.ID_SQUADRA 
                WHERE aspu.DTA_DISATTIVAZIONE > TO_DATE(:s0 , 'DD/MM/YYYY')  
                AND aspu.ID_UO = :s1 and aspu.ID_SERVIZIO = :s2 
                ORDER BY at2.DESCR_ORARIO";
      */

      $query3_parte_comune="SELECT * FROM (
                  SELECT as2.DESC_SERVIZIO, aspu.ID_SER_PER_UO, aspu.ID_PERCORSO,
                  aspu.DESCRIZIONE, aspu.FREQUENZA_NEW,
                  CASE 
                    WHEN substr(aspu.FREQUENZA_NEW,0,1)='S' 
                    THEN  substr(aspu.FREQUENZA_NEW,
                    1+to_number(to_char(to_date(:s0,'dd/mm/yyyy'), 'D')),
                    1)
                    WHEN substr(aspu.FREQUENZA_NEW,0,1)='M' AND 
                    substr(aspu.FREQUENZA_NEW,2) LIKE 
                    '%' || to_char(to_date(:s0,'dd/mm/yyyy'), 'W')||to_char(to_date(:s0,'dd/mm/yyyy'), 'D') || '%' 
                    THEN to_char(1)
                    ELSE to_char(0)
                  END IN_FREQ,
                  at2.CODICE_TURNO, 
                  at2.DESCR_ORARIO, aspu.DURATA, as3.DESC_SQUADRA 
                  FROM ANAGR_SER_PER_UO aspu 
                  JOIN ANAGR_SERVIZI as2 ON as2.ID_SERVIZIO = aspu.ID_SERVIZIO 
                  JOIN ANAGR_TURNI at2 ON at2.ID_TURNO = aspu.ID_TURNO 
                  JOIN ANAGR_SQUADRE as3 ON as3.ID_SQUADRA = aspu.ID_SQUADRA 
                  WHERE aspu.DTA_DISATTIVAZIONE >= TO_DATE(:s0,'DD/MM/YYYY')
                  AND aspu.ID_UO = :s1 and aspu.ID_SERVIZIO = :s2 
                  AND aspu.FREQUENZA_NEW !='S0000000'";
                  
      $query3_previste="and aspu.ID_SQUADRA != 15)
                WHERE IN_FREQ =  1
                ORDER BY DESCR_ORARIO";
      
      $query3=  $query3_parte_comune . ' '.  $query3_previste;
      //echo $query3;
      $result3 = oci_parse($oraconn, $query3);

      oci_bind_by_name($result3, ':s0', substr($_POST['data_inizio'], 4));
      oci_bind_by_name($result3, ':s1', $_POST['ut']);
      oci_bind_by_name($result3, ':s2', $r2['ID_SERVIZIO']);
    
      oci_execute($result3);
      if (oci_fetch($result3)){
        oci_execute($result3);
        ?>
        <div class="accordion-item">
        
        <h2 class="accordion-header" id="panel-heading-<?php echo $r2['ID_SERVIZIO'] ?>-prev">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panel-<?php echo $r2['ID_SERVIZIO'];?>-prev"
          aria-expanded="true" aria-controls="panel-<?php echo $r2['ID_SERVIZIO'] ?>-prev">
          <i class="fa-regular fa-calendar-check"></i> Percorsi previsti per oggi
          </button>
        </h2>
        <div id="panel-<?php echo $r2['ID_SERVIZIO'];?>-prev" class="accordion-collapse collapse show" aria-labelledby="panel-<?php echo $r2['ID_SERVIZIO'];?>-prev">
        <div class="accordion-body">
        <?php
        echo "<ul>";
        while($r3 = oci_fetch_assoc($result3)) {
          ?>
          <li>
          <?php include("./righe_servizi_giornalieri.php"); ?> 
          </li>
          <?php
        }
        echo "</ul>";
        ?>
      </div></div></div>
      <?php
      }
      oci_free_statement($result3);
      
      //****************************************************************************************************************************** */
      $query3_non_previste="and aspu.ID_SQUADRA != 15)
                WHERE IN_FREQ =  0
                ORDER BY DESCR_ORARIO";
      
      $query3=  $query3_parte_comune . ' '.  $query3_non_previste;

      $result3 = oci_parse($oraconn, $query3);

      oci_bind_by_name($result3, ':s0', substr($_POST['data_inizio'], 4));
      oci_bind_by_name($result3, ':s1', $_POST['ut']);
      oci_bind_by_name($result3, ':s2', $r2['ID_SERVIZIO']);

      oci_execute($result3);
      if (oci_fetch($result3)){
        oci_execute($result3);
        ?>
        <div class="accordion-item">
        <h2 class="accordion-header" id="panel-heading-<?php echo $r2['ID_SERVIZIO'] ?>-nonprev">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panel-<?php echo $r2['ID_SERVIZIO'];?>-nonprev"
          aria-expanded="true" aria-controls="panel-<?php echo $r2['ID_SERVIZIO'] ?>-nonprev">
          <i class="fa-regular fa-calendar-xmark"></i> Percorsi non previsti per oggi
          </button>
        </h2>
        <div id="panel-<?php echo $r2['ID_SERVIZIO'];?>-nonprev" class="accordion-collapse collapse" aria-labelledby="panel-<?php echo $r2['ID_SERVIZIO'];?>-nonprev">
        <div class="accordion-body">
        <?php
        
        echo "<ul>";
        while($r3 = oci_fetch_assoc($result3)) {
          ?>
          <li>
          <?php include("./righe_servizi_giornalieri.php"); ?> 
          </li>
          <?php
        }
        echo "</ul>";
        ?>
      </div></div></div>
      <?php
      }
      oci_free_statement($result3);
      
      //******************************************************************************************************************************
      $query3_parte_comune1="SELECT * FROM (
        SELECT as2.DESC_SERVIZIO, aspu.ID_SER_PER_UO, aspu.ID_PERCORSO,
        aspu.DESCRIZIONE, aspu.FREQUENZA_NEW,
        '0' AS IN_FREQ,
        at2.CODICE_TURNO, 
        at2.DESCR_ORARIO, aspu.DURATA, as3.DESC_SQUADRA 
        FROM ANAGR_SER_PER_UO aspu 
        JOIN ANAGR_SERVIZI as2 ON as2.ID_SERVIZIO = aspu.ID_SERVIZIO 
        JOIN ANAGR_TURNI at2 ON at2.ID_TURNO = aspu.ID_TURNO 
        JOIN ANAGR_SQUADRE as3 ON as3.ID_SQUADRA = aspu.ID_SQUADRA 
        WHERE aspu.DTA_DISATTIVAZIONE >= TO_DATE(:s0,'DD/MM/YYYY')
        AND aspu.ID_UO = :s1 AND aspu.ID_SERVIZIO = :s2 
        AND aspu.FREQUENZA_NEW ='S0000000'";

      $query3_nofreq=" )
                ORDER BY IN_FREQ DESC, DESCR_ORARIO";
      
      $query3=  $query3_parte_comune1 . ' '.  $query3_nofreq;

      //echo $query3;
      
      $result3 = oci_parse($oraconn, $query3);

      oci_bind_by_name($result3, ':s0', substr($_POST['data_inizio'], 4));
      oci_bind_by_name($result3, ':s1', $_POST['ut']);
      oci_bind_by_name($result3, ':s2', $r2['ID_SERVIZIO']);
      
      oci_execute($result3);
      if (oci_fetch($result3)){
        oci_execute($result3);
        ?>
        <div class="accordion-item">
        <h2 class="accordion-header" id="panel-heading-<?php echo $r2['ID_SERVIZIO'] ?>-nofreq">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panel-<?php echo $r2['ID_SERVIZIO'];?>-nofreq"
          aria-expanded="true" aria-controls="panel-<?php echo $r2['ID_SERVIZIO'] ?>-nofreq">
          <i class="fa-regular fa-calendar"></i> Percorsi senza frequenza
          </button>
        </h2>
        <div id="panel-<?php echo $r2['ID_SERVIZIO'];?>-nofreq" class="accordion-collapse collapse show" aria-labelledby="panel-<?php echo $r2['ID_SERVIZIO'];?>-nofreq">
        <div class="accordion-body">
        <?php
        echo "<ul>";
        while($r3 = oci_fetch_assoc($result3)) {
          ?>
          <li>
          <?php include("./righe_servizi_giornalieri.php");?>
          ?> 
          </li>
          <?php
      }
      echo "</ul>";
      ?>
      </div></div></div>
      <?php
      }
      oci_free_statement($result3);
      

      //******************************************************************************************************************************
      $query3_vis="and aspu.ID_SQUADRA = 15)
                ORDER BY IN_FREQ DESC, DESCR_ORARIO";
      
      $query3=  $query3_parte_comune . ' '.  $query3_vis;

      //echo $query3;
      
      $result3 = oci_parse($oraconn, $query3);

      oci_bind_by_name($result3, ':s0', substr($_POST['data_inizio'], 4));
      oci_bind_by_name($result3, ':s1', $_POST['ut']);
      oci_bind_by_name($result3, ':s2', $r2['ID_SERVIZIO']);
      
      oci_execute($result3);
      if (oci_fetch($result3)){
        oci_execute($result3);
        ?>
        <div class="accordion-item">
        <h2 class="accordion-header" id="panel-heading-<?php echo $r2['ID_SERVIZIO'] ?>-vis">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panel-<?php echo $r2['ID_SERVIZIO'];?>-vis"
          aria-expanded="true" aria-controls="panel-<?php echo $r2['ID_SERVIZIO'] ?>-vis">
          <i class="fa-regular fa-eye"></i> Percorsi in sola visualizzazione
          </button>
        </h2>
        <div id="panel-<?php echo $r2['ID_SERVIZIO'];?>-vis" class="accordion-collapse collapse" aria-labelledby="panel-<?php echo $r2['ID_SERVIZIO'];?>-vis">
        <div class="accordion-body">
        <?php
        echo "<ul>";
        while($r3 = oci_fetch_assoc($result3)) {
          ?>
          <li>
          <?php include("./righe_servizi_giornalieri.php");
          if ( $r3['IN_FREQ']==1){
            echo '<i class="fa-regular fa-calendar-check" title="Previsto per oggi"></i>';
          } else if ( $r3['IN_FREQ']==0) {
            echo '<i class="fa-regular fa-calendar-xmark" title="Non previsto per oggi"></i>';
          } else {
            echo '<i class="fa-regular fa-calendar-exclamation" title="Problema con la lettura della frequenza. Contattare assterritorio@amiu.genova.it"></i>';
          }
          ?> 
          </li>
          <?php
        }
        echo "</ul>";
      ?>
      </div></div></div>

      
      
      <?php
      }
      oci_free_statement($result3);

      ?>
      </div> <!--accordion interno al servizio-->
        

      </div>
    </div>
  </div>
 
  <?php
  }
  oci_free_statement($result2);
 

  // chiudo l'intero accordion 
  ?> 
  
  </div>
  
  <?php
} else {
  echo "<h4>E' necessario scegliere una UT</h4>";

}


?>









</div>








<?php
require_once('req_bottom.php');
require_once('./footer.php');
?>

<script>

function dateFormatter() {
  moment.locale('it');
  return moment().format('D DD/MM/yyyy')
}

moment.locale('it');



<?php if ($_POST['data_inizio']){
?>
document
$(document).ready(function() {
    $('#js-date').datepicker({
        format: "D dd/mm/yyyy",
        language: 'it',
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });
});
<?php } else { ?>
$(document).ready(function() {
    $('#js-date').datepicker({
        format: "D dd/mm/yyyy",
        language: 'it',
        //format: moment().format('D DD/MM/yyyy'),
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    }).datepicker("setDate",'now');;
});

<?php }  ?>



</script>








</body>

</html>