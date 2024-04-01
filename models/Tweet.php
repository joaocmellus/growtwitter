<?php

require_once __DIR__ . '/../data/Model.php';
require_once __DIR__ . '/User.php';

class Tweet extends Model
{
    protected static Database $__db;
    public string $user_id;
    public string $content;

    protected function canSave(): bool
    {
        return isset($this->user_id) && isset($this->content);
    }

    public function getLikes(): array
    {
        return Like::find(['tweet_id' => $this->id]);
    }

    public function getUser(): User
    {
        return User::findById($this->user_id);
    }
    
    public function addLike(User $user): void
    {
        Like::create([
            'tweet_id' => $this->id,
            'user_id'  => $user->id
        ]);
    }

    public function show(): void
    {
        $user = $this->getUser();
        echo "@{$user->username}: {$this->content} <br>";
        $likes = $this->getLikes();

        if (count($likes) > 0) {
            $user = User::findById(array_shift($likes)->get('user_id'));
            echo "@{$user->username} ";
            
            if (count($likes) > 0) {
                echo 'e mais ' . count($likes) . 'curtiram';
            } else {
                echo 'curtiu';
            }
        }
        echo '<hr>';
        
    }

    public static function list(): void
    {
        foreach (Tweet::all() as $tweet) {
            $tweet->show();
        }
    }
}