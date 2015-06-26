<?php
include ('funcionesExtra.php');

function procesar($file_handle, $tipo)
{
  $procesadas = 0;
  $totales = 0;
  while ( !feof($file_handle) )
  {
    $totales++;
    $linea = fgetcsv($file_handle,0,"|");
    if (querySQL($linea, $tipo)){
      $procesadas++;
    }
    else{
      print "Faltaron datos en linea: ".$totales."<BR>";
    }
  }
  return $procesadas;
}

function querySQL($values, $tipo)
{
  $realizarQuery = false;

  //De cada uno tiene que salir cargado:
  //
  //columnasArchivo - Con el nombre de las columnas de la db que contiene el archivo a cargar
  //values - El array con los valores a cargar ordenados segun columnasArchivo
  //realizarQuery - En true, indicando que los tres datos anteriores estan seteados para realizar la query correctamente

  if ($tipo == 1 and sizeof($values == 39))
  {
    $cliente = elegirCliente($values[7],$values[11]);
    $values[2] = tipoDePedido($values[2],$values[34],$values[33],$values[31],$cliente); //Reemplazo A-C por el tipo de pedido
    array_pop($values); //Borro el ultimo campo del archivo de pedidos (el num de avance)
    array_push($values,$cliente);
    $columnasArchivo='solicitud,nroTasacion,tipo,apellidoNombre,dni,canal,sucursal,tipoPrestamo,tasadora,sucOriginadora,fechaEnvio,destino,contacto,horarioContacto,telefono1,telefono2,titular,telefonoTitular,calle,numero,entreCalles,manzanaTorre,lotePiso,casaDepto,unidadFuncional,cochera,baulera,codPost,localidad,partidoDepto,provincia,observaciones,catastro,avance,prorroga,cliente';
    $realizarQuery = true;
  }

  if ($tipo == 2 and sizeof($values == 16))
  {
    if ($values[11])
    {
      $values[10] = $values[10] ." Y ".$values[11]; //Uno las entre calles
    }
    array_splice($values, 11, 1); //Borro la entre calle 2
    array_splice($values, 6, 2); //Borro los mails
    array_push($values,"27","Fotografias","FOTOGRAFIAS", "FOTOGRAFIAS",date('Y-m-d'));
    $columnasArchivo='solicitud,nroTasacion,apellidoNombre,telefono1,telefono2,telefonoTitular,calle,numero,entreCalles,codPost,localidad,partidoDepto,provincia,cliente,tipo,tipoPrestamo,destino,fechaEnvio';
    $realizarQuery = true;
  }

  if ($tipo == 3 and sizeof($values > 1))
  {
    $realizarQuery = true;
  }

  if ($realizarQuery)
  {
    $columnasAgregadas='estado,prioridad,fechaIngreso';
    array_push($values,'A derivar','Normal', date('Y-m-d'));

    $valores = "'".implode("','",$values)."'";
    $bom = pack('H*','EFBBBF'); //Byte of mark UTF-8 a remover
    $valores = preg_replace("/$bom/", '', $valores);

    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'testeo_carga';
    $tablename = 'madre2';
    $conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die('No se pudo conectar a la DB: ' . mysqli_error($conexion));;
    $sqlstring = "INSERT INTO ".$tablename." (".$columnasArchivo.",".$columnasAgregadas.") VALUES (".$valores.")";
    //Podria hacer un log de las lineas que no se cargaron... podria jajaja
    mysqli_query( $conexion, $sqlstring ) or  die('No se pudo ingresar los datos: ' . mysqli_error($conexion).'<BR>');
    mysqli_close($conexion);
    
    return true;
  }
  else {
    return false;
  }
}
 ?>
