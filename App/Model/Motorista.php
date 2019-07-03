<?php

class Motorista extends Pessoa{

    private $modeloCarro;
    private $statusMotorista;
    private $statusCorridaMotorista;

    public function getModeloCarro() { return $this->modeloCarro; }
    public function getStatusMotorista() { return $this->statusMotorista; }
    public function getStatusCorridaMotorista() { return $this->statusCorridaMotorista; }
    
    public function setModeloCarro($modeloCarro){ $this->modeloCarro = $modeloCarro; }
    public function setStatusMotorista($statusMotorista){ $this->statusMotorista = $statusMotorista; }
    public function setStatusCorridaMotorista($statusCorridaMotorista){ $this->statusCorridaMotorista = $statusCorridaMotorista; }

    public static function selectDashboard()
    {
        $on = true;
        $off = false;
        $sql1 = 'SELECT COUNT(*) FROM tb_motorista';
        $sql2 = 'SELECT COUNT(*) FROM tb_motorista WHERE  bln_status = :statusMotorista';
        $sql3 = 'SELECT COUNT(*) FROM tb_motorista WHERE  bln_status_corrida = :statusCorridaOn';
        $sql4 = 'SELECT COUNT(*) FROM tb_motorista WHERE  bln_status_corrida = :statusCorridaOff';

        $comando1 = Connection::getConecta()->prepare( $sql1 );
        $comando1->execute();
        $c1 = $comando1->fetch(); 

        $comando2 = Connection::getConecta()->prepare( $sql2 );
        $comando2->bindParam(':statusMotorista', $on);
        $comando2->execute();
        $c2 = $comando2->fetch(); 
        
        $comando3 = Connection::getConecta()->prepare( $sql3 );
        $comando3->bindParam(':statusCorridaOn', $on);
        $comando3->execute();
        $c3 = $comando3->fetch(); 
        
        $comando4 = Connection::getConecta()->prepare( $sql4 );
        $comando4->bindParam(':statusCorridaOff', $off);
        $comando4->execute();
        $c4 = $comando4->fetch(); 

        $resultados = array();

        $resultados[] = $c1;
        $resultados[] = $c2;
        $resultados[] = $c3;
        $resultados[] = $c4;

        return $resultados;
    }

    public static function findByStatusAndCorrida()
    {
        $statusMotorista = true;
        $statusCorrida = false;
        
        $sql = 'SELECT * FROM tb_motorista WHERE bln_status = :statusMotorista AND bln_status_corrida = :statusCorrida';
        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindParam(':statusMotorista', $statusMotorista);
        $comando->bindParam(':statusCorrida', $statusCorrida);
        $comando->execute();

        $resultados = array();

        while ($row = $comando->fetchObject('Motorista')) {
            $row->setId($row->cd_motorista);
            $row->setNome($row->nm_motorista);
            $row->setNascimento($row->dt_nascimento);
            $row->setSexo($row->nm_sexo);
            $row->setDocumento($row->cd_documento);
            $row->setModeloCarro($row->nm_modelo);
            $row->setStatusMotorista($row->bln_status);
            $row->setStatusCorridaMotorista($row->bln_status_corrida);
            
            $resultados[] = $row;
        }

        if (!$resultados) {
            throw new Exception("Não há motoristas disponiveis no momento..", 1);
        }

        return $resultados;
    }

    public static function selectAll()
    {
        $sql = 'SELECT * FROM tb_motorista';
        $comando = Connection::getConecta()->prepare( $sql );
        $comando->execute();

        $resultados = array();

        while ($row = $comando->fetchObject('Motorista')) {
            
            $row->setId($row->cd_motorista);
            $row->setNome($row->nm_motorista);
            $row->setNascimento($row->dt_nascimento);
            $row->setSexo($row->nm_sexo);
            $row->setDocumento($row->cd_documento);
            $row->setModeloCarro($row->nm_modelo);
            $row->setStatusMotorista($row->bln_status);
            $row->setStatusCorridaMotorista($row->bln_status_corrida);
            
            $resultados[] = $row;
        }
        
        if (!$resultados) {
            throw new Exception("Não foi encontrado nenhum registro no banco!");
        }

        return $resultados;
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM tb_motorista WHERE cd_motorista = :id';
        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindParam(':id', $id);
        $comando->execute();

        $resultados = $comando->fetchObject('Motorista');
            
        $resultados->setId($resultados->cd_motorista);
        $resultados->setNome($resultados->nm_motorista);
        $resultados->setNascimento($resultados->dt_nascimento);
        $resultados->setSexo($resultados->nm_sexo);
        $resultados->setDocumento($resultados->cd_documento);
        $resultados->setModeloCarro($resultados->nm_modelo);
        $resultados->setStatusMotorista($resultados->bln_status);
        $resultados->setStatusCorridaMotorista($resultados->bln_status_corrida);
            
        
        if (!$resultados) {
            throw new Exception("Não foi encontrado nenhum registro no banco!");
        }

        return $resultados;
    }

