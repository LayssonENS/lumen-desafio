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
        
        /* Valida se Cnpj é válido e retorna caso não for*/
        $validadorCnpj = \Validator::make(
            ['cnpj' => $cnpj],
            ['cnpj' => 'required|cnpj']
        );
    
        if ($validadorCnpj->fails())
        {
            return response()->json([
                'erro' => 'O valor do CNPJ não é válido'
            ], 422);
        }      

        try{
            /* Realiza consulta de cnpj na API: https://www.receitaws.com.br/v1/cnpj/  */
            $result =$this->empresa->get('https://www.receitaws.com.br/v1/cnpj/' . $cnpj);
            $resultado = json_decode($result->getBody()->getContents());

           /* Retorna dados de acordo como pedido no desafio*/
            return response()->json([
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
            ], 200);

        }catch(QueryException $exception) {
            return response()->json([
                'erro' => 'Erro ao tentar executar a consulta'
            ], 500);
        }
    }
}