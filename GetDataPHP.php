<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obtener datos de Api desde PHP</title>
</head>
<body>
    <?php
   //Iniciamo una  nueva session 
   $ch = curl_init();
    
   //Colocar el link de dicha api
   $url = "https://pokeapi.co/api/v2/pokemon/ditto";

   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

   $response = curl_exec($ch);

   if (curl_errno($ch))
   {
    $error_msg = curl_error($ch);
    echo"Error al conectarse a la api";
   }
   else 
   {
    curl_close($ch);
    //Cambiar variable mirando el link de la api y colocar la propiedad de la misma, para tener en cuenta
    $pokeapi_data = json_decode($response,true);
     
    echo "<h1>".$pokeapi_data['name']."</h1>";

   }



    ?>
</body>
</html>