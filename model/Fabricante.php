<?php

class Fabricante
{
    private int $codigo = 0;
    private string $nome = "";
    private string $descricao = "";
    
    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setCodigo($codigo): void
    {
        $this->codigo = $codigo;
    }

    public function setNome($nome): void
    {
        $this->nome = $nome;
    }

    public function setDescricao($descricao): void
    {
        $this->descricao = $descricao;
    }
}