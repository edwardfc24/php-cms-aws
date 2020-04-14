<?php

function alert($type, $message) {
    global $documentReadyScript;
    $documentReadyScript.="<script type='text/javascript'>
    $(document).ready(function(){
        new PNotify({
            title: 'Mensaje',
            text: '". $message ."',
            type: '".$type."',
            styling: 'bootstrap3'
        });
    });
</script>";
}

function alert_redirect($type, $message, $url) {
    global $documentReadyScript;
    $documentReadyScript.="<script type='text/javascript'>
    $(document).ready(function(){
        new PNotify({
            title: 'Mensaje',
            text: '". $message ."',
            type: '".$type."',
            styling: 'bootstrap3'
        });
        setTimeout(
        function() {
            window.location.href = '".$url."';
        }, 2000);        
    });
</script>";
}

function comboCategorias($actual) {
    $obc = new CategoriaBLL();
    $arrCategorias = $obc->selectAll();
    $show = '';
    foreach ($arrCategorias as $objCategoria) {
        if ($objCategoria->getId() == $actual)
            $sel = " selected ";
        else
            $sel = "";
        $show .=
        '<option value="' . $objCategoria->getId() . '" ' . $sel . '>'
        . $objCategoria->getNombreCategoria() .
        '</option>';
    }
    return $show;
}


