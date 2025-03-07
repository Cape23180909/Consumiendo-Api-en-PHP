<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores</title>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="GetDataPHP.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Lista de Proveedores</h1>
        <a href="crear_proveedor.php" class="btn btn-primary btn-crear">Crear Nuevo Proveedor</a>
        <?php
        // Iniciamos una nueva sesión cURL
        $ch = curl_init();
        
        // URL del endpoint de la API para obtener los proveedores
        $url = "https://inventarioapi-f3c6fvgsh0f2aueq.eastus2-01.azurewebsites.net/api/Proovedores";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "<div class='alert alert-danger'>Error al conectarse a la API</div>";
        } else {
            curl_close($ch);
            
            // Decodificar la respuesta JSON
            $proveedores_data = json_decode($response, true);

            // Verificar si la respuesta es válida
            if (is_array($proveedores_data)) {
                echo "<table class='table'>
                        <thead>
                            <tr>
                                <th>ProveedorID</th>
                                <th>Nombre</th>
                                <th>Contacto</th>
                                <th>Dirección</th>
                            </tr>
                        </thead>
                        <tbody>";

                foreach ($proveedores_data as $proveedor) {
                    // Verificar si las claves existen en el array
                    $id = isset($proveedor['id']) ? $proveedor['id'] : 'N/A';
                    $nombre = isset($proveedor['nombre']) ? $proveedor['nombre'] : 'N/A';
                    $contacto = isset($proveedor['contacto']) ? $proveedor['contacto'] : 'N/A';
                    $direccion = isset($proveedor['direccion']) ? $proveedor['direccion'] : 'N/A';

                    echo "<tr>
                            <td>".$id."</td>
                            <td>".$nombre."</td>
                            <td>".$contacto."</td>
                            <td>".$direccion."</td>
                          </tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<div class='alert alert-warning'>No se encontraron proveedores.</div>";
            }
        }
        ?>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>