<?php

/* PARA LA CONEXION CON LA BASE DE DATOS USAREMOS UNA CLASE QUE CONTENGA TODOS LOS MÉTODOS NECESARIOS. A ESTA CLASE LA LLAMAREMOS MODELO */
class Modelo {

    private $conexion;

    /* LA FUNCIÓN __construct() SE EJECUTA CADA VEZ QUE SE CREA UN NUEVO OBJETO CONEXION.
     POR ESO EN TEORÍA DE POO, SE LLAMA CONTRUCTOR */

    function __construct(){
        
        /* LA ESTRUCTURA try {} catch(e){} LA USAMOS PARA PREVENIR ALGÚN POSIBLE ERROR EN LA CONEXIÓN.
        DE EXISTIR UN ERROR, SALDRÁ DEL BLOQUE try Y PASARÁ AL CATCH ATRAPANDO EL ERROR. DE ESA FORMA
        EVITAMOS QUE EL PROGRAMA SE CAIGA*/

        try {
            
            /* PRIMERO OBTENEMOS LAS CREDENCIALES DE CONEXIÓN. */
            $this->obtenerCredenciales();

            /* CONCATENAREMOS LAS CREDENCIALES FORMANDO UNA CADENA CON ESTE FORMATO:
             motor:host=host;dbname=basedatos
             
             PARA CONCATENAR CADENAS EN PHP, SE UTILIZA EL PUNTO (.) DE LA SIGUIENTE MANERA: */

            $cadena_conexion = $this->credenciales['motor']
            .':host='.  $this->credenciales['host']
            .';dbname='. $this->credenciales['basedatos'];

            

            /* LUEGO USAMOS ESA CADENA EN CONJUNTO CON EL USUARIO Y CLAVE PARA INICIAR LA CONEXION CON LA BASE */
            $this->conexion = new PDO($cadena_conexion, $this->credenciales['usuario'], $this->credenciales['clave']);
        } catch (Exception $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    private function obtenerCredenciales(){
        /*Para conectarnos a la base, primero necesitamos las credenciales de dicha base.
        Lo que hacemos es obtener dichas credenciales del archivo conexion.json con la función
        file_get_contents, que devolverá una cadena con el contenido del archivo. 
        
        */

        $json_conexion = file_get_contents("conexion.json");

        /* Luego, con la función json_decode convertimos esa cadena en un arreglo asociativo */
        $this->credenciales = json_decode($json_conexion, true);

        /* LUEGO PODEMOS ACCEDER A LAS CREDENCIALES DE ESTA FORMA:
        
        $motor      = $this->credenciales['motor'];
        $basedatos  = $this->credenciales['basedatos'];
        
        ...*/
    }

    /* CON EL SIGUIENTE MÉTODO OBTENDREMOS EL LISTADO DE PRODUCTOS DESDE NUESTRA BASE.
    LE PASAMOS LA CONSULTA SQL AL METODO QUERY DE LA CONEXION QUE CREAMOS EN EL CONSTRUCTOR Y EL RESULTADO
    LO RECORREMOS PARA IRLO AÑADIENDO A UN ARREGLO ASOCIATIVO. DICHO ARREGLO LO DEVOLVEMOS CON RETURN */

    public function listarProductos($id = null){

        if (is_null($id)){
            $query = $this->conexion->query('SELECT id, nombre, descripcion, precio, status FROM productos');
        }else{
            $consulta = $this->conexion->prepare('
                SELECT id, nombre, descripcion, precio, status FROM productos WHERE id = :id LIMIT 1
            ');

            $consulta->execute([':id' => $id]);
            $query = [$consulta->fetch()];
        }

        $productos = array();

        foreach($query as $fila){
            $productos[] = [
                'id'            => $fila['id'],
                'nombre'        => $fila['nombre'],
                'descripcion'   => $fila['descripcion'],
                'precio'        => $fila['precio'],
                'status'        => $fila['status']
            ];
        }

        return $productos;
    }

    /* CON EL SIGUIENTE MÉTODO, GUARDAMOS UN PRODUCTO EN NUESTRA BASE */
    public function guardarProducto($nombre, $descripcion, $precio){
        
        try{
            /* PRIMERO PREPARAMOS LA QUERY PARA SER EJECUTADA */

            $query = $this->conexion->prepare(
                'INSERT INTO productos (
                    nombre
                    ,descripcion
                    ,precio
                ) VALUES (
                    :nombre
                    ,:descripcion
                    ,:precio
                )'
            );

            /* CREAMOS UN ARREGLO ASOCIATIVO CON LOS PARAMETROS DEL PRODUCTO */
            $parametros = [
                ':nombre'       => $nombre, 
                ':descripcion'  => $descripcion,
                ':precio'       => $precio
            ];

            /* EJECUTAMOS LA CONSULTA SQL DE INSERCIÓN PASANDOLE LOS PARAMETROS */
            $filas_insertadas = $query->execute($parametros);

            /* SI INSERTÓ CORRECTAMENTE EL PRODUCTO, DEVOLVEMOS TRUE, EN CUALQUIER OTRO CASO, FALSE */
            if ($filas_insertadas == 1){
                return true;
            }else{
                return false;
            }

        } catch(Exception $e){
            return false;
        }
        
    }

    /* CON EL SIGUIENTE MÉTODO, ACTUALIZAREMOS UN PRODUCTO YA EXISTENTE EN NUESTRA BASE. ES IDENTICA A LA ANTERIOR
    CON EL CAMBIO DE QUE AHORA EN LUGAR DE INSERT, SE USA UPDATE, Y SE RECIBEN MÁS PARÁMETROS */
    public function actualizarProducto($id, $nombre, $descripcion, $precio, $status = 1){

        /* EL $status = 1 INDICA QUE SI NO SE RECIBE EL PARAMETRO status, POR DEFECTO SERÁ 1 */
        
        try {
            
            $query = $this->conexion->prepare(
                'UPDATE productos SET
                nombre = :nombre,
                descripcion = :descripcion,
                precio = :precio,
                status = :status
                WHERE id = :id'
            );

            $parametros = [
                'id' => $id,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'status' => $status
            ];

            $filas_actualizadas = $query->execute($parametros);

            /* SI LOS REGISTROS AFECTADOS SON MAYORES A 0, ENTONCES ACTUALIZÓ BIEN */
            if ($filas_actualizadas > 0){
                return true;
            }else{
                return false;
            }

        }catch(Excepton $e){
            return false;
        }
    }

    public function eliminarProducto($id){
        try{

            /* NO TE OLVIDES DE PONER EL WHERE EN EL DELETE FROM: https://www.youtube.com/watch?v=i_cVJgIz_Cs */
            $query = $this->conexion->prepare(
                'DELETE FROM productos WHERE id = :id'
            );

            $parametros = [
                ':id' => $id
            ];

            $filas_eliminadas = $query->execute($parametros);

            /* SI LOS REGISTROS ELIMINADOS SON MAYORES A 0, ENTONCES ELIMINÓ BIEN */
            if ($filas_eliminadas > 0){
                return true;
            }else{
                return false;
            }

        }catch(Exception $e){
            return false;
        }
    }
}