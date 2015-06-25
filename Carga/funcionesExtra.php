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
?>
