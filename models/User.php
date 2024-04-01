<?php

require_once __DIR__ . '/../data/Model.php';
require_once __DIR__ . '/Tweet.php';

class User extends Model
{
    protected static Database $__db;
    public string $name;
    public string $email;
    public string $username;
    public string $password;

    protected function canSave(): bool
    {
        if (isset($this->username)) {
            if (empty(User::find(['username' => $this->username]))) {
                return
                    isset($this->name) && 
                    isset($this->email) && 
                    isset($this->password);
            }
        }
        return false;
    }

    public function getTweets(): array
    {
        return Tweet::find(['user_id' => $this->id,]);
    }

    public function addLike(Tweet $tweet): void
    {
        $tweet->addLike($this);
    }

    public function tweet($content): Tweet
    {
        return Tweet::create(['content' => $content, 'user_id' => $this->id]); 
    }
}