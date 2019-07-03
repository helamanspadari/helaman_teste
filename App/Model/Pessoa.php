<?php

abstract class Pessoa{

    private $id;
    private $nome;
    private $nascimento;
    private $sexo;
    private $documento;

    public function getId(){ return $this->id; }
    public function getNome(){ return $this->nome; }
    public function getNascimento(){ return $this->nascimento; }
    public function getSexo(){ return $this->sexo; }
    public function getDocumento(){ return $this->documento; }
    
    public function setId($id){ $this->id = $id; }
    public function setNome($nome){ $this->nome = $nome; }
    public function setNascimento($nascimento){ $this->nascimento = $nascimento; }
    public function setSexo($sexo){ $this->sexo = $sexo; }
    public function setDocumento($documento){ $this->documento = $documento; }
   
}
