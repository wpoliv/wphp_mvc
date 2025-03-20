<?php

class Tipo
{
    private int $codigo = 0;
    private string $nome = "";
    private string $descricao = "";
    private Categoria $categoria;
    
    public function getCodigo(): int
    {
        return $this->codigo;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }
    
    public function getCategoria(): Categoria
    {
        return $this->categoria;
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
    
    public function setCategoria(Categoria $categoria): void
    {
        $this->categoria = $categoria;
    }
}