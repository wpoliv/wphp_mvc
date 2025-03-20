<?php
class SubCategoria
{
    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getCategoria(): Categoria 
    {
        return $this->categoria;
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

    public function setCategoria(Categoria $categoria): void 
    {
        $this->categoria = $categoria;
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