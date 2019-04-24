/* AQUÍ PONDREMOS LAS FUNCIONES JAVASCRIPT QUE CONTROLAN LA INTERFAZ */

function nuevo_producto() {
    aparecerDesaparecer(['formulario_productos', 'guardar', 'volver'], ['listado_productos', 'nuevo']);
    limpiar('campos_productos');
}

function volver() {
    aparecerDesaparecer(['listado_productos', 'nuevo'], ['formulario_productos', 'guardar', 'volver']);
}

function ver_producto(id) {
    conectar('index.php?accion=ver', { id: id }, function(respuesta) {
        let ObjRespuesta = JSON.parse(respuesta); //Como la respuesta se recibe como texto, debo convertirla a un objeto javascript.
        if (ObjRespuesta.error) { //Si hubo un error
            alert('Ocurrió un error al obtener el producto: ' + ObjRespuesta.glosa);
            return;
        }

        llenar_campos(ObjRespuesta.producto[0]);
    })
}

function guardar_producto() {

    campos = obtenerTextos('campos_productos');

    if (!validaciones()) { //Si no pasa las validaciones, cancela la acción
        return;
    }

    /* CREARÉ UN OBJETO PRODUCTO CON EL SIGUIENTE FOR */

    let objeto_producto = {};
    for (let i = 0; i < campos.length; i++) {
        let nombre = campos[i].name;
        let valor = campos[i].value;

        objeto_producto[nombre] = valor;
    }

    objeto_producto['status'] = document.getElementById('status').value;

    if (objeto_producto['id'] == '') {
        delete(objeto_producto['id']);
    }

    /* DICHA OBJETO SERÁ LO QUE LE PASAREMOS AL SERVIDOR PARA GUARDAR EL PRODUCTO */

    conectar('index.php?accion=guardar', objeto_producto, function(respuesta) {
        //Está función se ejecutará inmediatamente despues de que el servidor responda la llamada

        let ObjRespuesta = JSON.parse(respuesta); //Como la respuesta se recibe como texto, debo convertirla a un objeto javascript.

        if (ObjRespuesta.error) { //Si hubo un error
            alert('Ocurrió un error al guardar los datos: ' + ObjRespuesta.glosa);
            return;
        }

        actualizarListado(ObjRespuesta.listado);
        volver();
    });
}

function eliminarProducto(id) {
    if (!confirm('¿Seguro que desea eliminar el producto?')) {
        return;
    }

    conectar('index.php?accion=eliminar', { id: id }, function(respuesta) {
        let ObjRespuesta = JSON.parse(respuesta); //Como la respuesta se recibe como texto, debo convertirla a un objeto javascript.

        if (ObjRespuesta.error) { //Si hubo un error
            alert('Ocurrió un error al eliminar el producto: ' + ObjRespuesta.glosa);
            return;
        }

        actualizarListado(ObjRespuesta.listado);
    })
}

function actualizarListado(listado) {
    let cadena_filas = '';
    for (let i = 0; i < listado.length; i++) {
        let estado = (listado[i].status == 1) ? 'Activo' : 'Inactivo';

        cadena_filas += '<tr>';
        cadena_filas += '<td>' + listado[i].id + '</td>';
        cadena_filas += '<td><a href = "#" onclick = "ver_producto(' + listado[i].id + ');">' + listado[i].nombre + '</a></td>';
        cadena_filas += '<td>' + listado[i].descripcion + '</td>';
        cadena_filas += '<td>' + listado[i].precio + '</td>';
        cadena_filas += '<td>' + estado + '</td>';
        cadena_filas += '<td><a href = "#" onclick = "eliminarProducto(' + listado[i].id + ');">Eliminar</a></td>';
        cadena_filas += '</tr>';
    }

    if (listado.length == 0) {
        cadena_filas = '<tr><td colspan = "6">No hay productos registrados. </td></tr>';
    }

    document.getElementById('lista_productos').innerHTML = cadena_filas;
}

/* LIMPIEZA DE CAMPOS DEL FORMULARIO */
function limpiar(id_formulario) {
    elementos = obtenerTextos(id_formulario);

    for (let i = 0; i < elementos.length; i++) {
        elementos[i].value = '';
    }

    document.getElementById('status').value = 1;
}

/* LA SIGUIENTE FUNCIÓN APARECE UNOS ELEMENTOS HTML Y DESAPARECE OTROS */
function aparecerDesaparecer(elementos_aparecer, elementos_desaparecer) {
    aplicarEstilo(elementos_aparecer, 'display', '');
    aplicarEstilo(elementos_desaparecer, 'display', 'none'); //display none desaparece.
}

/* LA SIGUIENTE FUNCIÓN ME PERMITE APLICAR UN ESTILO CSS A UN ARREGLO DE ELEMENTOS HTML */
function aplicarEstilo(elementos, estilo, valor) {
    for (let i = 0; i < elementos.length; i++) {
        document.getElementById(elementos[i]).style[estilo] = valor;
    }
}

/* LA SIGUIENTE FUNCIÓN ME PERMITE OBTENER TODOS LOS ELEMENTOS DE TEXTO DE UN FORMULARIO */
function obtenerTextos(id_formulario) {
    let controles = document.getElementById(id_formulario).getElementsByTagName('input');

    /* EL SIGUIENTE WHILE LO HAGO PARA OBTENER SOLO LOS CAMPOS DE TEXTO */
    let i = 0;
    let controles_texto = new Array();
    while (i < controles.length) {
        if (controles[i].type == 'text' || controles[i].type == 'hidden') {
            controles_texto.push(controles[i]);
        }
        i++;
    }


    return controles_texto;
}

function llenar_campos(producto) {
    let claves = Object.keys(producto);
    for (let i = 0; i < claves.length; i++) {
        document.getElementById(claves[i]).value = producto[claves[i]];
    }

    aparecerDesaparecer(['formulario_productos', 'guardar', 'volver'], ['listado_productos', 'nuevo']);
}

function validaciones() {

    let nombre_producto = document.getElementById('nombre').value;
    if (nombre_producto.length == 0) { //Si el nombre del producto está vacío
        alert('Debe ingresar el nombre del producto');
        return false;
    }

    let descripcion_producto = document.getElementById('descripcion').value;
    if (descripcion_producto.length == 0) { //Si la descripcion del producto está vacía
        alert('Debe ingresar el nombre del producto');
        return false;
    }

    let precio_producto = document.getElementById('precio').value;
    if (precio_producto.length == 0) { //Si la descripcion del producto está vacía
        alert('Debe ingresar el precio del producto');
        return false;
    }

    return true;
}

function conectar(url, datos, callback) {
    /* PARA ENVIAR LOS DATOS AL SERVIDOR, USAREMOS UNA LIBRERÍA QUE PROGRAMÉ. SE ENCUENTRA EN LA CARPETA librerias */
    let ajax = new Najax();

    ajax.post(url, datos, callback);
}