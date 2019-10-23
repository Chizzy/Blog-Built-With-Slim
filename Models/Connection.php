<?php

class Connection extends PDO
{
    public function __construct($dsn, $username = null, $passwd = null, $options = null)
    {
        parent::__construct($dsn, $username, $passwd, $options);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}