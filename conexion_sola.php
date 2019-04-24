<?php

/* CON ESTA SOLA LINEA CONECTAMOS A MYSQL */
$conexion = new PDO('mysql:host=localhost;dbname=crudxampp', 'root', '');


/* DE LA SIGUIENTE MANERA EJECUTAMOS UNA CONSULTA SQL CON EL OBJETO RECIEN CREADO */
$resultado = $conexion->query('SELECT * FROM productos');


/* PARA ACCEDER A LOS RESULTADOS, PODEMOS HACERLO CON UN FOREACH */

foreach($query as $fila){
    $productos[] = [
        'id'            => $fila['id'],
        'nombre'        => $fila['nombre'],
        'descripcion'   => $fila['descripcion'],
        'precio'        => $fila['precio'],
        'status'        => $fila['status']
    ];
}