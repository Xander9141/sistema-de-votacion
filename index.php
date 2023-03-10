<?php


include "obtener_candidatos.php";
include "obtener_region.php";
include "obtener_comunas.php";
// Comprobar si el formulario fue enviado y procesar la información
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	include "guardar_voto.php";
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Elecciones 2023</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
	<div class="container">
		<header>
			<h1>Elecciones 2023</h1>
		</header>
		<nav>
			<ul>
				<li><a href="index.php">Inicio</a></li>
				<li><a href="#">Votar</a></li>
				<li><a href="">Resultados</a></li>
				<li><a href="#">Contacto</a></li>

			</ul>
		</nav>
		<main>

			<div style="border: 2px solid black; padding: 10px;">
				<table>
					<form method="post" action="index.php" onsubmit="return validarFormulario()">

						<h2>Formulario de Votación</h2>
						<label for="nombre">Nombre y Apellido:</label>
						<input type="text" id="nombre" name="nombre" required>
						<br><br>
						<label for="alias">Alias:</label>
						<input type="text" id="alias" name="alias" required>
						<br><br>
						<label for="rut">RUT:</label>
						<input type="text" id="rut" name="rut" required>
						<br><br>
						<label for="email">Email:</label>
						<input type="email" id="email" name="email" required>
						<br><br>
						<label for="region">Región:</label>
						<select id="region" name="region" onchange="actualizarComunas()">
							<option value="">Selecciona una región</option>
							<?php
							// Recorre cada fila del resultado de la consulta y genera una opción para cada región
							while ($row = mysqli_fetch_assoc($result)) {
								echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
							}
							?>
						</select>
						<br><br>
						<label for="comuna">Comuna:</label>
						<label for="comuna">Comuna:</label>
						<select name="comuna" id="comuna">
							<option value="">Seleccione una comuna</option>
							<?php
							// Recorre cada fila del resultado de la consulta y genera una opción para cada región
							while ($row = mysqli_fetch_assoc($result2)) {
								echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
							}
							?>
						</select>
						</select>
						<br><br>
						<label for="candidato">Candidato:</label>
						<select id="candidato" name="candidato">
							<option value="">Selecciona un candidato</option>
							<?php
							// Recorre cada fila del resultado de la consulta y genera una opción para cada candidato
							while ($row = mysqli_fetch_assoc($result3)) {
								echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
							}
							?>
						</select>

						<br><br>
						<label>¿Cómo se enteró de nosotros?</label><br>
						<input type="checkbox" id="web" name="entero[]" value="Web">
						<label for="web">Web</label><br>
						<input type="checkbox" id="amigo" name="entero[]" value="Amigo">
						<label for="amigo">Amigo</label><br>
						<input type="checkbox" id="tv" name="entero[]" value="TV">
						<label for="tv">TV</label><br>
						<input type="checkbox" id="otro" name="entero[]" value="Otro">
						<label for="otro">Otro</label><br>
						<br>
						<input type="submit" value="Votar">
					</form>
				</table>
			</div>
			<script>
				function actualizarComunas() {
					var regionId = document.getElementById('region').value;
					if (regionId) {
						var xhr = new XMLHttpRequest();
						xhr.onreadystatechange = function() {
							if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
								var comunas = JSON.parse(xhr.responseText);
								var comunaSelect = document.getElementById('comuna');
								// Limpiar las opciones de comunas
								comunaSelect.options.length = 0;
								// Agregar la opción por defecto
								var defaultOption = document.createElement('option');
								defaultOption.value = '';
								defaultOption.textContent = 'Seleccione una comuna';
								comunaSelect.appendChild(defaultOption);
								// Agregar las opciones de comunas correspondientes a la región seleccionada
								comunas.forEach(function(comuna) {
									var option = document.createElement('option');
									option.value = comuna.id;
									option.textContent = comuna.nombre;
									comunaSelect.appendChild(option);
								});
							}
						};
						xhr.open('GET', 'obtener_comunas.php?region=' + encodeURIComponent(regionId));
						xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

						xhr.send();
					} else {
						// Si no se ha seleccionado ninguna región, limpiar las opciones de comunas
						var comunaSelect = document.getElementById('comuna');
						comunaSelect.options.length = 0;
						var defaultOption = document.createElement('option');
						defaultOption.value = '';
						defaultOption.textContent = 'Seleccione una comuna';
						comunaSelect.appendChild(defaultOption);
					}
				}
			</script>
		</main>
		<footer>
			<p>Elecciones 2023 | Todos los derechos reservados</p>
		</footer>
	</div>
</body>

</html>