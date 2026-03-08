<?php
header("Access-Control-Allow-Origin: *");
$dati = json_decode(file_get_contents("php://input"), true);

$nome = $dati["nome"];
$contenuto = $dati["contenuto"];
$prezzo = $dati["prezzo"];

switch($prezzo) {
    case $prezzo <= 1000:
        header("Location: http://localhost:3000/insert?nome=$nome&contenuto=$contenuto&prezzo=$prezzo");
        break;
    case $prezzo > 1000 && $prezzo <= 10000:
        header("Location: http://localhost:3010/insert?nome=$nome&contenuto=$contenuto&prezzo=$prezzo");
        break;
    case $prezzo > 10000:
        header("Location: http://localhost:3020/insert?nome=$nome&contenuto=$contenuto&prezzo=$prezzo");
        break;
    default:
        echo "Errore";
}
?>