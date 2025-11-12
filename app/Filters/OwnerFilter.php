<?php
namespace App\Filters;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class OwnerFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('role') !== 'owner') {
            // Jika bukan owner, tendang ke dashboard
            return redirect()->to('dashboard')->with('error', 'Anda tidak memiliki hak akses.');
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) { }
}