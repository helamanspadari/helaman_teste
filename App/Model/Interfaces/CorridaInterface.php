<?php

interface CorridaInterface {

    public function iniciarCorrida(Motorista $motorista , Passageiro $passageiro);
    public function terminarCorrida(Motorista $motorista , Passageiro $passageiro);
    public function statusMotorista(Motorista $motorista);
    public function statusPassageiro(Passageiro $passageiro);
    public function calcularValor(Corrida $corrida);
    public function pagarCorrida(Corrida $corrida);

}
