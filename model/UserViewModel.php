<?php

class UserViewModel
{
    private int $userId = 0;
    private string $login = "";
    private int $roleId = 0;
    private string $password = "";
    private string $confirm = "";
    private bool $newRecord = false;


    public function getUserId(): int
    {
        return $this->userId;
    }
    
    public function getLogin(): string
    {
        return $this->login;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getConfirm(): string
    {
        return $this->confirm;
    }
    
    public function getNewRecord(): bool
    {
        return $this->newRecord;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
    
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setConfirm(string $confirm): void
    {
        $this->confirm = $confirm;
    }
    
    public function setNewRecord(bool $newRecord): void
    {
        $this->newRecord = $newRecord;
    }
}
