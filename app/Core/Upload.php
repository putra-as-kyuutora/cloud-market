<?php
namespace App\Core;

/** Upload gambar aman: cek ukuran, MIME asli (finfo), nama acak. */
class Upload
{
    /** @return array [namaFileRelatif|null, pesanError|null] ; $sub = 'barang' | 'bukti' */
    public static function image(string $field, string $sub): array
    {
        if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return [null, null];
        }
        $f = $_FILES[$field];
        if ($f['error'] !== UPLOAD_ERR_OK) return [null, 'Upload gagal (kode ' . $f['error'] . ').'];
        if ($f['size'] > Config::get('upload.max_bytes')) {
            return [null, 'Ukuran file maksimal ' . round(Config::get('upload.max_bytes') / 1048576, 1) . ' MB.'];
        }
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
        $ext  = Config::get('upload.mime')[$mime] ?? null;
        if (!$ext) return [null, 'Format gambar harus JPG, PNG, atau WEBP.'];

        $dir = ROOT_PATH . '/public/uploads/' . $sub;
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $name = bin2hex(random_bytes(12)) . '.' . $ext;
        if (!move_uploaded_file($f['tmp_name'], "$dir/$name")) return [null, 'Gagal menyimpan file.'];
        return [$sub . '/' . $name, null];
    }

    public static function delete(?string $relative): void
    {
        if (!$relative) return;
        $path = realpath(ROOT_PATH . '/public/uploads/' . $relative);
        $root = realpath(ROOT_PATH . '/public/uploads');
        if ($path && $root && strpos($path, $root) === 0 && is_file($path)) @unlink($path);
    }
}
