<?php

require_once __DIR__ . '/../data/Model.php';

class Like extends Model
{
    protected static Database $__db;
    public string $tweet_id;
    public string $user_id;

    protected function canSave(): bool
    {
        if (isset($this->tweet_id) && isset($this->user_id)) {
            if (empty(Like::find(['tweet_id' => $this->tweet_id, 'user_id' => $this->user_id])))
            {
                return true;
            }
        }
        return false;
    }
}