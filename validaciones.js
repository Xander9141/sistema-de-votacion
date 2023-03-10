// Función para validar el RUT
function validarRut(rut) {
    if (rut.length != 10) {
        return false;
    }

    var suma = 0;
    var arrRut = rut.split("");

    for (var i = 0; i < arrRut.length; i++) {
        if (i == arrRut.length - 1 && arrRut[i].toUpperCase() == "K") {
            suma += 10 * (9 - i);
        } else if (!isNaN(parseInt(arrRut[i]))) {
            suma += parseInt(arrRut[i]) * (9 - i);
        } else {
            return false;
        }
    }

    var dv = 11 - (suma % 11);

    if (dv == 11) {
        dv = 0;
    } else if (dv == 10) {
        dv = "K";
    }

    if (dv.toString().toUpperCase() == arrRut[arrRut.length - 1].toUpperCase()) {
        return true;
    } else {
        return false;
    }
}

// Función para validar el email
function validarEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

// Función para validar el formulario de votación
function validarFormulario() {
    var nombre = document.getElementById("nombre").value;
    var alias = document.getElementById("alias").value;
    var rut = document.getElementById("rut").value;
    var email = document.getElementById("email").value;
    var region = document.getElementById("region").value;
    var comuna = document.getElementById("comuna").value;
    var candidato = document.getElementById("candidato").value;
    var enteros = document.getElementsByName("entero[]");
    var seleccionados = false;
    // Validar el RUT
if (!validarRut(rut)) {
    alert("El RUT ingresado es inválido.");
    return false;
}

// Verificar si el RUT ya existe en la tabla "votos"
var xhr = new XMLHttpRequest();
xhr.open("POST", "verificar_rut.php", false);
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
        if (xhr.responseText == "existe") {
            alert("Error: El RUT ya ha votado.");
        } else {
            // Insertar el voto en la tabla "votos"
            var xhr2 = new XMLHttpRequest();
            xhr2.open("POST", "insertar_voto.php", false);
            xhr2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr2.onreadystatechange = function() {
                if (xhr2.readyState == 4 && xhr2.status == 200) {
                    if (xhr2.responseText == "ok") {
                        alert("Voto registrado correctamente.");
                    } else {
                        alert("Error al registrar el voto.");
                    }
                }
            };
            xhr2.send("nombre=" + encodeURIComponent(nombre) + "&rut=" + encodeURIComponent(rut) + "&candidato=" + encodeURIComponent(candidato));
        }
    }
};
xhr.send("rut=" + encodeURIComponent(rut));
}
$rut = $_POST['rut'];


// Verificar si el RUT ya existe en la tabla "votos"
$sql = "SELECT COUNT(*) FROM votos WHERE rut = '$rut'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

if ($row[0] > 0) {
    // El RUT ya existe en la tabla "votos"
    alert("Error: El RUT ya ha votado.");
} else {
    // Insertar el voto en la tabla "votos"
    $nombre = $_POST['nombre'];
    $candidato = $_POST['candidato'];

    $sql = "INSERT INTO votos (nombre, rut, candidato) VALUES ('$nombre', '$rut', '$candidato')";
    if (mysqli_query($conn, $sql)) {
        alert("Voto registrado correctamente.");
    } else {
        alert("Error al registrar el voto: ".mysqli_error($conn));
    }
}

// Obtener los checkboxes seleccionados
var opcionesSeleccionadas = [];
var checkboxes = document.getElementsByName("entero[]");
for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].checked) {
        opcionesSeleccionadas.push(checkboxes[i].value);
    }
}

// Validar que se hayan seleccionado al menos dos opciones
if (opcionesSeleccionadas.length < 2) {
    alert("Debe seleccionar al menos dos opciones.");
    return false;
}

// Concatenar las opciones seleccionadas separadas por comas
var opcionesTexto = opcionesSeleccionadas.join(",");

for (var i = 0; i < enteros.length; i++) {
    if (enteros[i].checked) {
        seleccionados = true;
    }
}

if (nombre.trim() == "" || alias.trim() == "" || rut.trim() == "" || email.trim() == "" || region == "" || comuna == "" || candidato == "" || !seleccionados) {
    alert("Debe completar todos los campos del formulario.");
    return false;
}


if (!validarEmail(email)) {
    alert("El email ingresado es inválido.");
    return false;
}

return true;
