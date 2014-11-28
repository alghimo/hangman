<?php

return array(
    'dsn'      => 'mysql:host=localhost;dbname=hangman',
    'user'     => 'hangman_site',
    'password' => 'h4ngm4n_s1t3!!',
    'options'  => array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;'),
);
