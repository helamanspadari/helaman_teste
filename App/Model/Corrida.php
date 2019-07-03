<?php

require_once 'Interfaces/CorridaInterface.php';

class Corrida implements CorridaInterface{

    private $id;
    private $motorista;
    private $passageiro;
    private $valor;
    private $distancia;
    private $dt_hr_inicio;
    private $dt_hr_termino;
    private $statusCorrida;     // true = iniciada - false = terminada
    private $statusPagamento;   // true = pago - false = a pagar

    public static function selectDashboard()
    {
        $on = true;
        $off = false;
        $sql1 = 'SELECT COUNT(*) FROM tb_corrida';
        $sql2 = 'SELECT COUNT(*) FROM tb_corrida WHERE  bln_status_corrida = :statusCorridaOn';
        $sql3 = 'SELECT COUNT(*) FROM tb_corrida WHERE  bln_status_corrida = :statusCorridaOff';
        $sql4 = 'SELECT COUNT(*) FROM tb_corrida WHERE  bln_status_pagamento = :statusPagamentoOn';

        $comando1 = Connection::getConecta()->prepare( $sql1 );
        $comando1->execute();
        $c1 = $comando1->fetch(); 

        $comando2 = Connection::getConecta()->prepare( $sql2 );
        $comando2->bindValue(':statusCorridaOn', $on);
        $comando2->execute();
        $c2 = $comando2->fetch(); 
        
        $comando3 = Connection::getConecta()->prepare( $sql3 );
        $comando3->bindValue(':statusCorridaOff', $off);
        $comando3->execute();
        $c3 = $comando3->fetch(); 
        
        $comando4 = Connection::getConecta()->prepare( $sql4 );
        $comando4->bindValue(':statusPagamentoOn', $on);
        $comando4->execute();
        $c4 = $comando4->fetch(); 

        $resultados = array();

        $resultados[] = $c1;
        $resultados[] = $c2;
        $resultados[] = $c3;
        $resultados[] = $c4;

        return $resultados;
    }

    // CRUD
    public static function findByStatusCorridaOn()
    {
        $sql = 'SELECT * FROM tb_corrida WHERE bln_status_corrida = true';

        $comando = Connection::getConecta()->prepare( $sql );
        $comando->execute();

        $resultados = array();

        while ( $row = $comando->fetchObject('Corrida')) {
            $row->setId($row->cd_corrida);
            $row->setMotorista($row->cd_motorista);
            $row->setPassageiro($row->cd_passageiro);
            $row->setValor($row->vl_corrida);
            $row->setDistancia($row->vl_distancia);
            $row->setDt_Hr_Inicio($row->dt_hr_inicio);
            $row->setDt_Hr_Termino($row->dt_hr_termino);
            $row->setStatusCorrida($row->bln_status_corrida);
            $row->setStatusPagamento($row->bln_status_pagamento);

            $resultados[] = $row;
        }

        if (!$resultados) {
            throw new Exception("Não há corridas sendo feitas no momento!", 1);
        }

        return $resultados;
    }

    public static function selectAll()
    {
        $sql = 'SELECT c.*, m.nm_motorista, p.nm_passageiro 
                    FROM tb_corrida as c, tb_motorista as m, tb_passageiro as p 
                        WHERE c.cd_motorista = m.cd_motorista and c.cd_passageiro = p.cd_passageiro';

        $comando = Connection::getConecta()->prepare( $sql );
        $comando->execute();

        $resultados = array();

        while ( $row = $comando->fetchObject('Corrida')) {
            $row->setId($row->cd_corrida);
            $row->setMotorista($row->cd_motorista);
            $row->setPassageiro($row->cd_passageiro);
            $row->setValor($row->vl_corrida);
            $row->setDistancia($row->vl_distancia);
            $row->setDt_Hr_Inicio($row->dt_hr_inicio);
            $row->setDt_Hr_Termino($row->dt_hr_termino);
            $row->setStatusCorrida($row->bln_status_corrida);
            $row->setStatusPagamento($row->bln_status_pagamento);

            $resultados[] = $row;
        }

        if (!$resultados) {
            throw new Exception("Não foi encontrado nenhum registro no banco!", 1);
        }

        return $resultados;
    }

