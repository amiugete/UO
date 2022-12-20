<?php
session_start();
#require('../validate_input.php');

//require ('./conn.php');

//echo "OK";


//$idcivico=$_GET["id"];
$query='SELECT USERNAME, DATA_VALIDITA
FROM UNIOPE.AMIU_TOKEN 
WHERE TOKEN=:t1';

//echo "<br>". $token;

$result = oci_parse($oraconn, $query);

oci_bind_by_name($result, ':t1', $token);

oci_execute($result);



$rows = array();
while($r = oci_fetch_assoc($result)) {
    $username = $r['USERNAME'];
    //$rows[] = $r;
}


#echo "<br>OK";


// CREAZIONE JSON
#echo $rows ;
/*if (empty($rows)==FALSE){
    //print $rows;
    $locations =(json_encode($rows));
    echo $locations;
} else {
    echo "[{\"NOTE\":'No data'}]";
}*/

oci_free_statement($result);
//oci_close($oraconn);
?>