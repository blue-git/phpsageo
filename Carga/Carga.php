<?PHP
    include ('menu.html');
    include ('contenidoCarga.html');
    include ('funcionesCarga.php');


    if (isset($_POST['loadPath']))
    {
        $archivoPath = $_POST['loadPath'];
        if ( file_exists($archivoPath) )
        {
          $file_handle = fopen( $archivoPath, "r" );
          switch($_POST['tipoDeCarga'])
          {
            case "hipo":
              $contador = procesarHipotecario($file_handle);
              break;
            case "ampl":
              $contador = procesarAmpliacion($file_handle);
              break;
            case "foto":
              $contador = procesarFotografia($file_handle);
              break;
          }
          fclose($file_handle);
          print ("Se cargaron: ".$contador." inspecciones");
        }
        else {
          print ("No se encontro el archivo, revise la direccion ingresada");
        }
    }

?>
