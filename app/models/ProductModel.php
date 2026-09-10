<?php

defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductModel extends Model
{
    public $db;

    protected $table = 'products';
    protected $primary_key = 'id';
    protected $fillable = ['name', 'description', 'price', 'stock'];
    protected $timestamps = true;

    public function __construct()
    {
        parent::__construct();
        $this->db = lava_instance()->call->database();
        $this->ensure_table();
    }

    public function ensure_table()
    {
        $db = lava_instance()->call->database();
        $db->raw("CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT DEFAULT '',
            price DECIMAL(10,2) NOT NULL DEFAULT 0,
            stock INTEGER NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        return true;
    }

    public function getAll()
    {
        return $this->all();
    }

    public function findById($id)
    {
        return $this->find($id);
    }

    public function store(array $data)
    {
        return $this->insert($data);
    }

    public function updateProduct($id, array $data)
    {
        return $this->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->delete($id);
    }
}
