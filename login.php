<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "tu_base_de_datos"; // Cambia por el nombre real

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Evita errores por falta de datos
if (!isset($_POST['cedula']) || !isset($_POST['contrasena'])) {
    die("Datos incompletos");
}

$cedula = $conn->real_escape_string($_POST['cedula']);
$hashRecibido = $conn->real_escape_string($_POST['contrasena']); // Ya viene como SHA-256

// Consulta para obtener el hash almacenado
$sql = "SELECT password_hash FROM usuarios WHERE cedula = '$cedula' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo "Usuario no encontrado";
    exit;
}

$row = $result->fetch_assoc();
$hashGuardado = $row['password_hash'];

// Comparación de hashes
if (hash_equals($hashGuardado, $hashRecibido)) {
    // Login exitoso
    session_start();
    $_SESSION['usuario'] = $cedula;
    echo "Login exitoso";
    // Redirigir si quieres:
    // header("Location: panel.php");
} else {
    echo "Contraseña incorrecta";
}

$conn->close();
?>

