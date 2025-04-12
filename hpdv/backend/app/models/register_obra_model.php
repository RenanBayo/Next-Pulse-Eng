<?php
#[AllowDynamicProperties] #php 8.2
class RegisterObraModel
{
    private $id;
    private $codigo_obra;
    private $nome_obra;
    private $cliente;
    private $cep;
    private $cidade;
    private $uf;
    private $endereco;
    private $bairro;
    private $complemento;
    private $numero;
    private $ponto_referencia;
    private $data_criacao;
    private $data_modificacao;

    public function __get($attribute)
    {
        return $this->$attribute;
    }

    public function __set($attribute, $value)
    {
        $this->$attribute = $value;
    }
}
