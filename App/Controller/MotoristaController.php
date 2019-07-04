<?php

class MotoristaController{

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        
            $loader = new \Twig\Loader\FilesystemLoader('App/View/_Dashboard/_Motorista');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('home.html');

            $parametros = array();
            try{
                $motoristas = Motorista::selectAll();
                $parametros['motoristas'] = $motoristas;
            } catch (Exception $th) {
                $parametros['erro'] = $th->getMessage();
            }
            
            $conteudo = $template->render($parametros);
            echo $conteudo;     
    }

    public function new()
    {
        try {
            $loader = new \Twig\Loader\FilesystemLoader('App/View/_Dashboard/_Motorista');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('add.html');

            $parametros = array();
            
            $conteudo = $template->render($parametros);
            echo $conteudo;
            
        } catch (Exception $th) {
            echo $th->getMessage();
        }
    }

    public function insert()
    {
        try {
            
            $m = new Motorista();
            $m->setNome($_POST['nome']);
            $m->setNascimento($_POST['nascimento']);
            $m->setSexo($_POST['sexo']);
            $m->setDocumento($_POST['documento']);
            $m->setModeloCarro($_POST['modeloCarro']);
            $m->setStatusMotorista(true);
            $m->setStatusCorridaMotorista(false);
    
            Motorista::create($m);

            echo '<script>alert("Motorista salvo com sucesso!")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=motorista&method=list"</script>';

        } catch (Exception $e) {
            echo '<script>alert("Erro!!\n'. $e->getMessage() .'")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=motorista&method=new"</script>';
        }
    }

    public function alter($id)
    {
        try {
            $loader = new \Twig\Loader\FilesystemLoader('App/View/_Dashboard/_Motorista');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('edit.html');

            $motorista = Motorista::findById($id);

            $parametros = array();
            $parametros['id'] = $motorista->getId();
            $parametros['nome'] = $motorista->getNome();
            $parametros['nascimento'] = $motorista->getNascimento();
            $parametros['sexo'] = $motorista->getSexo();
            $parametros['documento'] = $motorista->getDocumento();
            $parametros['modeloCarro'] = $motorista->getModeloCarro();
            $parametros['statusMotorista'] = $motorista->getStatusMotorista();
            $parametros['statusCorridaMotorista'] = $motorista->getStatusCorridaMotorista();
            
            $conteudo = $template->render($parametros);
            echo $conteudo;
            
        } catch (Exception $th) {
            echo $th->getMessage();
        }
    }

    public function update($id)
    {
        try {
            
            $m = new Motorista();
            $m->setId($id);
            $m->setNome($_POST['nome']);
            $m->setNascimento($_POST['nascimento']);
            $m->setSexo($_POST['sexo']);
            $m->setDocumento($_POST['documento']);
            $m->setModeloCarro($_POST['modeloCarro']);
            $m->setStatusMotorista($_POST['statusMotorista']);
            $m->setStatusCorridaMotorista($_POST['statusCorrida']);

            Motorista::update($m);

            echo '<script>alert("Motorista alterado com sucesso!")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=motorista&method=list"</script>';

        } catch (Exception $e) {

            echo '<script>alert("Erro!!\n'. $e->getMessage() .'")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=motorista&method=alter"</script>';
            
        }
    }

    public function remove($id)
    {
        try {
            
            $motorista = Motorista::findById($id);
            $m = new Motorista();
            $m->setId($motorista->getId());
            $m->setNome($motorista->getNome());
            $m->setNascimento($motorista->getNascimento());
            $m->setSexo($motorista->getSexo());
            $m->setDocumento($motorista->getDocumento());
            $m->setModeloCarro($motorista->getModeloCarro());
            $m->setStatusMotorista($motorista->getStatusMotorista());
            $m->setStatusCorridaMotorista($motorista->getStatusCorridaMotorista());
      
            if ($m->getStatusCorridaMotorista() == false){
                Motorista::delete($m);
            } else {
                echo '<script>alert("Motorista está realizando uma corrida, tente inativa-lo depois!")</script>';
                echo '<script>location.href="http://localhost/helaman_teste/?page=motorista&method=list"</script>';
            }
            
            echo '<script>alert("Status alterado com sucesso!")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=motorista&method=list"</script>';

        } catch (Exception $e) {

            echo '<script>alert("Erro!!\n'. $e->getMessage() .'")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=motorista&method=list"</script>';
            
        }
    }
}
