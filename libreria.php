<?php // funzione lato server che possono servire 
function data_italiana($data){
    $d=date_create($data);
    return date_format($d,"d/m/Y");
}
?>