<?php

require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Tweet.php';
require_once __DIR__ . '/models/Like.php';

// Adicionar arrays onde serão guardados os dados
User::init();
Tweet::init();
Like::init();

$user1 = User::create(['name' => 'Alice Johnson', 'email' =>'alice@example.com', 'username' => 'alicejohnson', 'password' => 'password123']);
$user2 = User::create(['name' => 'Bob Smith', 'email' =>'bob@example.com', 'username' => 'bobsmith', 'password' => 'password123']);
$user3 = User::create(['name' => 'Charlie Davis', 'email' =>'charlie@example.com', 'username' => 'charliedavis', 'password' => 'password123']);
$user4 = User::create(['name' => 'Diana Prince', 'email' =>'diana@example.com', 'username' => 'dianaprince', 'password' => 'password123']);
$user5 = User::create(['name' => 'Evan Wright', 'email' =>'evan@example.com', 'username' => 'evanwright', 'password' => 'password123']);

$tweet1 = $user1->tweet('hello world 1');
$tweet2 = $user2->tweet('hello world 2');
$tweet3 = $user3->tweet('hello world 3');
$tweet4 = $user4->tweet('hello world 4');
$tweet5 = $user5->tweet('hello world 5');

$tweet1->addLike($user4);
$tweet1->addLike($user2);
$tweet1->addLike($user3);
$tweet2->addLike($user1);
$tweet3->addLike($user5);

Tweet::list();