<?php
/**
 * Created by PhpStorm.
 * User: gustavoweb
 * Date: 28/11/2017
 * Time: 17:27
 */

namespace Source\Models;

class Shipment
{

    /*
     * const urlSoap = URL de cálculo de Preço e Prazo da cotação de Frete
     */
    const urlSoap = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL';

    /*
     * const urlRastro = URL utilizada para rastrear o objeto dos correios
     */
    const urlRastro = 'http://webservice.correios.com.br/service/rastro/Rastro.wsdl';

    /*
     * Atributos da cotação de frete
     */
    private $soap;
    private $nCdEmpresa;
    private $sDsSenha;
    private $nCdServico;
    private $sCepOrigem;
    private $nCdFormato;
    private $nVlLargura;

    /*
     * Atributos do rastreio de mercadorias
     */
    private $soapRastro;
    private $objeto;

    /*
     * Método Construtor da classe
     */
    public function __construct()
    {
        $this->nCdEmpresa = '';
        $this->sDsSenha = '';
        $this->nCdServico = '04014,04510';
        $this->sCepOrigem = '88063301';
        $this->nCdFormato = 1;
        $this->nVlLargura = '0';
    }

    /*
     * Método para fazer o orçamento do frete
     */
    public function quote($sCepDestino, $nVlPeso, $nVlComprimento, $nVlAltura, $nVlLargura, $sCdMaoPropria = 'N', $nVlValorDeclarado = 0, $sCdAvisoRecebimento = 'N')
    {
        //Parâmetros informados para o webservice
        $param = [
            'nCdEmpresa' => $this->nCdEmpresa,
            'sDsSenha' => $this->sDsSenha,
            'nCdServico' => $this->nCdServico,
            'sCepOrigem' => $this->sCepOrigem,
            'sCepDestino' => $sCepDestino,
            'nVlPeso' => $nVlPeso,
            'nCdFormato' => $this->nCdFormato,
            'nVlComprimento' => $nVlComprimento,
            'nVlAltura' => $nVlAltura,
            'nVlLargura' => $nVlLargura,
            'nVlDiametro' => $this->nVlLargura,
            'sCdMaoPropria' => $sCdMaoPropria,
            'nVlValorDeclarado' => $nVlValorDeclarado,
            'sCdAvisoRecebimento' => $sCdAvisoRecebimento
        ];

        // INSTANCIA DO SOAP
        $this->soap = new \SoapClient(self::urlSoap);

        // CONSUMO DO MÉTODO DO WEBSERVICE
        $CalcPrecoPrazo = $this->soap->CalcPrecoPrazo($param);

        // CALLBACK
        $result = (object)$CalcPrecoPrazo->CalcPrecoPrazoResult->Servicos->cServico;
        return $result;
    }

    public function rastro($objeto)
    {
        //Parâmetros informados para o webservice
        $params = [
            'usuario' => 'ECT',
            'senha' => 'SRO',
            'tipo' => 'L',
            'resultado' => 'T',
            'lingua' => '101',
            'objetos' => $objeto
        ];

        // INSTANCIA DO SOAP
        $this->soapRastro = new \SoapClient(self::urlRastro);

        // CONSUMO DO MÉTODO DO WEBSERVICE
        $buscaEventos = $this->soapRastro->buscaEventos($params);

        // CALLBACK
        return $buscaEventos->return->objeto;
    }
}