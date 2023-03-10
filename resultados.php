<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_votacion";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Consulta para contar votos por candidato
$sql = "SELECT candidato, COUNT(*) as cantidad_votos FROM votos GROUP BY candidato";
$result = mysqli_query($conn, $sql);

// Creación de gráfico con librería GD
$image = imagecreatetruecolor(400, 300);
$background_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);
$bar_color = imagecolorallocate($image, 0, 0, 255);
$font_path = 'arial.ttf';
$margin = 30;
$bar_width = 50;

// Dibujo del gráfico
imagefill($image, 0, 0, $background_color);
imagettftext($image, 12, 0, $margin, $margin, $text_color, $font_path, 'Votos por candidato');
$x = $margin;
$y = $margin * 2;
while ($row = mysqli_fetch_assoc($result)) {
  $candidato = $row['candidato'];
  $cantidad_votos = $row['cantidad_votos'];
  imagettftext($image, 12, 0, $x, $y, $text_color, $font_path, $candidato);
  imagefilledrectangle($image, $x, $y + 10, $x + $bar_width, $y + 10 + $cantidad_votos * 10, $bar_color);
  imagettftext($image, 12, 0, $x + 5, $y + 20 + $cantidad_votos * 10, $text_color, $font_path, $cantidad_votos);
  $x += $bar_width + $margin;
}

// Generación de imagen
header('Content-Type: image/png');
imagepng($image);

// Liberación de memoria
imagedestroy($image);
mysqli_close($conn);
?>
