<?php

class Role
{
    private int $id = 0;
    private string $name = "";
    private string $description = "";
    private ?array $actions = null;
    
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    
    public function addAction(Action $action): void
    {
        $this->actions[] = $action;
    }
    
    public function getActions(): ?array
    {
        return $this->actions;
    }
}