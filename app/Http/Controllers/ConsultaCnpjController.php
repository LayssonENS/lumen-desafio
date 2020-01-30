<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ConsultaCnpjController extends Controller
{
    protected $empresa;

    /* Instanciado no construct, o Client do Guzzle para facilitar o envio de POST e GET */
    public function __construct()
    {
        $this->empresa = new Client();
    }

    public function ConsultarCnpj($cnpj)
    {   
        /* Valida tamanho do CNPJ e se contém apenas números */
        if ((strlen($cnpj) != 14) or (!is_numeric($cnpj)))
        return([[ 'status' => 'CNPJ invalido'], 'ERRO', 400]);
        
        /* Realiza consulta de cnpj na API: https://www.receitaws.com.br/v1/cnpj/  */
        $result =$this->empresa->get('https://www.receitaws.com.br/v1/cnpj/' . $cnpj);
        $resultado = json_decode($result->getBody()->getContents());

        /* Monta Json para retorno personalizado */
        $json = [
            'empresa' => [
                'cnpj' => $resultado->cnpj,
                'ultima_atualizacao' => $resultado->ultima_atualizacao,
                'abertura' => $resultado->abertura,
                'nome' =>  $resultado->nome,
                'fantasia' =>  $resultado->fantasia,
                'status' => $resultado->status,
                'tipo' => $resultado->tipo,
                'situacao' => $resultado->situacao,
                'capital_social' => $resultado->capital_social,
                'endereco' => [
                    'bairro' => $resultado->bairro,
                    'logradouro' => $resultado->logradouro,
                    'numero' => $resultado->numero,
                    'cep' => $resultado->cep,
                    'municipio' => $resultado->municipio,
                    'uf' => $resultado->uf,
                    'complemento' => $resultado->complemento
                ],
                'contato' => [
                    'telefone' => $resultado->telefone,
                    'email' => $resultado->email
                ],
                'atividade_principal' => $resultado->atividade_principal   
                
            ]
        ];
        return response($json, 200)->header('Content-Type', 'application/json');
    }
}