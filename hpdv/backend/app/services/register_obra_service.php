<?php
class RegisterObraService
{
    private $connect;
    private $obra;

    public function __construct(DbConnection $connect, RegisterObraModel $obra)
    {
        $this->connect = $connect->getConnection();
        $this->obra = $obra;
    }

    public function searchObraByCode()
    {
        $query = "SELECT codigo_obra FROM tb_obras WHERE codigo_obra = :codigo_obra";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':codigo_obra', $this->obra->__get('codigo_obra'));
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function RegisterObra()
    {
        $query = "INSERT INTO tb_obras (codigo_obra, nome_obra, cliente, cep, cidade, uf, endereco, bairro, complemento, numero, ponto_referencia) VALUES (:codigo_obra,:nome_obra, :cliente, :cep, :cidade, :uf, :endereco, :bairro, :complemento, :numero, :ponto_referencia)";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':codigo_obra', $this->obra->__get('codigo_obra'));
        $stmt->bindValue(':nome_obra', $this->obra->__get('nome_obra'));
        $stmt->bindValue(':cliente', $this->obra->__get('cliente'));
        $stmt->bindValue(':cep', $this->obra->__get('cep'));
        $stmt->bindValue(':cidade', $this->obra->__get('cidade'));
        $stmt->bindValue(':uf', $this->obra->__get('uf'));
        $stmt->bindValue(':endereco', $this->obra->__get('endereco'));
        $stmt->bindValue(':bairro', $this->obra->__get('bairro'));
        $stmt->bindValue(':complemento', $this->obra->__get('complemento'));
        $stmt->bindValue(':numero', $this->obra->__get('numero'));
        $stmt->bindValue(':ponto_referencia', $this->obra->__get('ponto_referencia'));
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function searchobra()
    {
        $query = "SELECT * FROM tb_obras WHERE nome_obra LIKE :valueSearch OR codigo_obra LIKE :valueSearch  ORDER BY nome_obra ASC";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':valueSearch', '%' . $this->obra->__get('valueSearch') . '%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteobra()
    {
        $query = "DELETE FROM tb_obras WHERE id = :id";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':id', $this->obra->__get('id'));
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function searchobraByCodeUpdate()
    {
        $query = "SELECT codigo_obra FROM tb_obras WHERE codigo_obra = :codigo_obra AND id != :id";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':codigo_obra', $this->obra->__get('codigo_obra'));
        $stmt->bindValue(':id', $this->obra->__get('id'));
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function updateobraWithouImage()
    {
        $query = "UPDATE tb_obras SET nome_obra = :nome_obra, codigo_obra = :codigo_obra, cliente = :cliente, cep = :cep, cidade = :cidade, uf = :uf, endereco = :endereco, bairro = :bairro, complemento = :complemento, numero = :numero, ponto_referencia = :ponto_referencia WHERE id = :id";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':codigo_obra', $this->obra->__get('codigo_obra'));
        $stmt->bindValue(':nome_obra', $this->obra->__get('nome_obra'));
        $stmt->bindValue(':cliente', $this->obra->__get('cliente'));
        $stmt->bindValue(':cep', $this->obra->__get('cep'));
        $stmt->bindValue(':cidade', $this->obra->__get('cidade'));
        $stmt->bindValue(':uf', $this->obra->__get('uf'));
        $stmt->bindValue(':endereco', $this->obra->__get('endereco'));
        $stmt->bindValue(':bairro', $this->obra->__get('bairro'));
        $stmt->bindValue(':complemento', $this->obra->__get('complemento'));
        $stmt->bindValue(':numero', $this->obra->__get('numero'));
        $stmt->bindValue(':ponto_referencia', $this->obra->__get('ponto_referencia'));
        $stmt->bindValue(':id', $this->obra->__get('id'));
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateobra()
    {
        $query = "UPDATE tb_obras SET nome_obra = :nome_obra, codigo_obra = :codigo_obra, cliente = :cliente, cep = :cep, cidade = :cidade, uf = :uf, endereco = :endereco, bairro = :bairro, complemento = :complemento, numero = :numero, ponto_referencia = :ponto_referencia  WHERE id = :id";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':nome_obra', $this->obra->__get('nome_obra'));
        $stmt->bindValue(':codigo_obra', $this->obra->__get('codigo_obra'));
        $stmt->bindValue(':cliente', $this->obra->__get('cliente'));
        $stmt->bindValue(':cep', $this->obra->__get('cep'));
        $stmt->bindValue(':cidade', $this->obra->__get('cidade'));
        $stmt->bindValue(':uf', $this->obra->__get('uf'));
        $stmt->bindValue(':endereco', $this->obra->__get('endereco'));
        $stmt->bindValue(':bairro', $this->obra->__get('bairro'));
        $stmt->bindValue(':complemento', $this->obra->__get('complemento'));
        $stmt->bindValue(':numero', $this->obra->__get('numero'));
        $stmt->bindValue(':ponto_referencia', $this->obra->__get('ponto_referencia'));    
        $stmt->bindValue(':id', $this->obra->__get('id'));
        $stmt->execute();
        return $stmt->rowCount();
    }
}
