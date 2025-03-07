<?php
// Obtener el ID del proveedor desde la URL
$proveedor_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$proveedor_id) {
    echo "<div class='alert alert-danger'>ID de proveedor no proporcionado.</div>";
    exit();
}

// Obtener los datos del proveedor desde la API para mostrar la información
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

// Procesar la eliminación cuando se confirma
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmar'])) {
    // Configurar la solicitud cURL para eliminar el proveedor
    $ch = curl_init("https://inventarioapi-f3c6fvgsh0f2aueq.eastus2-01.azurewebsites.net/api/Proovedores/$proveedor_id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); // Usar DELETE para eliminar
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));

    // Ejecutar la solicitud y obtener la respuesta
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Obtener el código de estado HTTP
    curl_close($ch);

    // Verificar si la eliminación fue exitosa (código HTTP 200 o 204)
    if ($http_code == 200 || $http_code == 204) {
        // Redirigir a GetDataPHP.php después de eliminar
        header("Location: GetDataPHP.php");
        exit();
    } else {
        // Mostrar un mensaje de error con más detalles
        echo "<div class='alert alert-danger'>Error al eliminar el proveedor. Código de respuesta: $http_code</div>";
        echo "<div class='alert alert-info'>Respuesta de la API: " . htmlspecialchars($response) . "</div>"; // Mostrar la respuesta de la API
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Proveedor</title>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Eliminar Proveedor</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">¿Estás seguro de que deseas eliminar este proveedor?</h5>
                <p class="card-text"><strong>ID:</strong> <?php echo htmlspecialchars($proveedor['proovedorId']); ?></p>
                <p class="card-text"><strong>Nombre:</strong> <?php echo htmlspecialchars($proveedor['nombre']); ?></p>
                <p class="card-text"><strong>Contacto:</strong> <?php echo htmlspecialchars($proveedor['contacto']); ?></p>
                <p class="card-text"><strong>Dirección:</strong> <?php echo htmlspecialchars($proveedor['direccion']); ?></p>
                
                <form method="POST" action="Delete_proveedor.php?id=<?php echo $proveedor_id; ?>">
                    <button type="submit" name="confirmar" class="btn btn-danger">Eliminar</button>
                    <a href="GetDataPHP.php" class="btn btn-secondary">Volver</a>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>