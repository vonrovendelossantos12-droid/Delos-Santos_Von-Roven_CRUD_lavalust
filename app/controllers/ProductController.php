<?php

defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('session');
        $this->call->model('ProductModel');
    }

    public function index()
    {
        $products = $this->ProductModel->getAll();
        $this->call->view('products/index', [
            'title' => 'Products',
            'products' => $products,
            'success' => $this->session->flashdata('success')
        ]);
    }

    public function create()
    {
        $this->call->view('products/create', [
            'title' => 'Add Product',
            'product' => [],
            'errors' => []
        ]);
    }

    public function store()
    {
        $data = $_POST ?? [];
        $errors = $this->validateProduct($data);

        if (!empty($errors)) {
            $this->call->view('products/create', [
                'title' => 'Add Product',
                'product' => $data,
                'errors' => $errors
            ]);
            return;
        }

        $this->ProductModel->store([
            'name' => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'price' => (float) ($data['price'] ?? 0),
            'stock' => (int) ($data['stock'] ?? 0),
        ]);

        $this->session->set_flashdata('success', 'Product added successfully.');
        $this->redirect('/products');
    }

    public function edit($id)
    {
        $product = $this->ProductModel->findById($id);

        if (!$product) {
            show_404();
        }

        $this->call->view('products/edit', [
            'title' => 'Edit Product',
            'product' => $product,
            'errors' => []
        ]);
    }

    public function update($id)
    {
        $product = $this->ProductModel->findById($id);

        if (!$product) {
            show_404();
        }

        $data = $_POST ?? [];
        $errors = $this->validateProduct($data);

        if (!empty($errors)) {
            $this->call->view('products/edit', [
                'title' => 'Edit Product',
                'product' => array_merge($product, $data),
                'errors' => $errors
            ]);
            return;
        }

        $this->ProductModel->updateProduct($id, [
            'name' => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'price' => (float) ($data['price'] ?? 0),
            'stock' => (int) ($data['stock'] ?? 0),
        ]);

        $this->session->set_flashdata('success', 'Product updated successfully.');
        $this->redirect('/products');
    }

    public function delete($id)
    {
        $product = $this->ProductModel->findById($id);

        if (!$product) {
            show_404();
        }

        $this->ProductModel->deleteProduct($id);
        $this->session->set_flashdata('success', 'Product deleted successfully.');
        $this->redirect('/products');
    }

    private function validateProduct(array $data)
    {
        $errors = [];

        if (empty(trim((string) ($data['name'] ?? '')))) {
            $errors['name'] = 'Product name is required.';
        }

        if (empty(trim((string) ($data['price'] ?? '')))) {
            $errors['price'] = 'Price is required.';
        } elseif (!is_numeric($data['price'])) {
            $errors['price'] = 'Price must be numeric.';
        }

        if (!isset($data['stock']) || !is_numeric($data['stock'])) {
            $errors['stock'] = 'Stock must be numeric.';
        }

        return $errors;
    }

    private function redirect($path)
    {
        $base = rtrim(BASE_URL ?? '', '/');
        $target = $base !== '' ? $base . '/' . ltrim($path, '/') : '/' . ltrim($path, '/');
        header('Location: ' . $target);
        exit;
    }
}