    public static function findById($id)
    {
        $sql = 'SELECT c.*, m.nm_motorista, p.nm_passageiro 
                    FROM tb_corrida as c, tb_motorista as m, tb_passageiro as p 
                        WHERE c.cd_motorista = m.cd_motorista and c.cd_passageiro = p.cd_passageiro and c.cd_corrida = :id';

        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindValue(':id', $id);
        $comando->execute();

        $resultados = $comando->fetchObject('Corrida');

        $resultados->setId($resultados->cd_corrida);
        $resultados->setMotorista($resultados->cd_motorista);
        $resultados->setPassageiro($resultados->cd_passageiro);
        $resultados->setValor($resultados->vl_corrida);
        $resultados->setDistancia($resultados->vl_distancia);
        $resultados->setDt_Hr_Inicio($resultados->dt_hr_inicio);
        $resultados->setDt_Hr_Termino($resultados->dt_hr_termino);
        $resultados->setStatusCorrida($resultados->bln_status_corrida);
        $resultados->setStatusPagamento($resultados->bln_status_pagamento);        

        if (!$resultados) {
            throw new Exception("Não foi encontrado nenhum registro no banco!", 1);
        }

        return $resultados;
    }

    public static function create(Corrida $corrida, Motorista $motorista, Passageiro $passageiro)
    {
        $corrida->iniciarCorrida($motorista, $passageiro);
       
        $sql = 'INSERT INTO tb_corrida 
                    (cd_motorista, cd_passageiro, bln_status_pagamento, bln_status_corrida, dt_hr_inicio) 
                        VALUES (:motorista, :passageiro, :statusPagamento, :statusCorrida, :inicio)';
        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindValue(':motorista', $motorista->getId());
        $comando->bindValue(':passageiro', $passageiro->getId());
        $comando->bindValue(':statusPagamento', false);
        $comando->bindValue(':statusCorrida', true);
        $comando->bindValue(':inicio', $corrida->getDt_Hr_Inicio());
        $res = $comando->execute();

        $sql2 = 'UPDATE tb_motorista SET bln_status_corrida = :statusCorrida WHERE cd_motorista = :id';
        $comando2 = Connection::getConecta()->prepare( $sql2 );
        $comando2->bindValue(':statusCorrida', true);
        $comando2->bindValue(':id', $motorista->getId());
        $res2 = $comando2->execute();

        $sql3 = 'UPDATE tb_passageiro SET bln_status = :statusCorrida WHERE cd_passageiro = :id';
        $comando3 = Connection::getConecta()->prepare( $sql3 );
        $comando3->bindValue(':statusCorrida', true);
        $comando3->bindValue(':id', $passageiro->getId());
        $res3 = $comando3->execute();

        if (($res == 0) and ($res2 == 0) and ($res3 == 0)){
            throw new Exception("Erro ao iniciar a corrida", 1);
            return false;
        }

        return true;
    }

    public static function complement(Corrida $corrida)
    {        
        $sql = 'UPDATE tb_corrida SET vl_distancia = :distancia WHERE cd_corrida = :id';

        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindValue(':distancia', $corrida->getDistancia());
        $comando->bindValue(':id', $corrida->getId());
        $res = $comando->execute();

        if ($res == 0){
            throw new Exception("Erro ao completar corrida", 1);
            return false;
        }

        return true;
    }

    public static function update(Corrida $corrida)
    {

    }

