<?php
// Obtener el ID del proveedor a editar desde la URL
$proveedor_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$proveedor_id) {
    echo "<div class='alert alert-danger'>ID de proveedor no proporcionado.</div>";
    exit();
}

// Obtener los datos del proveedor desde la API
$ch = curl_init("https://inventarioapi-f3c6fvgsh0f2aueq.eastus2-01.azurewebsites.net/api/Proovedores/$proveedor_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code != 200) {
    echo "<div class='alert alert-danger'>Error al obtener los datos del proveedor. Código de respuesta: $http_code</div>";
    exit();
}

$proveedor = json_decode($response, true);

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $contacto = $_POST['contacto'];
    $direccion = $_POST['direccion'];

    // Datos que se enviarán a la API
    $data = array(
        'proovedorId' => $proveedor_id, // Incluir el ID si la API lo requiere
        'nombre' => $nombre,
        'contacto' => $contacto,
        'direccion' => $direccion
    );

    // Convertir los datos a JSON
    $json_data = json_encode($data);

    // Configurar la solicitud cURL para actualizar el proveedor
    $ch = curl_init("https://inventarioapi-f3c6fvgsh0f2aueq.eastus2-01.azurewebsites.net/api/Proovedores/$proveedor_id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Usar PUT para actualizar
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_data))
    );

    // Ejecutar la solicitud y obtener la respuesta
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Obtener el código de estado HTTP
    curl_close($ch);

    // Verificar si la actualización fue exitosa (código HTTP 200 o 204)
    if ($http_code == 200 || $http_code == 204) {
        // Redirigir a GetDataPHP.php después de guardar
        header("Location: GetDataPHP.php");
        exit();
    } else {
        // Mostrar un mensaje de error con más detalles
        echo "<div class='alert alert-danger'>Error al actualizar el proveedor. Código de respuesta: $http_code</div>";
        echo "<div class='alert alert-info'>Respuesta de la API: " . htmlspecialchars($response) . "</div>"; // Mostrar la respuesta de la API
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor</title>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Editar Proveedor</h1>
        <form method="POST" action="Edit_proveedor.php?id=<?php echo $proveedor_id; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($proveedor['nombre']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="contacto" class="form-label">Contacto</label>
                <input type="text" class="form-control" id="contacto" name="contacto" value="<?php echo htmlspecialchars($proveedor['contacto']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($proveedor['direccion']); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="GetDataPHP.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>