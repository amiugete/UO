<?php 

echo '<div class="row small" id="sezione_'.$r3['ID_SER_PER_UO'].'">';
echo '<div class="col-lg-4">';
echo $r3['ID_PERCORSO'] . ' - '. $r3['DESCRIZIONE'] .' ('.$r3['DESC_SQUADRA'].')' . ' ' .$r3['DESCR_ORARIO'] ; 
echo '</div>'; // chiudo la col

echo '<div class="col-lg-4" id="sezione_sportello_'.$r3['ID_SER_PER_UO'].'">';

//echo $r3['ID_SER_PER_UO'];

/*$query4= "SELECT DISTINCT hs.SPORTELLO, ams.DESCRIZIONE 
FROM HIST_SERVIZI hs 
LEFT JOIN ANAGR_MEZZI_SPORTELLO ams ON trim(leading '0' from ams.SPORTELLO) = hs.SPORTELLO
WHERE ID_SER_PER_UO = :s1 
AND DTA_SERVIZIO = TO_DATE(:s2,'DD/MM/YYYY')";
*/

// così gestisco anche il caso di 2 sportelli
$query4="SELECT ss.SPORTELLO, ams.DESCRIZIONE
FROM (
SELECT distinct trim(regexp_substr(SPORTELLO, '[^ ]+', 1, level )) as SPORTELLO  FROM
(
SELECT DISTINCT hs.SPORTELLO 
   FROM HIST_SERVIZI hs
   WHERE ID_SER_PER_UO = :s1 
AND DTA_SERVIZIO = TO_DATE(:s2,'DD/MM/YYYY')
)
connect by regexp_substr(SPORTELLO, '[^ ]+', 1, level) is not NULL
) ss
LEFT JOIN ANAGR_MEZZI_SPORTELLO ams ON trim(leading '0' from ams.SPORTELLO) = ss.SPORTELLO";

$result4 = oci_parse($oraconn, $query4);

oci_bind_by_name($result4, ':s1', $r3['ID_SER_PER_UO']);
oci_bind_by_name($result4, ':s2', substr($_POST['data_inizio'], 4));
oci_execute($result4);
echo '<div class="input-group mb-6">';
$check_sportello=0;
while($r4 = oci_fetch_assoc($result4)) {
  
    if ($r4['SPORTELLO']!='') {
        $check_sportello=1;
        ?>
<form class="row g-1" autocomplete="off" id="sportello_rm_<?php echo $r3['ID_SER_PER_UO'];?>_<?php echo $r4['SPORTELLO'];?>" action="" onsubmit="return removeSp_<?php echo $r3['ID_SER_PER_UO'];?>_<?php echo $r4['SPORTELLO'];?>();">
<div class="col-auto">
  <?php
    }
    echo $r4['SPORTELLO'];
    if ($r4['DESCRIZIONE']) {
        echo ' <i class="fa-solid fa-truck" style="color: green;" title="'.$r4['DESCRIZIONE'].'" ></i>';
    } else if ($r4['SPORTELLO']!='' && $r4['DESCRIZIONE']=='') {
        echo ' <i class="fa-solid fa-truck" style="color: red;" title="Descrizione non presente sul DB" ></i>';
    }
    if ($r4['SPORTELLO']!='') {
    ?>
    
<input autocomplete="false" id="r_id_<?php echo $r3['ID_SER_PER_UO'];?>_<?php echo $r4['SPORTELLO'];?>" type="text" value="<?php echo $r3['ID_SER_PER_UO'];?>" style="display:none;">
<input autocomplete="false" id="r_data_sp_<?php echo $r3['ID_SER_PER_UO'];?>_<?php echo $r4['SPORTELLO'];?>" type="text" value="<?php echo substr($_POST['data_inizio'], 4);?>" style="display:none;">
<input autocomplete="false" id="r_sp_<?php echo $r3['ID_SER_PER_UO'];?>_<?php echo $r4['SPORTELLO'];?>" type="text" value="<?php echo $r4['SPORTELLO'];?>" style="display:none;">
</div>
<div class="col-auto">
<button type="submit" class="btn btn-danger btn-sm" disabled>
<i class="fa-solid fa-xmark" title="Salva sportello" ></i> 
</button>
</div>
</form>

    <?php 
    }
}
oci_free_statement($result4);


/*if($check_sportello==1){

} else if($check_sportello==0){*/
?>

<script>
function showResult_<?php echo $r3['ID_SER_PER_UO'];?>(str) {
  if (str.length<3) {
    $('#livesearch-<?php echo $r3['ID_SER_PER_UO'];?>').fadeOut();
    document.getElementById("livesearch-<?php echo $r3['ID_SER_PER_UO'];?>").innerHTML="";
    document.getElementById("livesearch-<?php echo $r3['ID_SER_PER_UO'];?>").style.border="0px";
    return;
  }
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      if (str.length>2) {
        $('#livesearch-<?php echo $r3['ID_SER_PER_UO'];?>').fadeIn();
      }
      document.getElementById("livesearch-<?php echo $r3['ID_SER_PER_UO'];?>").innerHTML=this.responseText;
      document.getElementById("livesearch-<?php echo $r3['ID_SER_PER_UO'];?>").style.border="1px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","sportelli.php?q="+str+"&i="+<?php echo $r3['ID_SER_PER_UO'];?>,true);
  xmlhttp.send();
}

