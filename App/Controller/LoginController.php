<?php

class LoginController {

    public function index()
    {
        $loader = new \Twig\Loader\FilesystemLoader('App/View/_Site/');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('login.html');
        
        $parametros = array();
        
        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
}