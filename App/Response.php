<?php
namespace Core;

class Response
{
    private $status = 200;
    private $messages = [
        200 => '',
        201 => 'Criado com sucesso.',
        204 => 'Sem resposta.',
        400 => 'Requisição inválida.',
        401 => 'Acesso negado.',
        500 => 'Erro interno.'
    ];

    public function json($json=null)
    {
        $response = [
            'status' => $this->status,
            'data' => $json,
            'message' => $this->messages[$this->status]
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function status($code)
    {
        $this->status = $code;
        return $this;
    }
}