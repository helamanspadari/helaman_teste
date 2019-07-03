<?php

class Core{

    public function start($urlGet)
    {
        if (isset($urlGet['page'])) {
            $controller = ucfirst($urlGet['page'].'Controller');
        } else {
            $controller = 'SiteController';
        }
        
        if (isset($urlGet['method'])) {
            $acao = $urlGet['method'];
        } else {
            $acao = 'index';
        }

        if ( !class_exists($controller) ) {
            $controller = 'ErroController';
        }
        
        if (isset($urlGet['id']) && $urlGet['id'] != null) {
            $id = $urlGet['id'];
        } else {
            $id = null;
        }

        call_user_func_array(array(new $controller, $acao), array('id'=>$id));
        
    }
}
