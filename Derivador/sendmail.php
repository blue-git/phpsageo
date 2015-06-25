
<html>
<head>
</head>
<body>
<?php
if ($_POST['mail'] and $_POST['web']) {
  ob_start();
    include_once $_POST['web'];
  $body = ob_get_contents();
  ob_end_clean();
  echo "<font color=black>Se ha enviado el mail a: ".$_POST['mail']."</font>";
  echo $body;
        if ($_POST['asunto']) {
          $subject = $_POST['asunto'];
        }
        else {
          $subject = "";
        }
        $message = $body;
        $to = $_POST['mail'];
        $type = 'HTML'; // or HTML
        $charset = 'utf-8';
        $mail     = 'info@sageo.com.ar';
        $headers  = 'From: '.$mail."\n";
        $headers .= 'Reply-to: '.$mail."\n";
        $headers .= 'MIME-Version: 1.0' . PHP_EOL;
        $headers .= 'Content-type: text/'.$type.';charset='.$charset.''."\n";

        mail($to, $subject, $message, $headers);
}
else {
  print "<div align='center'><font size=9 color=#FF0000>[Error]</font><font size=9 color=#000000> Debe setear los campos mail, web y asunto desde el POST.</font></div>";
}
?>
</body>
</html>
