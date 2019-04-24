<?php

require('modelo.php');

class Controlador {

    function __construct(){
        $this->modelo = new Modelo();   
    }

    public function cargarInterfaz(){
        /* Listamos los productos */
        $productos = (array) $this->modelo->listarProductos();

        /* CARGAMOS LA INTERFAZ PRINCIPAL */
        require('interfaces/interfaz_base.php');
    }

    public function listar(){
        $productos = (array) $this->modelo->listarProductos();

        $this->responder([
            'error' => false,
            'listado' => $productos
        ]);
    }

    public function ver($request){
        $producto = (array) $this->modelo->listarProductos($request['id']);

        $this->responder([
            'error' => false,
            'producto' => $producto
        ]);
    }

    public function guardar($request){

        if (empty($request['id'])){
            $this->validarRequeridos($request, ['nombre', 'descripcion', 'precio']);
            $exito = $this->modelo->guardarProducto($request['nombre'], $request['descripcion'], $request['precio']);
        }else{
            $this->validarRequeridos($request, ['id', 'nombre', 'descripcion', 'precio']);
            $status = (isset($request['status'])) ? $request['status'] : 1;
            $exito = $this->modelo->actualizarProducto($request['id'], $request['nombre'], $request['descripcion'], $request['precio'], $status);
        }
        
        if ($exito){
            $this->listar();
        }

        $this->responder([
            'error' => true,
            'glosa' => 'No se pudo guardar'
        ]);        
    }

    public function eliminar($request){
        $this->validarRequeridos($request, ['id']);

        if($this->modelo->eliminarProducto($request['id'])){
            $this->listar();
        }

        $this->responder([
            'error' => true,
            'glosa' => 'No se pudo eliminar'
        ]);   
    }

    public function responder($respuesta){
        echo json_encode($respuesta);
        exit();
    }

    public function validarRequeridos($request, $campos){
        foreach($campos as $campo){
            if (empty($request[$campo])){
                $this->responder([
                    'error' => true,
                    'glosa' => 'Se requiere campo '. $campo
                ]);
            }    
        }
    }

}