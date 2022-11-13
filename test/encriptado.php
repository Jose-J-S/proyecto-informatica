<?php

$claveBase = "SENATI";      //Clave ORIGINAL
$claveEncriptada1 = sha1($claveBase);
$claveEncriptada2 = password_hash($claveBase, PASSWORD_BCRYPT);

$claveAcceso = "SENATI";    //Login

//Comprobando utilizando algoritmo débil
if (sha1($claveAcceso) == $claveEncriptada1){
  echo "<p>BIENVENIDO cifrado 1</p>";
}

//Comprobando utilizando algoritmo fuerte (ya no es necesario igualarlo a TRUE)
if (password_verify($claveAcceso, $claveEncriptada2)){
  echo "<p>Bienvenido cifrado 2</p>";
}


/* echo "<pre>";
var_dump($claveEncriptada1);
echo "</pre>";

echo "<pre>";
var_dump($claveEncriptada2);
echo "</pre>"; */


?>