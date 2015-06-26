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
          $procesadas = procesar($file_handle,$_POST['tipoDeCarga']);
          fclose($file_handle);
          print ("<BR>Se cargaron: ".$procesadas." inspecciones");
        }
        else {
          print ("No se encontro el archivo, revise la direccion ingresada");
        }
    }

?>
