<?php

/* Notificar solamente errores de ejecución */
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/* INICIAMOS LA APLICACIÓN CARGANDO LOS ARCHIVOS NECESARIOS */
require('controlador.php');

/* CARGAMOS NUESTRO CONTROLADOR, QUE POSEE TODA LA LÓGICA DE NUESTRA APLICACIÓN DEL LADO DEL SERVIDOR */
$controlador = new Controlador();

/* SI EN LA PETICIÓN RECIBIDA EXISTE LA VARIABLE accion, NO CARGAREMOS NINGUNA INTERFAZ Y LO TRATAREMOS COMO UNA API */
if (!empty($_GET['accion'])){

    /* PHP TIENE DOS ARREGLOS QUE CAPTURAN PARAMETROS EN LAS PETICIONES: $_GET Y $_POST
    EL PRIMERO CAPTURA DATOS PASADOS POR LA MISMA URL (ejemplo index.php?accion=guardar)
    EL SEGUNDO SE UTILIZA PARA ENVIAR DATOS DE FORMULARIOS Y OTROS */

    if (method_exists($controlador, $_GET['accion'])){
        $controlador->{ $_GET['accion'] }($_POST);
    }

    $controlador->responder([
        'error' => true,
        'glosa' => 'No existe el metodo ' . $_GET['accion']
    ]);
}

/* SI EN CAMBIO NO EXISTE accion, CARGAREMOS NUESTRA INTERFAZ */
$controlador->cargarInterfaz();


?>