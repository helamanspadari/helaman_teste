<?php

// Core
require_once 'App/Core/Core.php';

// Database
require_once 'lib/Database/Connection.php';

// Autoload composer
require_once 'vendor/autoload.php';

// Controllers
require_once 'App/Controller/ErroController.php';
require_once 'App/Controller/LoginController.php';
require_once 'App/Controller/SiteController.php';
require_once 'App/Controller/HomeController.php';
require_once 'App/Controller/MotoristaController.php';
require_once 'App/Controller/PassageiroController.php';
require_once 'App/Controller/CorridaController.php';

// Models
require_once 'App/Model/Pessoa.php';
require_once 'App/Model/Motorista.php';
require_once 'App/Model/Passageiro.php';
require_once 'App/Model/Corrida.php';

// Interfaces
// Estão sendo chamadas das proprias classes

$template = file_get_contents('App/View/Template/layout.html');

ob_start();
    $core = new Core();
    $core->start($_GET);

    $saida = ob_get_contents();
ob_end_clean();

$template_pronto = str_replace('{{area_dinamica}}', $saida, $template);

echo $template_pronto;
