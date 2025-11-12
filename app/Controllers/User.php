<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel; // <-- Gunakan Model yang baru kita buat

class User extends Controller
{
    protected $session;

    public function __construct()
    {
        // Muat service session
        $this->session = \Config\Services::session();
    }

    /**
     * Metode ini menangani login atau registrasi
     * berdasarkan tombol submit yang ditekan.
     */
    public function account()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('login');
        }

        if ($this->request->getPost('signup')) {
            // Jika tombol 'Sign Up' (Register) ditekan
            return $this->register();
        } elseif ($this->request->getPost('signin')) {
            // Jika tombol 'Sign In' (Login) ditekan
            return $this->login();
        }

        return redirect()->to('login');
    }

    /**
     * Menangani logika registrasi.
     * Hanya membuat pengguna dengan peran 'admin'.
     */
    private function register()
    {
        $validation = \Config\Services::validation();
        $rules = [
            'name' => 'required',
            // Pastikan email unik di tabel 'users'
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('register')->withInput()->with('errors', $validation->getErrors());
        }

        $model = new UserModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'admin' // <-- PENTING: Pendaftaran hanya untuk 'admin'
        ];

        $model->save($data);

        return redirect()->to('login')->with('success', 'Registrasi admin berhasil. Silakan login.');
    }

    /**
     * Menangani logika login.
     * Memverifikasi pengguna dan menyimpan 'role' ke session.
     */
    private function login()
    {
        $validation = \Config\Services::validation();
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('login')->withInput()->with('errors', $validation->getErrors());
        }

        $model = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan email di tabel 'users'
        $user = $model->where('email', $email)->first();

        // Verifikasi user dan password
        if ($user && password_verify($password, $user['password'])) {
            // Login sukses, siapkan data session
            $sessData = [
                'isLoggedIn' => true,
                'user_id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'] // <-- PENTING: Simpan peran (owner/admin)
            ];

            $this->session->set($sessData);
            $this->session->setFlashdata('login_success', true);

            return redirect()->to('dashboard');
        } else {
            // Login gagal
            return redirect()->to('login')->with('errors', ['login' => 'Email atau password salah.']);
        }
    }

    /**
     * Menangani logika logout.
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('login');
    }
}
