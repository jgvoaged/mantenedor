<!-- ESTA ES LA INTERFAZ BASE. CONTIENE TODO LO NECESARIO QUE REQUIERE UN DOCUMENTO HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- CON LO SIGUIENTE CARGAMOS LOS ESTILOS CSS DE NUESTRA APLICACIÓN -->
    <link rel="stylesheet" type="text/css" href="interfaces/estilos.css?v=0.1">
</head>
<body>
    <!-- LOS ELEMENTOS DIV SON CAJAS. 
    USANDO EL ATRIBUTO CLASS SE LE PUEDEN PONER DISTINTOS ESTILOS CSS,
    MIENTRAS QUE CON EL ATRIBUTO ID SE LE DA UN IDENTIFICADOR AL ELEMENTO -->

    <!-- PODEMOS METER CODIGO PHP EN LAS INTERFACES ENCERRANDOLO EN ESTOS BLOQUES <?php ?> 
     TRATA DE HACER LO MENOS POSIBE Y SOLO PARA LO NECESARIO. ES MUY MALA PRACTICA PONER LÓGICA DE NEGOCIO
     EN MEDIO DE LAS INTERFACES. -->

     <!--
     USANDO LOS REQUIRE UNO PUEDE CARGAR DISTINTOS FRAGMENTOS DE INTERFAZ Y DE ESA MANERA SEPARAR LAS PARTES -->

    <div class = "cuerpo">
        <?php require('elementos_interfaz/botones.php'); ?>

        <div id = "listado_productos">
            <?php require('elementos_interfaz/listado.php'); ?>
        </div>
        <div id = "formulario_productos" style = "display:none;">
            <?php require('elementos_interfaz/formulario.php'); ?>
        </div>
    </div>

    <!-- CON LO SIGUIENTE CARGAMOS UN SCRIPT EXTERNO HECHO EN JAVASCRIPT -->
    <script src = "interfaces/librerias/ajax.js?v=0.1"></script>
    <script src = "interfaces/funciones_interfaz.js?v=0.1"></script>
</body>
</html>