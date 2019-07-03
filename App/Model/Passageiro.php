<?php

class Passageiro extends Pessoa{

    private $statusPassageiro;      // true = ocupado  - false = livre

    public static function selectDashboard()
    {
        $on = true;
        $off = false;
        $sql1 = 'SELECT COUNT(*) FROM tb_passageiro';
        $sql2 = 'SELECT COUNT(*) FROM tb_passageiro WHERE  bln_status = :statusPassageiroOn';
        $sql3 = 'SELECT COUNT(*) FROM tb_passageiro WHERE  bln_status = :statusPassageiroOff';

        $comando1 = Connection::getConecta()->prepare( $sql1 );
        $comando1->execute();
        $c1 = $comando1->fetch(); 

        $comando2 = Connection::getConecta()->prepare( $sql2 );
        $comando2->bindParam(':statusPassageiroOn', $on);
        $comando2->execute();
        $c2 = $comando2->fetch(); 
        
        $comando3 = Connection::getConecta()->prepare( $sql3 );
        $comando3->bindParam(':statusPassageiroOff', $off);
        $comando3->execute();
        $c3 = $comando3->fetch(); 

        $resultados = array();

        $resultados[] = $c1;
        $resultados[] = $c2;
        $resultados[] = $c3;

        return $resultados;
    }

    public static function findByStatus()
    {
        $statusPassageiro = false;
        
        $sql = 'SELECT * FROM tb_passageiro WHERE bln_status = :statusPassageiro';
        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindParam(':statusPassageiro', $statusPassageiro);
        $comando->execute();

        $resultados = array();

        while ($row = $comando->fetchObject('Passageiro')) {
            $row->setId($row->cd_passageiro);
            $row->setNome($row->nm_passageiro);
            $row->setNascimento($row->dt_nascimento);
            $row->setSexo($row->nm_sexo);
            $row->setDocumento($row->cd_documento);
            $row->setStatusPassageiro($row->bln_status);
            
            $resultados[] = $row;
        }

        if (!$resultados) {
            throw new Exception("Não há passageiros livres no momento..", 1);
        }

        return $resultados;
    }

    public static function selectAll()
    {
        $sql = 'SELECT * FROM tb_passageiro';
        $comando = Connection::getConecta()->prepare( $sql );
        $comando->execute();

        $resultados = array();

        while ($row = $comando->fetchObject('Passageiro')) {
            
            $row->setId($row->cd_passageiro);
            $row->setNome($row->nm_passageiro);
            $row->setNascimento($row->dt_nascimento);
            $row->setSexo($row->nm_sexo);
            $row->setDocumento($row->cd_documento);
            $row->setStatusPassageiro($row->bln_status);
            
            $resultados[] = $row;
        }
        
        if (!$resultados) {
            throw new Exception("Não foi encontrado nenhum registro no banco!");
        }

        return $resultados;
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM tb_passageiro WHERE cd_passageiro = :id';

        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindParam(':id', $id);
        $comando->execute();

        $resultado = $comando->fetchObject('Passageiro');

        $resultado->setId($resultado->cd_passageiro);
        $resultado->setNome($resultado->nm_passageiro);
        $resultado->setNascimento($resultado->dt_nascimento);
        $resultado->setSexo($resultado->nm_sexo);
        $resultado->setDocumento($resultado->cd_documento);
        $resultado->setStatusPassageiro($resultado->bln_status);

        if (!$resultado) {
            throw new Exception("Não foi encontrado nenhum registro no banco!", 1);
        }

        return $resultado;
    }

    public static function create(Passageiro $passageiro)
    {
        if (empty($passageiro->getNome())
            or empty($passageiro->getNascimento())
            or empty($passageiro->getSexo())
            or empty($passageiro->getDocumento())
            ) {
            
                throw new Exception("Por favor, preencha corretamente o formulario!", 1);
                return false;
        }

        $sql = 'INSERT INTO tb_passageiro 
                    (nm_passageiro, dt_nascimento, nm_sexo, cd_documento, bln_status) 
                        VALUES (:nome, :dt, :sexo, :documento, :statusPassageiro)';

        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindParam(':nome', $passageiro->getNome());
        $comando->bindParam(':dt', $passageiro->getNascimento());
        $comando->bindParam(':sexo', $passageiro->getSexo());
        $comando->bindParam(':documento', $passageiro->getDocumento());
        $comando->bindParam(':statusPassageiro', $passageiro->getStatusPassageiro());
        $res = $comando->execute();

        if ($res == 0) {
            throw new Exception("Fallha ao inserir o Passageiro", 1);
            return false;
        }

        return true;
    }

    public static function update(Passageiro $passageiro)
    {
        if (empty($passageiro->getId())
            or empty($passageiro->getNome())
            or empty($passageiro->getNascimento())
            or empty($passageiro->getSexo())
            or empty($passageiro->getDocumento())
            ) {
            
                throw new Exception("Por favor, preencha corretamente o formulario!", 1);
                return false;
        }

        $sql = 'UPDATE tb_passageiro SET 
                    nm_passageiro = :nome, dt_nascimento = :dt, nm_sexo = :sexo, 
                        cd_documento = :documento, bln_status = :statusPassageiro
                        WHERE cd_passageiro = :id';

        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindParam(':nome', $passageiro->getNome());
        $comando->bindParam(':dt', $passageiro->getNascimento());
        $comando->bindParam(':sexo', $passageiro->getSexo());
        $comando->bindParam(':documento', $passageiro->getDocumento());
        $comando->bindParam(':statusPassageiro', $passageiro->getStatusPassageiro());
        $comando->bindParam(':id', $passageiro->getId());
        $res = $comando->execute();

        if ($res == 0) {
            throw new Exception("Fallha ao alterar o Passageiro", 1);
            return false;
        }

        return true;
    }

    public static function delete(Passageiro $passageiro)
    {
        $sql = 'DELETE FROM tb_passageiro WHERE cd_passageiro = :id';

        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindParam(':id', $passageiro->getId());
        $res = $comando->execute();

        if ($res == 0) {
            throw new Exception("Falhar ao remover o Passageiro", 1);
            return false;
        }

        return true;
    }

    // GETTERS E SETTERS
    public function getStatusPassageiro() { return $this->statusPassageiro; }
    public function setStatusPassageiro($statusPassageiro) { $this->statusPassageiro = $statusPassageiro; }

}
