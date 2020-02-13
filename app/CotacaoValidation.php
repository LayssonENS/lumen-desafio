<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CotacaoValidation extends Model
{
    /* Valida dados recebido do Json */
    const RULE_COTACAO = [
        'destinatario.*.cep' =>  'required|numeric',
        'volumes.*.tipo' => 'required|numeric',
        'volumes.*.quantidade' => 'required|numeric',
        'volumes.*.peso' => 'required|numeric',
        'volumes.*.valor' => 'required|numeric',
        'volumes.*.sku' => 'required',
        'volumes.*.altura' => 'required|numeric',
        'volumes.*.largura' => 'required|numeric',
        'volumes.*.comprimento' => 'required|numeric'
       
    ];
}