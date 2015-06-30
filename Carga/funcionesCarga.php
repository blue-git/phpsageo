 <?php
include ('funcionesExtra.php');

function procesar($file_handle, $tipo)
{

  // ============== DB DATA ==============
  $dbhost = "localhost";
  $dbuser = "root";
  $dbpass = "";
  $dbname = "testeo_carga";
  $tablename = "madre2";
  // =====================================

  $procesadas = 0;
  $totales = 0;
  $conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die('No se pudo conectar a la DB: ' . mysqli_error($conexion));
  while ( !feof($file_handle) )
  {
    $totales++;
    $linea = fgetcsv($file_handle,0,"|");
    if (querySQL($linea, $tipo, $conexion, $tablename)){
      $procesadas++;
    }
    else{
      print "Error en linea: ".$totales."<BR>";
    }
  }
  mysqli_close($conexion);
  return $procesadas;
}

function querySQL($values, $tipo, $conexion, $tablename)
{
  $realizarQuery = false;

if ($values[0])
{
  if ($tipo == 1 and sizeof($values) == 36)
  {
    $cliente = elegirCliente($values[7],$values[11]);
    $values[2] = tipoDePedido($values[2],$values[34],$values[33],$values[31],$cliente); //Reemplazo A-C por el tipo de pedido
    array_pop($values); //Borro el ultimo campo del archivo de pedidos (el num de avance)
    array_push($values,$cliente,'A derivar','Normal', date('Y-m-d'));
    $columnasArchivo='solicitud,nroTasacion,tipo,apellidoNombre,dni,canal,sucursal,tipoPrestamo,tasadora,sucOriginadora,fechaEnvio,destino,contacto,horarioContacto,telefono1,telefono2,titular,telefonoTitular,calle,numero,entreCalles,manzanaTorre,lotePiso,casaDepto,unidadFuncional,cochera,baulera,codPost,localidad,partidoDepto,provincia,observaciones,catastro,avance,prorroga,cliente,estado,prioridad,fechaIngreso';
    $realizarQuery = true;
  }

  if ($tipo == 2 and sizeof($values) == 16)
  {
    if ($values[11]) {
      $values[10] = $values[10] ." Y ".$values[11]; //Uno las entre calles
    }
    array_splice($values, 11, 1); //Borro la entre calle 2
    array_splice($values, 6, 2); //Borro los mails
    array_push($values,"27","Fotografias","FOTOGRAFIAS", "FOTOGRAFIAS",date('Y-m-d'),'A derivar','Normal', date('Y-m-d'));
    $columnasArchivo='solicitud,nroTasacion,apellidoNombre,telefono1,telefono2,telefonoTitular,calle,numero,entreCalles,codPost,localidad,partidoDepto,provincia,cliente,tipo,tipoPrestamo,destino,fechaEnvio,estado,prioridad,fechaIngreso';
    $realizarQuery = true;
  }

  if ($tipo == 3 and sizeof($values) == 2)
  {
    $pedido = "SELECT * FROM $tablename WHERE solicitud = $values[0] ORDER BY numeroSageo ASC LIMIT 1";
    $queryArray = mysqli_fetch_assoc(mysqli_query($conexion, $pedido));
    if ($queryArray)
    {
      $queryArray = array_replace($queryArray,["cliente" => 33,"tipo" => "Avance","nroTasacion" => $values[1]]);
      array_splice($queryArray, 0, 1);
      $columnasArchivo = implode(",",array_keys($queryArray));
      $values = $queryArray;
      $realizarQuery = true;
    }
    else {
      print "No se encontro pedido anterior para cargar el informe: ".$values[0]." ";
    }
  }

  if ($realizarQuery)
  {
    $valores = "'".implode("','",$values)."'";
    $bom = pack('H*','EFBBBF'); //Byte of mark UTF-8 a remover
    $valores = preg_replace("/$bom/", '', $valores);

    $sqlstring = "INSERT INTO $tablename ($columnasArchivo) VALUES ($valores)";
    mysqli_query( $conexion, $sqlstring ) or  die('No se pudo ingresar los datos: ' . mysqli_error($conexion).'<BR>');
  }
}
  return $realizarQuery;
}
 ?>
