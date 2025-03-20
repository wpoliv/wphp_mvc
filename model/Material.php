<?php

class Material
{
    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getModelo(): Modelo
    {
        return $this->modelo;
    }

    public function getTombo()
    {
        return $this->tombo;
    }

    public function getOrigem(): Origem
    {
        return $this->origem;
    }

    public function getNuOrigem()
    {
        return $this->nuOrigem;
    }

    public function getNome()
    {
        return $this->Nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getSituacao(): Situacao
    {
        return $this->situacao;
    }

    public function setCodigo($codigo): void
    {
        $this->codigo = $codigo;
    }

    public function setModelo(Modelo $modelo): void
    {
        $this->modelo = $modelo;
    }

    public function setTombo($tombo): void
    {
        $this->tombo = $tombo;
    }

    public function setOrigem(Origem $origem): void
    {
        $this->origem = $origem;
    }

    public function setNuOrigem($nuOrigem): void
    {
        $this->nuOrigem = $nuOrigem;
    }

    public function setNome($Nome): void
    {
        $this->Nome = $Nome;
    }

    public function setDescricao($descricao): void
    {
        $this->descricao = $descricao;
    }

    public function setSituacao(Situacao $situacao): void
    {
        $this->situacao = $situacao;
    }
}
