<?php
function ElegirCliente($tipoPrestamo, $subTipoPrestamo)
{
  switch ($tipoPrestamo)
  {
    case "PRESTAMO PROGRAMA CREDITO ARGENTINO":
      if ($subTipoPrestamo == "MEJORA - AMPLIACION" or $subTipoPrestamo == "TERMINACION") {
        return 33;
      }
      elseif($subTipoPrestamo == "ADQUISICION") {
        return 35;
      }
      else {
        return 31;
      }
      break;
    case "PROCREAR ASISTENCIA DAMNIFICADOS":
        return 23;
      break;
    case "PRESTAMO REFACCION PROCREAR BICENTENARIO":
        return 25;
      break;
    case "":
      if ($subTipoPrestamo == "FOTOGRAFIAS") {
        return 27;
      }
      else {
        return 0;
      }
      break;
    default:
      return 29;
  }
}

function TipoDePedido($cancelada,$prorroga,$avance,$observaciones,$cliente)
{
  if ($cancelada == "C")
  {
    return "Anulado";
  }
  if ($prorroga == "SI")
  {
    return "Prorroga";
  }
  if (preg_match("/visita/i",$observaciones))
  {
    return "Reconsideracion";
  }
  if (($cliente == 25) or ($cliente == 23))
  {
    return "Refaccion";
  }
  if ($avance == "SI")
  {
    return "Avance";
  }
  return "Terreno";
}

  function SQLHipotecario($values)
  {
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'testeo_carga';
    $tablename = 'madre2';

    $conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die('No se pudo conectar a la DB: ' . mysqli_error($conexion));;
    $columnasHipotecario='solicitud,nroTasacion,tipo,apellidoNombre,dni,canal,sucursal,tipoPrestamo,tasadora,sucOriginadora,fechaEnvio,destino,contacto,horarioContacto,telefono1,telefono2,titular,telefonoTitular,calle,numero,entreCalles,manzanaTorre,lotePiso,casaDepto,unidadFuncional,cochera,baulera,codPost,localidad,partidoDepto,provincia,observaciones,catastro,avance,prorroga';
    $columnasNuestras='cliente,estado,prioridad,fechaIngreso';
    //columnasNoUsadas='numeroSageo,deudorCliente,tasador,fechaVisita,recibidoTasador,fechaSalida,viaje,solicitante,mailSolicitante,idMejoras,honorarioEspecial,presupuestoEspecial,pagadoTasador,facturaSageo';'
    $cliente = ElegirCliente($values[7],$values[11]);
    $values[2] = TipoDePedido($values[2],$values[34],$values[33],$values[31],$cliente); //Reemplazo A por el tipo de pedido
    array_pop($values); //Borro el ultimo campo del archivo de pedidos
    array_push($values, $cliente,'A derivar','Normal', date('Y-m-d')); //Agrego nuestras columnas
    $valores = "'".implode("','",$values)."'";
    $sqlstring = "INSERT INTO ".$tablename." (".$columnasHipotecario.",".$columnasNuestras.") VALUES (".$valores.")";
    $valorquery = mysqli_query( $conexion, $sqlstring ) or  die('No se pudo ingresar los datos: ' . mysqli_error($conexion));
    mysqli_close($conexion);
  }

 ?>