    public static function delete(Corrida $corrida) // finaliza corrida
    {
        $motorista = Motorista::findById($corrida->getMotorista());
        $passageiro = Passageiro::findById($corrida->getPassageiro());
        
        $corrida->calcularValor($corrida);
        $corrida->terminarCorrida($motorista, $passageiro);
        
        $sql = 'UPDATE tb_corrida SET 
                    vl_corrida = :valor, dt_hr_termino = :termino, bln_status_corrida = :statusCorrida 
                        WHERE cd_corrida = :id';
        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindValue(':valor', $corrida->getValor());
        $comando->bindValue(':termino', $corrida->getDt_Hr_Termino());
        $comando->bindValue(':statusCorrida', false);
        $comando->bindValue(':id', $corrida->getId());
        $res = $comando->execute();

        $sql2 = 'UPDATE tb_motorista SET bln_status_corrida = :statusCorrida WHERE cd_motorista = :id';
        $comando2 = Connection::getConecta()->prepare( $sql2 );
        $comando2->bindValue(':statusCorrida', false);
        $comando2->bindValue(':id', $motorista->getId());
        $res2 = $comando2->execute();

        $sql3 = 'UPDATE tb_passageiro SET bln_status = :statusCorrida WHERE cd_passageiro = :id';
        $comando3 = Connection::getConecta()->prepare( $sql3 );
        $comando3->bindValue(':statusCorrida', false);
        $comando3->bindValue(':id', $passageiro->getId());
        $res3 = $comando3->execute();

        if (($res == 0) and ($res2 == 0) and ($res3 == 0)){
            throw new Exception("Erro ao terminar a corrida", 1);
            return false;
        }

        return true;
    }

    public static function pay(Corrida $corrida) // pagamento corrida
    {
        $corrida->pagarCorrida($corrida);
        
        $sql = 'UPDATE tb_corrida SET 
                    bln_status_pagamento = :statusPagamento 
                        WHERE cd_corrida = :id';
        $comando = Connection::getConecta()->prepare( $sql );
        $comando->bindValue(':statusPagamento', true);
        $comando->bindValue(':id', $corrida->getId());
        $res = $comando->execute();

        if ($res == 0){
            throw new Exception("Falha ao pagar corrida", 1);
            return false;
        }

        return true;
    }

    // FUNCTIONS DA INTERFACE
    public function iniciarCorrida(Motorista $motorista, Passageiro $passageiro)
    {
        if(($this->getStatusCorrida() == false) && ($motorista->getStatusMotorista() == true) && ($motorista->getStatusCorridaMotorista() == false)){
            $this->setStatusCorrida(true);
            $this->setStatusPagamento(false);
            echo "Corrida iniciada<br>";
            date_default_timezone_set('America/Sao_Paulo');
            echo date('d/m/Y \à\s H:i:s');
            $this->setDt_Hr_Inicio(date('Y-m-d H:i:s'));
            
            $this->statusMotorista($motorista);
            $this->statusPassageiro($passageiro);
            
        } else {
            echo 'Não pode iniciar corrida';
        }
    }

    public function terminarCorrida(Motorista $motorista, Passageiro $passageiro)
    {
        if(($this->getStatusCorrida() == true) and ($motorista->getStatusMotorista() == true) and ($motorista->getStatusCorridaMotorista() == true)){
            $this->setStatusCorrida(false);
            $this->setStatusPagamento(true);
            echo "Corrida finalizada<br>";
            date_default_timezone_set('America/Sao_Paulo');
            echo date('d/m/Y \à\s H:i:s');
            $this->setDt_Hr_Termino(date('Y-m-d H:i:s'));

            $this->statusMotorista($motorista);
            $this->statusPassageiro($passageiro);
        } else {
            echo 'Não pode encerrar a corrida..';
        }
    }

    public function statusMotorista(Motorista $motorista)
    {
        if ($this->getStatusCorrida() == true) {
            $motorista->setStatusCorridaMotorista(true);
            echo '<br>corrida acontecendo e motorista ocupado';
        } else {
            $motorista->setStatusCorridaMotorista(false);
            echo '<br>corrida nenhuma e motorista livre';
        }
    }

