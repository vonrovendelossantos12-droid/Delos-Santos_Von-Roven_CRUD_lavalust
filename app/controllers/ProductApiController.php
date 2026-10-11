<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductApiController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('api');
        $this->call->model('UserModel');
        $this->call->model('ProductModel');
    }

    public function health()
    {
        $this->api->respond(['status' => 'ok']);
    }

    public function login()
    {
        $this->api->rate_limit('product-api-login', 10, 60);
        $this->api->require_method('POST');

        $input = $this->json_input();
        $email = filter_var(trim((string) ($input['email'] ?? '')), FILTER_VALIDATE_EMAIL);
        $password = $input['password'] ?? null;

        if (!$email || !is_string($password) || $password === '') {
            $this->api->respond_error('A valid email and password are required.', 422);
        }

        $user = $this->UserModel->find_by_email($email);
        $stored_password = (string) ($user['password'] ?? '');
        $password_matches = $user && password_verify($password, $stored_password);

        if (!$password_matches && $user && hash_equals($stored_password, $password)) {
            $password_matches = true;
            $this->UserModel->update_password(
                $user['id'],
                password_hash($password, PASSWORD_DEFAULT)
            );
        }

        if (!$user || !$password_matches) {
            $this->api->respond_error('Invalid email or password.', 401);
        }

        $token = $this->api->encode_jwt([
            'sub' => (int) $user['id'],
            'role' => $user['role'],
        ]);

        $this->api->respond([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => (int) config_item('payload_token_expiration'),
            'user' => [
                'id' => (int) $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
            ],
        ]);
    }

    public function products()
    {
        $this->require_authenticated_user();
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        if ($method === 'GET') {
            $this->api->respond(['data' => $this->ProductModel->read()]);
        }

        if ($method === 'POST') {
            $product = $this->validated_product($this->json_input());
            $id = $this->ProductModel->create(
                $product['product_name'],
                $product['description'],
                $product['price'],
                $product['quantity']
            );

            $this->api->respond([
                'message' => 'Product created.',
                'data' => $this->ProductModel->find($id),
            ], 201);
        }

        $this->api->respond_error('Method Not Allowed.', 405);
    }

    public function product($id)
    {
        $this->require_authenticated_user();
        $id = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if (!$id) {
            $this->api->respond_error('Invalid product ID.', 400);
        }

        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $existing = $this->ProductModel->find($id);

        if (!$existing) {
            $this->api->respond_error('Product not found.', 404);
        }

        if ($method === 'GET') {
            $this->api->respond(['data' => $existing]);
        }

        if ($method === 'PUT' || $method === 'PATCH') {
            $input = $this->json_input();
            if ($method === 'PATCH') {
                $input = array_merge($existing, $input);
            }

            $product = $this->validated_product($input);
            $this->ProductModel->update(
                $id,
                $product['product_name'],
                $product['description'],
                $product['price'],
                $product['quantity']
            );

            $this->api->respond([
                'message' => 'Product updated.',
                'data' => $this->ProductModel->find($id),
            ]);
        }

        if ($method === 'DELETE') {
            $this->ProductModel->delete($id);
            $this->api->respond(['message' => 'Product deleted.']);
        }

        $this->api->respond_error('Method Not Allowed.', 405);
    }

    private function require_authenticated_user()
    {
        $payload = $this->api->require_jwt();
        $user_id = filter_var($payload['sub'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if (!$user_id || !$this->UserModel->find_active_by_id($user_id)) {
            $this->api->respond_error('Unauthorized.', 401);
        }
    }

    private function json_input()
    {
        $input = json_decode((string) file_get_contents('php://input'));

        if (json_last_error() !== JSON_ERROR_NONE || !is_object($input)) {
            $this->api->respond_error('A valid JSON object is required.', 400);
        }

        return get_object_vars($input);
    }

    private function validated_product(array $input)
    {
        $name = $input['product_name'] ?? null;
        $description = $input['description'] ?? null;
        $price = $input['price'] ?? null;
        $quantity = $input['quantity'] ?? null;

        $name = is_string($name) ? trim($name) : null;
        $name_length = $name === null ? false : preg_match_all('/./us', $name);
        if ($name === null || $name === '' || $name_length === false || $name_length > 100) {
            $this->api->respond_error('Product name is required and must be at most 100 characters.', 422);
        }

        $description = $description ?? '';
        if (!is_string($description)) {
            $this->api->respond_error('Product description must be a string.', 422);
        }

        $price_string = is_int($price) || is_float($price) || is_string($price)
            ? (string) $price
            : '';
        if (!preg_match('/^\d{1,8}(?:\.\d{1,2})?$/', $price_string)) {
            $this->api->respond_error('Price must be a non-negative amount with up to two decimal places.', 422);
        }

        $quantity_string = is_int($quantity) || is_string($quantity) ? (string) $quantity : '';
        if (!preg_match('/^\d+$/', $quantity_string) || (float) $quantity_string > 2147483647) {
            $this->api->respond_error('Quantity must be a non-negative whole number.', 422);
        }

        return [
            'product_name' => $name,
            'description' => trim($description),
            'price' => number_format((float) $price_string, 2, '.', ''),
            'quantity' => (int) $quantity_string,
        ];
    }
}
