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

// Consulta para obtener los candidatos
$conn = mysqli_connect($host, $user, $password, $dbname);
$query = "SELECT id, nombre FROM candidatos";
$result3 = mysqli_query($conn, $query);


// Cerrar la conexión a la base de datos
$conn->close();
?>