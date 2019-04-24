<table class = "listado">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Status</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id = "lista_productos">
        <?php foreach($productos as $producto){ ?>
            <tr>
                <td><?php echo $producto['id']; ?></td>
                <td>
                    <a href = "#" onclick = "ver_producto(<?php echo $producto['id']; ?>);">
                        <?php echo $producto['nombre']; ?>
                    </a>
                </td>
                <td><?php echo $producto['descripcion']; ?></td>
                <td><?php echo $producto['precio']; ?></td>
                <td><?php if ($producto['status'] == 1){ ?>
                    Activo
                <?php }else{ ?>
                    Inactivo
                <?php } ?></td>
                <td>
                    <a href = "#" onclick = "eliminarProducto(<?php echo $producto['id']; ?>);">
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php } ?>
        <?php if (count($productos) == 0){ ?>
            <tr>
                <td colspan = "6">No hay productos registrados. </td>
            </tr>
        <?php } ?>
    </tbody>
</table>