<?php

class HomeController{

    public function index()
    {
        $loader = new \Twig\Loader\FilesystemLoader('App/View/_Dashboard/');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('home.html');

        $parametros = array();

        $totaisMotorista = Motorista::selectDashboard();
        $parametros['totalMotorista'] = $totaisMotorista[0][0];
        $parametros['ativosMotorista'] = $totaisMotorista[1][0];
        $parametros['ocupadosMotorista'] = $totaisMotorista[2][0];
        $parametros['livresMotorista'] = $totaisMotorista[3][0];

        $totaisPassageiro = Passageiro::selectDashboard();
        $parametros['totalPassageiro'] = $totaisPassageiro[0][0];
        $parametros['correndoPassageiro'] = $totaisPassageiro[1][0];
        $parametros['livrePassageiro'] = $totaisPassageiro[2][0];
            
        $totaisCorrida = Corrida::selectDashboard();
        $parametros['totalCorrida'] = $totaisCorrida[0][0];
        $parametros['correndoCorrida'] = $totaisCorrida[1][0];
        $parametros['concluidaCorrida'] = $totaisCorrida[2][0];
        $parametros['pagasCorrida'] = $totaisCorrida[3][0];
        
        try {
            $corridas = Corrida::findByStatusCorridaOn();
            $parametros['corridas'] = $corridas;

        } catch (Exception $th) {
            $parametros['erro'] = $th->getMessage();
        }
            
        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
}