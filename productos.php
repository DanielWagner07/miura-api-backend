<?php
// productos.php
header('Content-Type: application/json'); // Indicamos que la respuesta será JSON
header('Access-Control-Allow-Origin: *'); // Permite que tu Frontend se conecte (CORS)
header('Access-Control-Allow-Methods: GET, POST');

require 'conexion.php';

$method = $_SERVER['REQUEST_METHOD'];

// ---------------------------------------------------------
// MÉTODO POST: CREAR UN NUEVO PRODUCTO DESDE EL PANEL
// ---------------------------------------------------------
if ($method === 'POST') {
    // Capturamos los datos enviados desde el frontend
    $nombre = $_POST['nombre'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $id_categoria = $_POST['id_categoria'] ?? 1;
    
    // Validamos la restricción NOT NULL (campos obligatorios)
    if (empty($nombre) || $precio <= 0) {
        echo json_encode(["status" => "error", "message" => "El nombre y el precio son obligatorios."]);
        exit;
    }

    // Lógica para subir la imagen al servidor
    $imagenPath = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreImagen = basename($_FILES['imagen']['name']);
        $rutaDestino = 'uploads/' . $nombreImagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);
        $imagenPath = $rutaDestino;
    }

    try {
        // Preparamos la consulta SQL respetando tu diccionario de datos
        $sql = "INSERT INTO producto (nombre, precio, stock, id_categoria, imagen) 
                VALUES (:nombre, :precio, :stock, :id_categoria, :imagen)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':precio' => $precio,
            ':stock' => $stock,
            ':id_categoria' => $id_categoria,
            ':imagen' => $imagenPath
        ]);

        echo json_encode(["status" => "success", "message" => "¡Mueble guardado correctamente en el catálogo!"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al guardar: " . $e->getMessage()]);
    }
}

// ---------------------------------------------------------
// MÉTODO GET: LISTAR PRODUCTOS (Para la vista del cliente)
// ---------------------------------------------------------
elseif ($method === 'GET') {
    try {
        // Traemos solo los productos con estado = 1 (Activos)
        $stmt = $pdo->query("SELECT * FROM producto WHERE estado = 1");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($productos);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>