<?php

class User extends Contact
{
    private ?Role $role = null;
    private string $login = "";
    private string $password = "";
    private bool $active = false;
    
    public function getRole(): Role
    {
        if ($this->role == null)
        {
            $this->role = new Role();
        }
        return $this->role;
    }
    
    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    
    public function getActive(): bool
    {
        return $this->active;
    }
    
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    
    public function setActive(bool $val): void
    {
        $this->active = $val;
    }
}
