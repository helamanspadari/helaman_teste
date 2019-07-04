<?php

class CorridaController{

    public function index()
    {
        $this->list();
    }
    
    public function show($id)
    {
        $loader = new \Twig\Loader\FilesystemLoader('App/View/_Dashboard/_Corrida');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('show.html');

        $corrida = Corrida::findById($id);

        $parametros = array();
        $parametros['corrida'] = $corrida;


        $conteudo = $template->render($parametros);
        echo $conteudo; 
    }


    public function list()
    {
        $loader = new \Twig\Loader\FilesystemLoader('App/View/_Dashboard/_Corrida');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('home.html');

        $parametros = array();
        
        try {
            $corridas = Corrida::selectAll();
            $parametros['corridas'] = $corridas;

        } catch (Exception $e) {
            $parametros['erro'] = $e->getMessage();
        }
            
        $conteudo = $template->render($parametros);
        echo $conteudo;        
    }

    public function new()
    {
        try {
            $loader = new \Twig\Loader\FilesystemLoader('App/View/_Dashboard/_Corrida');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('add.html');

            $motoristasAtivosLivres = Motorista::findByStatusAndCorrida();
            $passageirosLivres = Passageiro::findByStatus();

            $parametros = array();
            $parametros['motoristasAtivosLivres'] = $motoristasAtivosLivres;
            $parametros['passageirosLivres'] = $passageirosLivres;
            
            $conteudo = $template->render($parametros);
            echo $conteudo;

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function insert()
    {
        try {
            $m = Motorista::findById($_POST['motoristaAtivoLivre']);
            $p = Passageiro::findById($_POST['passageiroLivre']);

            $c = new Corrida();
            $c->setMotorista($m);
            $c->setPassageiro($p);

            Corrida::create($c, $m, $p);

            echo '<script>alert("Corrida iniciada!")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=corrida&method=list"</script>';

        } catch (Exception $e) {
            echo '<script>alert("Erro!!\n'. $e->getMessage() .'")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=corrida&method=new"</script>';
        }
    }

    public function alter($id)
    {
        try {
            $corrida = Corrida::findById($id);

            if ($corrida->getStatusCorrida() == true) {
                $loader = new \Twig\Loader\FilesystemLoader('App/View/_Dashboard/_Corrida');
                $twig = new \Twig\Environment($loader);
                $template = $twig->load('complement.html');

                $parametros = array();
                $parametros['id'] = $corrida->getId();
            
                $conteudo = $template->render($parametros);
                echo $conteudo;
            
            } else {
                echo '<script>alert("Esta corrida ja foi finalizada!")</script>';
                echo '<script>location.href="http://localhost/helaman_teste/?page=corrida&method=list"</script>';
            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function complementInsert($id)
    {
        try {
            $corrida = Corrida::findById($id);

            $corrida->setDistancia($_POST['distancia']);
            
            Corrida::complement($corrida);

            echo '<script>alert("Distancia acrescentada!")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=corrida&method=list"</script>';

        } catch (Exception $e) {
            echo '<script>alert("Erro!!\n'. $e->getMessage() .'")</script>';
            echo '<script>location.href="http://localhost/helaman_teste/?page=corrida&method=alter&id='.$corrida->getId().'"</script>';
        }
    }

    public function update($id)
    {
        try {
            


        } catch (Exception $e) {
            
        }
    }

    public function remove($id) // finalizar corrida
    {
        try {
            $corrida = Corrida::findById($id);

            if ($corrida->getStatusCorrida() == true) {
            
                Corrida::delete($corrida);

                echo '<script>alert("Corrida terminada com sucesso!!")</script>';
                echo '<script>location.href="http://localhost/helaman_teste/?page=corrida&method=list"</script>';
                
            } else {
                echo '<script>alert("Esta corrida ja foi finalizada!")</script>';
                echo '<script>location.href="http://localhost/helaman_teste/?page=corrida&method=list"</script>';
            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function pay($id) // finalizar corrida
    {
        try {
            $corrida = Corrida::findById($id);

            if ($corrida->getStatusCorrida() == false and $corrida->getStatusPagamento() == false) {
            
                Corrida::pay($corrida);

                echo '<script>alert("Corrida paga com sucesso!!")</script>';
                echo '<script>location.href="http://localhost/helaman_teste/?page=corrida&method=list"</script>';
                
            } else {
                echo '<script>alert("Esta corrida ja foi paga!")</script>';
                echo '<script>location.href="http://localhost/helaman_teste/?page=corrida&method=list"</script>';
            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}