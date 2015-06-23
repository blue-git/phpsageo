<html>
<head>
<title>Carga de archivo HIPOTECARIO</title>
<link rel="stylesheet" href="Carga.css">
</head>
<body>

  <titulo>
    Carga de pedidos <BR><BR><BR>
  </titulo>

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

  <?php
    include('funcionesCarga.php');
    include('start.php')
  ?>

</body>
</html>