$(document).on('click',  '.sportelli<?php echo $r3['ID_SER_PER_UO'];?>', function(){  
    $('#sp_<?php echo $r3['ID_SER_PER_UO'];?>').val($(this).text());  
    $('#livesearch-<?php echo $r3['ID_SER_PER_UO'];?>').fadeOut();  
}); 




function addSp_<?php echo $r3['ID_SER_PER_UO'];?>() {
  console.log("Bottone  form cliccato");

  var id=document.getElementById('id_<?php echo $r3['ID_SER_PER_UO'];?>').value;
  console.log(id);
  var data_sp=document.getElementById('data_sp_<?php echo $r3['ID_SER_PER_UO'];?>').value;
  console.log(data_sp);
  var sp=document.getElementById('sp_<?php echo $r3['ID_SER_PER_UO'];?>').value;
  console.log(sp);
  
  //alert('Ora devo lanciare la funzione che crei l\'ordine di lavoro con i seguenti interventi: ' + selectedItems);
  // prevent form from submitting
  
  
  var url ="addSp.php?id="+encodeURIComponent(id)+"&d="+encodeURIComponent(data_sp)+"&sp="+encodeURIComponent(sp)+"";

  console.log(url);
  // get the URL
  http = new XMLHttpRequest(); 
  http.open("GET", url, true);
  http.send();
  console.log('Verifichiamo lo stato');
  console.log(http.readyState);
  
  //$( "#sezione_<?php echo $r3['ID_SER_PER_UO'];?>" ).load(window.location.href + " #sezione_<?php echo $r3['ID_SER_PER_UO'];?>" );
  //$( "#servizi" ).load(window.location.href + " #servizi" );
  
  //$( ".container-fluid" ).accordion("refresh");



  //$("#bilat").hide();
  //$("#comp_piazz").load(location.href + " #comp_piazz");
  //$("#successo").show();
  window.location.reload();
  //window.location.href = "ordini.php";*/
  return false;

}


</script>

<form  class="row g-1" autocomplete="off" id="sportello_add" action="" onsubmit="return addSp_<?php echo $r3['ID_SER_PER_UO'];?>();">
<div class="col-auto">
<input autocomplete="false" id="id_<?php echo $r3['ID_SER_PER_UO'];?>" type="text" value="<?php echo $r3['ID_SER_PER_UO'];?>" style="display:none;">
<input autocomplete="false" id="data_sp_<?php echo $r3['ID_SER_PER_UO'];?>" type="text" value="<?php echo substr($_POST['data_inizio'], 4);?>" style="display:none;">
<input autocomplete="false" class="form-control form-control-sm" type="text" id="sp_<?php echo $r3['ID_SER_PER_UO'];?>" 
onkeyup="showResult_<?php echo $r3['ID_SER_PER_UO'];?>(this.value)">

<div id="livesearch-<?php echo $r3['ID_SER_PER_UO'];?>"></div>
</div>
<div class="col-auto">
<button type="submit" class="btn btn-primary btn-sm">
<i class="fa-solid fa-check" title="Salva sportello"></i> 
</button>
</div>
</form>



<?php
//} // chiusura veccchio if che non serve più

echo "</div>"; // chiudo l'input group 

oci_free_statement($result_s);
echo '</div>'; // chiudo la col

echo '<div class="col-lg-4">';
$query5="SELECT apu.COD_MATLIBROMAT, hs.ID_PERSONA,
apu.DES_COGNOMEPERS, apu.DES_NOMEPERS, hs.DURATA 
FROM HIST_SERVIZI hs 
JOIN ANAGR_PERS_UO apu ON hs.ID_PERSONA = apu.ID_PERSONA 
AND hs.DTA_SERVIZIO > apu.DTA_INIZIO 
AND hs.DTA_SERVIZIO < apu.DTA_FINE 
WHERE ID_SER_PER_UO = :s1 
AND DTA_SERVIZIO = TO_DATE(:s2,'DD/MM/YYYY')";

$result5 = oci_parse($oraconn, $query5);

oci_bind_by_name($result5, ':s1', $r3['ID_SER_PER_UO']);
oci_bind_by_name($result5, ':s2', substr($_POST['data_inizio'], 5));
oci_execute($result5);

while($r5 = oci_fetch_assoc($result5)) {
    echo $r5['DES_COGNOMEPERS'] .' ' . substr($r5['DES_NOMEPERS'],0,2).' - ' . $r5['DURATA'] .' ';

}
oci_free_statement($result5);

echo '</div>'; // chiudo la col
echo '</div>'; // chiudo la row
?>