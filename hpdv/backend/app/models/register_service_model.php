<?php
#[AllowDynamicProperties] #php 8.2
class ServiceModel
{
    private $id;
    private $status;
    private $emissao;
    private $prazo;
    private $agendamento;
    private $vistoria;
    private $conclusao;
    private $pagamento;
    private $ordem_servico;
    private $atividade;
    private $escritorio;
    private $cliente;


    public function __get($attribute)
    {
        return $this->$attribute;
    }

    public function __set($attribute, $value)
    {
        $this->$attribute = $value;
    }
}
