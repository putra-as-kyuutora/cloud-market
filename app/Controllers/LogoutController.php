<?php
namespace App\Controllers;

use App\Core\{Auth, Controller};

class LogoutController extends Controller
{
    /** Dialog konfirmasi (Ya / Batal). */
    public function konfirmasi(): void
    {
        $layout = Auth::is('admin') ? 'layouts/admin' : (Auth::is('penjual') ? 'layouts/penjual' : 'layouts/main');
        $this->view('shared/konfirmasi', [
            'judul' => 'Keluar dari Cloud Market?', 'pesan' => 'Sesi Anda akan diakhiri.',
            'aksi' => url('/logout'), 'batal' => url(Auth::home()), 'labelYa' => 'Ya, Keluar', 'bahaya' => false,
        ], $layout);
    }

    public function logout(): void
    {
        $this->hapusSesi();
        $this->redirect('/login');
    }

    private function hapusSesi(): void { Auth::logout(); }
}