    public static function create(Motorista $motorista)
    {
        if (empty($motorista->getNome()) 
            or empty($motorista->getNascimento()) 
            or empty($motorista->getSexo()) 
            or empty($motorista->getDocumento()) 
            or empty($motorista->getModeloCarro()) 
            ) {

            throw new Exception("Por favor, preencha corretamente o formulario!", 1);
            
            return false;
        }

        $sql = 'INSERT INTO tb_motorista 
                    (nm_motorista, dt_nascimento, nm_sexo, cd_documento, nm_modelo, bln_status, bln_status_corrida) 
                        VALUES 
                            (:nome, :dt, :sexo, :documento, :modeloCarro, :statusMotorista, :statusCorridaMotorista)';

        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindValue(':nome', $motorista->getNome());
        $comando->bindValue(':dt', $motorista->getNascimento());
        $comando->bindValue(':sexo', $motorista->getSexo());
        $comando->bindValue(':documento', $motorista->getDocumento());
        $comando->bindValue(':modeloCarro', $motorista->getModeloCarro());
        $comando->bindValue(':statusMotorista', $motorista->getStatusMotorista());
        $comando->bindValue(':statusCorridaMotorista', $motorista->getStatusCorridaMotorista());
        $res = $comando->execute();

        if ($res == 0) {
            throw new Exception("Falha ao inserir Motorista", 1);
            return false;
        }
        
        return true; 
    }

    public static function update(Motorista $motorista)
    {
        if (empty($motorista->getId()) 
            or empty($motorista->getNome()) 
            or empty($motorista->getNascimento()) 
            or empty($motorista->getSexo()) 
            or empty($motorista->getDocumento()) 
            or empty($motorista->getModeloCarro())
            or empty($motorista->getStatusMotorista())
            or empty($motorista->getStatusCorridaMotorista()) 
            ) {

            throw new Exception("Por favor, preencha corretamente o formulario!", 1);
            
            return false;
        }

        if ($motorista->getStatusMotorista() == 'Ativo') {
            $motorista->setStatusMotorista(true);
        } else {
            $motorista->setStatusMotorista(false);
        }

        if ($motorista->getStatusCorridaMotorista() == 'Ocupado') {
            $motorista->setStatusCorridaMotorista(true);
        } else {
            $motorista->setStatusCorridaMotorista(false);
        }
        
        $sql = 'UPDATE tb_motorista SET 
                    nm_motorista = :nome,
                    dt_nascimento = :dt,
                    nm_sexo = :sexo,
                    cd_documento = :documento,
                    nm_modelo = :modelo,
                    bln_status = :statusMotorista,
                    bln_status_corrida = :statusCorrida
                        WHERE cd_motorista = :id';
        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindValue(':nome', $motorista->getNome());
        $comando->bindValue(':dt', $motorista->getNascimento());
        $comando->bindValue(':sexo', $motorista->getSexo());
        $comando->bindValue(':documento', $motorista->getDocumento());
        $comando->bindValue(':modelo', $motorista->getModeloCarro());
        $comando->bindValue(':statusMotorista', $motorista->getStatusMotorista());
        $comando->bindValue(':statusCorrida', $motorista->getStatusCorridaMotorista());
        $comando->bindValue(':id', $motorista->getId());
        $res = $comando->execute();

        if ($res == 0) {
            throw new Exception("Falha ao alterar Motorista", 1);
            return false;
        }
        
        return true; 
        
    }

    public static function delete(Motorista $motorista)
    {
        if ($motorista->getStatusMotorista() == false 
            or $motorista->getStatusMotorista() == null 
            or $motorista->getStatusMotorista() == 0) {
                $motorista->setStatusMotorista(true);
        } elseif ($motorista->getStatusMotorista() == true 
                or $motorista->getStatusMotorista() == 1) {
            $motorista->setStatusMotorista(false);
        }

        if ($motorista->getStatusCorridaMotorista() == false 
            or $motorista->getStatusCorridaMotorista() == null 
            or $motorista->getStatusCorridaMotorista() == 0) {
                $motorista->setStatusCorridaMotorista(false);
        } else {
            $motorista->setStatusMotorista(true);
        }

        $sql = 'UPDATE tb_motorista SET bln_status = :statusMotorista WHERE cd_motorista = :id';
        //$sql = 'DELETE FROM tb_motorista WHERE cd_motorista = :id';
        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindValue(':statusMotorista', $motorista->getStatusMotorista());
        $comando->bindValue(':id', $motorista->getId());
        $res = $comando->execute();

        if ($res == 0) {
            throw new Exception("Não foi possivel alterar o status do Motorista!", 1);
            return false;
        }
        
        return true;
    }
}
