<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
$host = "localhost";
$user = "root";
$password = "";
$dbname = "sistema_votacion";

// Conectar a la base de datos
$conexion = new mysqli($host, $user, $password, $dbname);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Validar el RUT
if (isset($_POST['validar_rut'])) {
    $rut = $_POST['validar_rut'];

    $sql = "SELECT * FROM votos WHERE rut = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $rut);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si el RUT ya se utilizó, devolver un objeto JSON con la propiedad "resultado" igual a "existe"
        $response = array('resultado' => 'existe');
        echo json_encode($response);
    } else {
        // Si el RUT no se ha utilizado, devolver un objeto JSON con la propiedad "resultado" igual a "no existe"
        $response = array('resultado' => 'no existe');
        echo json_encode($response);
    }
    
    // Si el RUT no se ha utilizado, mostrar una alerta y redirigir al usuario al index
    if ($response['resultado'] == 'no existe') {
        
        exit();
    }
}

// Obtener los valores enviados por el formulario
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$alias = mysqli_real_escape_string($conexion, $_POST['alias']);
$rut = mysqli_real_escape_string($conexion, $_POST['rut']);
$email = mysqli_real_escape_string($conexion, $_POST['email']);
$region = mysqli_real_escape_string($conexion, $_POST['region']);
$comuna = mysqli_real_escape_string($conexion, $_POST['comuna']);
$candidato = mysqli_real_escape_string($conexion, $_POST['candidato']);
$opciones = implode(',', $_POST['entero']);

try {
    // Preparar la consulta SQL
    $sql = "INSERT INTO votos (nombre, alias, rut, email, region, comuna, candidato, opciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssss", $nombre, $alias, $rut, $email, $region, $comuna, $candidato, $opciones);
    $stmt->execute();
    echo "<script>alert('Voto ingresado con éxito. ¡Gracias por participar! Haz click en el botón para volver al índice.');window.location.replace('voto_ingresado.php');</script>";
    // Si la consulta se ejecuta correctamente, redirigir al usuario al index
    header("Location: voto_ingresado.php");
    exit();
} catch (mysqli_sql_exception $e) {
    // Si se produce un error de clave duplicada, mostrar una alerta y redirigir al usuario al index
    if ($e->getCode() == 1062) {
        echo "<script>alert('Ya se ha registrado un voto con ese RUT. Por favor, inténtelo de nuevo.');</script>";
        echo "<script>window.location.replace('index.php');</script>";
        exit();
    } else {
        die("Error al guardar el voto: " . $e->getMessage());
    }
}




