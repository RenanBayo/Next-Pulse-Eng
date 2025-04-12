<?php
class ServiceService
{
    private $connect;
    private $service;

    public function __construct(DbConnection $connect, ServiceModel $service)
    {
        $this->connect = $connect->getConnection();
        $this->service = $service;
    }

    public function searchServiceByCode()
    {
        $query = "SELECT codigo_servico FROM tb_servicos WHERE codigo_servico = :codigo_servico";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':codigo_servico', $this->service->__get('codigo_servico'));
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function registerService()
    {
        $query = "INSERT INTO tb_servicos (nome_servico, codigo_servico, descricao_servico, preco_servico, imagem_servico) 
                  VALUES (:nome_servico, :codigo_servico, :descricao_servico, :preco_servico, :imagem_servico)";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':nome_servico', $this->service->__get('nome_servico'));
        $stmt->bindValue(':codigo_servico', $this->service->__get('codigo_servico'));
        $stmt->bindValue(':descricao_servico', $this->service->__get('descricao_servico'));
        $stmt->bindValue(':preco_servico', $this->service->__get('preco_servico'));
        $stmt->bindValue(':imagem_servico', $this->service->__get('imagem_servico'));
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function searchService()
    {
        $query = "SELECT * FROM tb_servicos WHERE nome_servico LIKE :valueSearch OR codigo_servico LIKE :valueSearch ORDER BY nome_servico ASC";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':valueSearch', '%' . $this->service->__get('valueSearch') . '%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteService()
    {
        $query = "DELETE FROM tb_servicos WHERE id = :id";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':id', $this->service->__get('id'));
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function searchServiceByCodeUpdate()
    {
        $query = "SELECT codigo_servico FROM tb_servicos WHERE codigo_servico = :codigo_servico AND id != :id";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':codigo_servico', $this->service->__get('codigo_servico'));
        $stmt->bindValue(':id', $this->service->__get('id'));
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function updateServiceWithoutImage()
    {
        $query = "UPDATE tb_servicos SET nome_servico = :nome_servico, codigo_servico = :codigo_servico, descricao_servico = :descricao_servico, preco_servico = :preco_servico WHERE id = :id";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':nome_servico', $this->service->__get('nome_servico'));
        $stmt->bindValue(':codigo_servico', $this->service->__get('codigo_servico'));
        $stmt->bindValue(':descricao_servico', $this->service->__get('descricao_servico'));
        $stmt->bindValue(':preco_servico', $this->service->__get('preco_servico'));
        $stmt->bindValue(':id', $this->service->__get('id'));
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateService()
    {
        $query = "UPDATE tb_servicos SET nome_servico = :nome_servico, codigo_servico = :codigo_servico, descricao_servico = :descricao_servico, preco_servico = :preco_servico, imagem_servico = :imagem_servico WHERE id = :id";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':nome_servico', $this->service->__get('nome_servico'));
        $stmt->bindValue(':codigo_servico', $this->service->__get('codigo_servico'));
        $stmt->bindValue(':descricao_servico', $this->service->__get('descricao_servico'));
        $stmt->bindValue(':preco_servico', $this->service->__get('preco_servico'));
        $stmt->bindValue(':imagem_servico', $this->service->__get('imagem_servico'));
        $stmt->bindValue(':id', $this->service->__get('id'));
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
