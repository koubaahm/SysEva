<?php
namespace App\Form\Model;

class InvitationModel
{
    private $users = [];
    private $articles = [];

    public function getUsers(): array
    {
        return $this->users;
    }

    public function setUsers(array $users): self
    {
        $this->users = $users;
        return $this;
    }

    public function getArticles(): array
    {
        return $this->articles;
    }

    public function setArticles(array $articles): self
    {
        $this->articles = $articles;
        return $this;
    }
}
