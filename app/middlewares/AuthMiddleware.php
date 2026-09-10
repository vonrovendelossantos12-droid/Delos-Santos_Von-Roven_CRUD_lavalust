<?php

defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthMiddleware
{
    public function handle($next)
    {
        $session = load_class('Session', 'libraries');

        if (!($session->userdata('logged_in') ?? false)) {
            $redirect = (defined('BASE_URL') && BASE_URL !== '') ? rtrim(BASE_URL, '/') . '/login' : '/login';
            header('Location: ' . $redirect);
            exit;
        }

        return $next();
    }
}
