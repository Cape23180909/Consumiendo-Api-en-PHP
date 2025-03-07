<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores</title>
</head>
<body>
    <h1>Lista de Proveedores</h1>
    <?php
    // Iniciamos una nueva sesión cURL
    $ch = curl_init();
    
    // URL del endpoint de la API para obtener los proveedores
    $url = "https://inventarioapi-f3c6fvgsh0f2aueq.eastus2-01.azurewebsites.net/api/Proovedores";

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "Error al conectarse a la API";
    } else {
        curl_close($ch);
        
        // Decodificar la respuesta JSON
        $proveedores_data = json_decode($response, true);

        // Verificar si la respuesta es válida
        if (is_array($proveedores_data)) {
            echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Dirección</th>
                    </tr>";

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

            echo "</table>";
        } else {
            echo "No se encontraron proveedores.";
        }
    }
    ?>
</body>
</html>