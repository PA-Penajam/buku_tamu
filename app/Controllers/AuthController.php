<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * Controller untuk autentikasi admin
 * Login dengan password-only (password dari .env)
 */
class AuthController extends Controller
{
    /**
     * Menampilkan form login
     *
     * @return string
     */
    public function login()
    {
        // Jika sudah login, redirect ke admin
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin');
        }

        $data = [
            'title'    => 'Login Admin',
            'js_files' => ['assets/js/pages/auth_login.js']
        ];

        return view('auth/login', $data);
    }

    /**
     * Proses autentikasi login
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function authenticate()
    {
        $password = $this->request->getPost('password');
        $adminPassword = getenv('ADMIN_PASSWORD') ?: env('ADMIN_PASSWORD', 'admin123');

        // Gunakan password yang sudah di-hash (misalnya dari environment variable)
        // Untuk keamanan, password seharusnya di-set dalam environment sebagai hash
        $storedHash = getenv('ADMIN_PASSWORD_HASH');
        if (!$storedHash) {
            // Fallback: gunakan password_hash jika belum ada hash yang disimpan
            $storedHash = password_hash($adminPassword, PASSWORD_DEFAULT);
        }

        // Validasi password dengan verifikasi yang aman
        if (password_verify($password, $storedHash)) {
            // Set session
            session()->set([
                'isLoggedIn' => true,
                'loginTime'  => time(),
            ]);

            // Redirect ke URL yang dituju atau admin
            $redirectUrl = session()->get('redirect_url') ?? '/admin';
            session()->remove('redirect_url');

            return redirect()->to($redirectUrl);
        }

        return redirect()->back()
            ->with('error', 'Password salah!');
    }

    /**
     * Logout admin
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')
            ->with('success', 'Berhasil logout.');
    }
}
