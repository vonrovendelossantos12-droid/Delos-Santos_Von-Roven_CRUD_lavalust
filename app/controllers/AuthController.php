<?php

defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('session');
    }

    public function loginForm()
    {
        if (($this->session->userdata('logged_in') ?? false)) {
            $this->redirect('/products');
        }

        $this->call->view('auth/login', [
            'title' => 'Login',
            'error' => $this->session->flashdata('error')
        ]);
    }

    public function login()
    {
        $username = strtolower(trim((string) ($_POST['username'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === 'admin' && $password === 'admin123') {
            $this->session->set_userdata([
                'logged_in' => true,
                'username' => 'admin'
            ]);
            $this->redirect('/products');
        }

        $this->session->set_flashdata('error', 'Invalid username or password.');
        $this->redirect('/login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->redirect('/login');
    }

    private function redirect($path)
    {
        $base = rtrim(BASE_URL ?? '', '/');
        $target = $base !== '' ? $base . '/' . ltrim($path, '/') : '/' . ltrim($path, '/');
        header('Location: ' . $target);
        exit;
    }
}
