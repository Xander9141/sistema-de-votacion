<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "sistema_votacion";

// Conectar a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Obtener el id de la región seleccionada desde la URL
$regionId = 0; // valor por defecto
if (isset($_GET["region"])) {
    $regionId = $_GET["region"];
}

// Consulta para obtener las comunas correspondientes a la región seleccionada
$sql = "SELECT id, nombre FROM comunas WHERE region_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $regionId);
$stmt->execute();
$result2 = $stmt->get_result();

// Crear un array con los resultados de la consulta
$comunas = array();
while ($row = $result2->fetch_assoc()) {
    $comunas[] = array("id" => $row["id"], "nombre" => $row["nombre"]);
}

// Cerrar la conexión a la base de datos
$conn->close();

// Enviar la respuesta en formato JSON
echo json_encode($comunas);
?>
