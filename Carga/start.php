<?PHP
    if (isset($_POST['loadPath']))
    {
        $archivoPath = $_POST['loadPath'];
        if ( file_exists($archivoPath) )
        {
          //hipo
          $file_handle = fopen( $archivoPath, "r" );
          while ( !feof($file_handle) )
          {
            $linea = fgetcsv($file_handle,0,"|");
            if (sizeof($linea == 39))
            {
              SQLFun($linea);
              @$contador++;
            }
            else {
              print ("Faltan datos en linea: ". $contador);
            }
          }
          fclose($file_handle);
          print ("Se cargaron: ".$contador." inspecciones");
        }
        else {
          print ("No se encontro el archivo, revise la direccion ingresada");
        }
    }
?>