    public function statusPassageiro(Passageiro $passageiro)
    {
        if ($this->getStatusCorrida() == true) {
            $passageiro->setStatusPassageiro(true);
            echo '<br>corrida acontecendo e passageiro na corrida';
        } else {
            $passageiro->setStatusPassageiro(false);
            echo '<br>corrida nenhuma e passageiro livre';
        }
    }

    // Pode substituir o processo dentro da function terminarCorrida, chamando outras functions
    public function calcularValor(Corrida $corrida)
    {
        // tentar fazer por tempo, pegando horario de inicio e termino e calcular..
        if ($corrida->getDistancia() < 5){
            $corrida->setValor(8);
            echo '<br>Distancia percorrida '.$corrida->getDistancia().' KM<br>Valor da Corrida<br>R$ '.$corrida->getValor();
        }
        elseif ($corrida->getDistancia() < 15){
            $corrida->setValor(16);
            echo '<br>Distancia percorrida '.$corrida->getDistancia().' KM<br>Valor da Corrida<br>R$ '.$corrida->getValor();
        }
        elseif ($corrida->getDistancia() < 30){
            $corrida->setValor(28);
            echo '<br>Distancia percorrida '.$corrida->getDistancia().' KM<br>Valor da Corrida<br>R$ '.$corrida->getValor();
        }
        elseif ($corrida->getDistancia() < 50){
            $corrida->setValor(43);
            echo '<br>Distancia percorrida '.$corrida->getDistancia().' KM<br>Valor da Corrida<br>R$ '.$corrida->getValor();
        }
        elseif ($corrida->getDistancia() < 100){
            $corrida->setValor(85);
            echo '<br>Distancia percorrida '.$corrida->getDistancia().' KM<br>Valor da Corrida<br>R$ '.$corrida->getValor();
        }
        elseif ($corrida->getDistancia() < 120){
            $corrida->setValor(99);
            echo '<br>Distancia percorrida '.$corrida->getDistancia().' KM<br>Valor da Corrida<br>R$ '.$corrida->getValor();
        }
        else {
            //calcular a diferença acrescetando x valor
        }
    }
    public function pagarCorrida(Corrida $corrida)
    {
        if ($corrida->getStatusCorrida() == false and $corrida->getStatusPagamento() == false) {
            $corrida->setStatusPagamento(true);
        }
    }


    // CONSTRUTOR
    /*public function __construct(Motorista $motorista, Passageiro $passageiro, $distancia)
    {
        $this->setMotorista($motorista);
        $this->setPassageiro($passageiro);
        $this->setDistancia($distancia);
        $this->setStatusCorrida(false);
        $this->setStatusPagamento(false);
    }*/

    // GETTERS E SETTERS
    public function getId() { return $this->id; }
    public function getMotorista() { return $this->motorista; }
    public function getPassageiro() { return $this->passageiro; }
    public function getValor() { return $this->valor; }
    public function getDistancia() { return $this->distancia; }
    public function getDt_Hr_Inicio() { return $this->dt_hr_inicio; }
    public function getDt_Hr_Termino() { return $this->dt_hr_termino; }
    public function getStatusCorrida() { return $this->statusCorrida; }
    public function getStatusPagamento() { return $this->statuspagamento; }
    
    public function setId($id) { $this->id = $id; }
    public function setMotorista($motorista) { $this->motorista = $motorista; }
    public function setPassageiro($passageiro) { $this->passageiro = $passageiro; }
    public function setValor($valor) { $this->valor = $valor; }
    public function setDistancia($distancia) { $this->distancia = $distancia; }
    public function setDt_Hr_Inicio($dt_hr_inicio) { $this->dt_hr_inicio =$dt_hr_inicio; }
    public function setDt_Hr_Termino($dt_hr_termino) { $this->dt_hr_termino =$dt_hr_termino; }
    public function setStatusCorrida($statusCorrida) { $this->statusCorrida = $statusCorrida; }
    public function setStatusPagamento($statuspagamento) { $this->statuspagamento = $statuspagamento; }
}
