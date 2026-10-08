<?php
// conexion.php
$host = 'localhost';
$dbname = 'tapizados_miura'; // El nombre de tu base de datos en MySQL
$username = 'root'; // Tu usuario de MySQL
$password = 'Santi90Sql40'; // Tu contraseña de MySQL (vacía por defecto en XAMPP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar PDO para que lance excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode([
        "status" => "error", 
        "message" => "Error de conexión a la base de datos: " . $e->getMessage()
    ]));
}
?>