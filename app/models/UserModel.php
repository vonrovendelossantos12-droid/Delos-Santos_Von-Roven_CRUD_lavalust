<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: UserModel
 * 
 * Automatically generated via CLI.
 */
class UserModel extends Model {
    protected $table = 'users';
    protected $primary_key = 'id';
    protected $fillable = [];
    protected $guarded = ['id'];

    public function __construct()
    {
        parent::__construct();
    }

    public function find_by_email($email)
    {
        return $this->db->table('users')
            ->where('email', $email)
            ->where('is_active', 1)
            ->get();
    }

    public function find_active_by_id($id)
    {
        return $this->db->table('users')
            ->where('id', $id)
            ->where('is_active', 1)
            ->get();
    }

    public function update_password($id, $password_hash)
    {
        return $this->db->table('users')
            ->where('id', $id)
            ->update(['password' => $password_hash]);
    }
}