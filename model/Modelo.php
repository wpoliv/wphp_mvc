<?php

class Modelo
{
    private int $codigo = 0;
    private ?Tipo $tipo = null;
    private ?Fabricante $fabricante = null;
    private string $nome = "";
    private string $numero = "";
    private string $descricao = "";
    
    public function getCodigo(): int
    {
        return $this->codigo;
    }

    public function getTipo(): Tipo
    {
        if ($this->tipo == null)
        {
            $this->tipo = new Tipo();
        }
        return $this->tipo;
    }

    public function getFabricante(): Fabricante
    {
        if ($this->fabricante == null)
        {
            $this->fabricante = new Fabricante();
        }
        return $this->fabricante;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setCodigo(int $codigo): void
    {
        $this->codigo = $codigo;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setNumero(string $numero): void
    {
        $this->numero = $numero;
    }

    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }
}
