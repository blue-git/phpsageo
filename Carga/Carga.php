<?php
  include('funcionesCarga.php');
?>
<html>
<head>
<title>Carga de archivo HIPOTECARIO</title>
Carga de archivo HIPOTECARIO <BR><BR><BR>
<FORM NAME ="form" METHOD ="POST" ACTION = "Carga.php">
  <INPUT TYPE = "Text" VALUE ="C:\Users\Pablo\Desktop\Pedido.txt" NAME = "loadPath" style="width: 300px">
  <BR>
  <select name="tipoDeCarga">
     <option value="hipo" selected>Hipotecario</option>
     <option value="ampl">Ampliaciones</option>
     <option value="foto">Fotografias</option>
  </select>
  <BR><BR>
  <INPUT TYPE = "Submit" Name = "botCarga" VALUE = "Cargar">
</FORM>
</head>
<body>

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

</body>
</html>
