<?php

class SiteController {

    public function index()
    {
        $loader = new \Twig\Loader\FilesystemLoader('App/View/_Site/');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('index.html');
        
        $parametros = array();
        
        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
}