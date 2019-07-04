<?php

class PassageiroController{

    public function index()
    {
        $this->list();
    }

    public function list()
    {
    
        $loader = new \Twig\Loader\FilesystemLoader('App/View/_Dashboard/_Passageiro');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('home.html');

        $parametros = array();
        
        try {
            $passageiros = Passageiro::selectAll();
            $parametros['passageiros'] = $passageiros;
        } catch (Exception $th) {
            $parametros['erro'] = $th->getMessage();
        }

        $conteudo = $template->render($parametros);
        echo $conteudo;       
    }

    public function new()
    {
        try {
            $loader = new \Twig\Loader\FilesystemLoader('App/View/_Dashboard/_Passageiro');
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
            
            $p = new Passageiro();
            $p->setNome($_POST['nome']);
            $p->setNascimento($_POST['nascimento']);
            $p->setSexo($_POST['sexo']);
            $p->setDocumento($_POST['documento']);
            $p->setStatusPassageiro(false);
    
            Passageiro::create($p);

            echo '<script>alert("Passageiro salvo com sucesso!")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=passageiro&method=list"</script>';

        } catch (Exception $e) {
            echo '<script>alert("Erro!!\n'. $e->getMessage() .'")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=passageiro&method=new"</script>';
        }
    }

    public function alter($id)
    {
        try {
            $loader = new \Twig\Loader\FilesystemLoader('App/View/_Dashboard/_Passageiro');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('edit.html');

            $passageiro = Passageiro::findById($id);

            $parametros = array();
            $parametros['id'] = $passageiro->getId();
            $parametros['nome'] = $passageiro->getNome();
            $parametros['nascimento'] = $passageiro->getNascimento();
            $parametros['sexo'] = $passageiro->getSexo();
            $parametros['documento'] = $passageiro->getDocumento();
            $parametros['statusPassageiro'] = $passageiro->getStatusPassageiro();
            
            $conteudo = $template->render($parametros);
            echo $conteudo;
            
        } catch (Exception $th) {
            echo $th->getMessage();
        }
    }

    public function update($id)
    {
        try {
            
            $p = new Passageiro();
            $p->setId($id);
            $p->setNome($_POST['nome']);
            $p->setNascimento($_POST['nascimento']);
            $p->setSexo($_POST['sexo']);
            $p->setDocumento($_POST['documento']);
            $p->setStatusPassageiro(false);
           
            Passageiro::update($p);

            echo '<script>alert("Passageiro alterado com sucesso!")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=passageiro&method=list"</script>';

        } catch (Exception $e) {

            echo '<script>alert("Erro!!\n'. $e->getMessage() .'")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=passageiro&method=alter"</script>';
            
        }
    }

    public function remove($id)
    {
        try {
            
            $passageiro = Passageiro::findById($id);
            $p = new Passageiro();
            $p->setId($passageiro->getId());
            $p->setNome($passageiro->getNome());
            $p->setNascimento($passageiro->getNascimento());
            $p->setSexo($passageiro->getSexo());
            $p->setDocumento($passageiro->getDocumento());
            $p->setStatusPassageiro($passageiro->getStatusPassageiro());
        
            if ($p->getStatusPassageiro() == false){
                Passageiro::delete($p);
            } else {
                echo '<script>alert("Passageiro está realizando uma corrida, tente remove-lo depois!")</script>';
                echo '<script>location.href="http://localhost/helaman_teste/?page=passageiro&method=list"</script>';
            }
     
            echo '<script>alert("Passageiro removido com sucesso!")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=passageiro&method=list"</script>';

        } catch (Exception $e) {

            echo '<script>alert("Erro!!\n'. $e->getMessage() .'")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=passageiro&method=list"</script>';
            
        }
    }
}
