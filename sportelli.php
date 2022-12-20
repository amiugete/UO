<?php 
require_once('./conn.php');

$parte='%'.$_GET['q'].'%';
$id=$_GET['i'];
//echo $parte;
//exit;
$query_s="SELECT TARGA, 
trim(leading '0' from ams.SPORTELLO) AS SPORTELLO_OK, 
DESCRIZIONE  
FROM ANAGR_MEZZI_SPORTELLO ams 
WHERE ATTIVO = 'S' 
AND ams.SPORTELLO LIKE :sp1
ORDER BY ams.SPORTELLO";

$result_s = oci_parse($oraconn, $query_s);


oci_bind_by_name($result_s, ':sp1', $parte);

oci_execute($result_s);

#$rows = array();

$rows='';
while($r_s = oci_fetch_assoc($result_s)) {
    $rows = $rows . '<li class="sportelli'.$id.'">'. $r_s['SPORTELLO_OK'].'</li>';
}

// Set output to "no suggestion" if no hint was found
// or to the correct values
if ($rows=='') {
    $response="<li>Non ci sono sportelli con questi numeri</li>";
  } else {
    $response=$rows;
  }
  
  //output the response
  echo '<ul class="lista-sportelli">' .$response .'</ul>';


?>