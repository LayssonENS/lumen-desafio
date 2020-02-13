<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ConsultaCnpjController extends Controller
{
    protected $empresa;

    /* Instanciando no construct, o Client do Guzzle para facilitar o envio de POST e GET */
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
            $resultadoConsulta = json_decode($result->getBody()->getContents());

           /* Retorna dados personalizados*/
            return response()->json([
                'empresa' => [
                    'cnpj' => $resultadoConsulta->cnpj,
                    'ultima_atualizacao' => $resultadoConsulta->ultima_atualizacao,
                    'abertura' => $resultadoConsulta->abertura,
                    'nome' =>  $resultadoConsulta->nome,
                    'fantasia' =>  $resultadoConsulta->fantasia,
                    'status' => $resultadoConsulta->status,
                    'tipo' => $resultadoConsulta->tipo,
                    'situacao' => $resultadoConsulta->situacao,
                    'capital_social' => $resultadoConsulta->capital_social,
                    'endereco' => [
                        'bairro' => $resultadoConsulta->bairro,
                        'logradouro' => $resultadoConsulta->logradouro,
                        'numero' => $resultadoConsulta->numero,
                        'cep' => $resultadoConsulta->cep,
                        'municipio' => $resultadoConsulta->municipio,
                        'uf' => $resultadoConsulta->uf,
                        'complemento' => $resultadoConsulta->complemento
                    ],
                    'contato' => [
                        'telefone' => $resultadoConsulta->telefone,
                        'email' => $resultadoConsulta->email
                    ],
                    'atividade_principal' => $resultadoConsulta->atividade_principal     
                ]
            ], 200);

        }catch(QueryException $exception) {
            return response()->json([
                'erro' => 'Erro ao tentar executar a consulta'
            ], 500);
        }
    }
}