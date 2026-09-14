<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function validateCredentials($username, $password)
    {
       
        return $username === 'admin' && $password === 'admin123';
    }
}