<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\CotacaoValidation;


class CotacaoController extends Controller
{  
    
    protected $cotacao;
    
    /* Instanciando no construct, o Client do Guzzle para facilitar o envio de POST e GET */
    public function __construct()
    {
        $this->cotacao = new Client();
    }
    
    public function CotacaoFrete(Request $data )
    {   
        
        /* Valida se os dados recebidos */
        $this->validate($data, 
            CotacaoValidation::RULE_COTACAO    
        );

        /* Recebendo dados enviados via post para montar JSON  */
        $json =[
            'remetente' => [
                'cnpj' => '17184406000174'
            ],
            'destinatario' => $data->destinatario,
            'volumes' => $data->volumes,
            'token' => 'c8359377969ded682c3dba5cb967c07b',
            'codigo_plataforma' => '588604ab3'
        ];
        try{
            $result =$this->cotacao->post('https://freterapido.com/api/external/embarcador/v1/quote-simulator',
            array('json' => $json));
            
            $resultado = json_decode($result->getBody()->getContents());
            
            

            /* Montando array com dados recebidos via JSON  */
            $cotacoes = array();
            foreach($resultado->transportadoras as $cotacao){
        
                $transportadoras  = array( "nome" => $cotacao->nome,
                                    "servico" => $cotacao->servico,
                                    "prazo_entrega" => $cotacao->prazo_entrega,
                                    "preco_frete" => $cotacao->preco_frete
                );
                
                /* Pega dados do array transportadoras  e adiciona no array cotacoes */
                array_push( $cotacoes, $transportadoras );
            }
        
            /* Montando array com as cotações  */
            $transportadorasCotacoes  = array("transportadoras" => $cotacoes );

            return response($transportadorasCotacoes , 200)->header('Content-Type', 'application/json');
            
        }catch(QueryException $exception) {
            return response()->json([
                'erro' => 'Erro ao tentar executar a consulta'
            ], 500);
        }
    }

}