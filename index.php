<?php
/**
 * Created by PhpStorm.
 * User: gustavoweb
 * Date: 28/11/2017
 * Time: 17:11
 */

// REQUIRE DA CLASSE (QUE SERÁ SUBSTITUÍDO PELO __autoload DA SUA APLICAÇÃO)
require __DIR__ . '/Source/Models/Shipment.php';

// INSTANCIA DO OBJETO
$ship = new \Source\Models\Shipment;

// INVOCAÇÃO DO MÉTODO
$formats = $ship->quote('88065030', '1', '20', '20', '20');

// EXIBE A SAÍDA NA TELA
echo "<h1>SoapClient</h1>";
foreach ($formats as $formatShip){
    echo "Forma de Entrega: {$formatShip->Codigo}<br>";
    echo "Valor: R$ {$formatShip->Valor}<br>";
    echo "Prazo: {$formatShip->PrazoEntrega} dias<br>";
    echo "<hr>";
}

// ATRIBUTOS DA URL PARA XML
$nCdEmpresa = '';
$sDsSenha = '';
$nCdServico = '04014,04510';
$sCepOrigem = '88063301';
$sCepDestino = '88065030';
$nVlPeso = '1';
$nCdFormato = '1';
$nVlComprimento = '20';
$nVlAltura = '20';
$nVlLargura = '20';
$nVlDiametro = '0';
$sCdMaoPropria = 'N';
$nVlValorDeclarado = '0';
$sCdAvisoRecebimento = 'N';

// CONSUMO VIA XML
$urlXML = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?StrRetorno=xml&nCdEmpresa={$nCdEmpresa}&sDsSenha={$sDsSenha}&nCdServico={$nCdServico}&sCepOrigem={$sCepOrigem}&sCepDestino={$sCepDestino}&nVlPeso={$nVlPeso}&nCdFormato={$nCdFormato}&nVlComprimento={$nVlComprimento}&nVlAltura={$nVlAltura}&nVlLargura={$nVlLargura}&nVlDiametro={$nVlDiametro}&sCdMaoPropria={$sCdMaoPropria}&nVlValorDeclarado={$nVlValorDeclarado}&sCdAvisoRecebimento={$sCdAvisoRecebimento}";
$result = simplexml_load_file($urlXML);

// EXIBE SAÍDA NA TELA
echo "<h1>XML</h1>";
foreach ($result as $formatShip){
    echo "Forma de Entrega: {$formatShip->Codigo}<br>";
    echo "Valor: R$ {$formatShip->Valor}<br>";
    echo "Prazo: {$formatShip->PrazoEntrega} dias<br>";
    echo "<hr>";
}

// CONSUMO FEITO VIA CURL
echo "<h1>CURL</h1>";
$post = [
    'objetos' => 'DV700025559BR'
];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://www2.correios.com.br/sistemas/rastreamento/resultado_semcontent.cfm');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
$result = utf8_encode(curl_exec($ch));
curl_close($ch);

echo $result;

// CONSUMO DO MÉTODO DE RASTREIO
//echo "<h1>Rastreio pela classe rastro</h1>";
//$obj = (object) $ship->rastro('DV947686100BR');
//
//echo "Entrega Objeto: {$obj->numero}<br>";
//echo "Nome: {$obj->nome}<br>";
//echo "Categoria: {$obj->categoria}<br>";
//echo "Data e Hora: {$obj->evento->data} {$obj->evento->hora}<br>";
//echo "Evento: {$obj->evento->descricao}<br>";
//echo "Local:  {$obj->evento->local} - {$obj->evento->codigo} - {$obj->evento->cidade}/{$obj->evento->uf}<br>";
//echo "<hr>";