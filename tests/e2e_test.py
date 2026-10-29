import re, requests, sys
B='http://127.0.0.1:8080'
def sess(): return requests.Session()
def csrf(s,path):
    r=s.get(B+path); m=re.search(r'name="_csrf" value="([0-9a-f]+)"',r.text); return m.group(1) if m else None, r
def post(s,path,data=None,files=None,form_path=None):
    t,_=csrf(s,form_path or path)
    d=dict(data or {}); d['_csrf']=t
    return s.post(B+path,data=d,files=files,allow_redirects=True)
ok=0; bad=0
def check(name,cond,extra=''):
    global ok,bad
    if cond: ok+=1; print('PASS',name)
    else: bad+=1; print('FAIL',name,extra)

# --- registrasi & validasi
s=sess()
r=post(s,'/registrasi',{'peran':'penjual','nama':'','username':'a','password':'1','noHp':'x','alamat':''},form_path='/registrasi')
check('registrasi invalid menampilkan error', 'minimal 6' in r.text and 'Nama lengkap wajib' in r.text)
r=post(s,'/registrasi',{'peran':'penjual','nama':'Toko Allan','username':'allan','password':'rahasia1','noHp':'081234567890','alamat':'Jl. Kaliurang 1','namaToko':'Toko Allan','deskripsiToko':'Barang bagus'},form_path='/registrasi')
check('registrasi penjual sukses', 'Registrasi berhasil' in r.text, r.text[:200])
r=post(s,'/registrasi',{'peran':'pembeli','nama':'X','username':'allan','password':'rahasia1','noHp':'081234567890','alamat':'x'},form_path='/registrasi')
check('username duplikat lintas peran ditolak', 'sudah digunakan' in r.text)
r=post(s,'/registrasi',{'peran':'pembeli','nama':'Andrianto','username':'andri','password':'rahasia1','noHp':'081200000000','alamat':'Jl. Godean 5'},form_path='/registrasi')
check('registrasi pembeli sukses','Registrasi berhasil' in r.text)

# --- login
p=sess()
r=post(p,'/login',{'peran':'penjual','username':'allan','password':'salah'},form_path='/login')
check('login salah ditolak','salah' in r.text)
r=post(p,'/login',{'peran':'pembeli','username':'allan','password':'rahasia1'},form_path='/login')
check('login peran tidak cocok ditolak','Login berhasil' not in r.text)
r=post(p,'/login',{'peran':'penjual','username':'allan','password':'rahasia1'},form_path='/login')
check('login penjual sukses -> Produk Saya','Produk Saya' in r.text and 'Login berhasil' in r.text)
check('penjual diarahkan ke /penjual/barang', r.url.endswith('/penjual/barang'))

# --- tambah barang
r=post(p,'/penjual/barang/tambah',{'namaBarang':'Kipas Miyako','idKategori':'1','harga':'50000','stok':'3','deskripsi':'Masih mulus'},form_path='/penjual/barang/tambah')
check('tambah tanpa foto ditolak','Foto barang wajib' in r.text)
r=post(p,'/penjual/barang/tambah',{'namaBarang':'Kipas Miyako','idKategori':'4','harga':'50000','stok':'3','deskripsi':'Masih mulus'},files={'gambar':('f.png',open('/tmp/t/fake.png','rb'),'image/png')},form_path='/penjual/barang/tambah')
check('upload file palsu ditolak (finfo)','JPG, PNG' in r.text)
r=post(p,'/penjual/barang/tambah',{'namaBarang':'Kipas Miyako','idKategori':'4','harga':'50000','stok':'3','deskripsi':'Masih mulus'},files={'gambar':('a.png',open('/tmp/t/a.png','rb'),'image/png')},form_path='/penjual/barang/tambah')
check('tambah barang sukses','Barang berhasil ditambahkan' in r.text and 'Kipas Miyako' in r.text)
r=post(p,'/penjual/barang/tambah',{'namaBarang':'Jaket Vintage','idKategori':'1','harga':'260000','stok':'1','deskripsi':'Hijau'},files={'gambar':('a.png',open('/tmp/t/a.png','rb'),'image/png')},form_path='/penjual/barang/tambah')

# --- edit
r=post(p,'/penjual/barang/1/edit',{'namaBarang':'Kipas Miyako Putih','idKategori':'4','harga':'55000','stok':'3','deskripsi':'Masih mulus'},form_path='/penjual/barang/1/edit')
check('edit barang sukses','Perubahan barang disimpan' in r.text and 'Kipas Miyako Putih' in r.text)

# --- publik cari
g=sess()
r=g.get(B+'/barang?q=kipas'); check('cari kata kunci',  'Kipas Miyako Putih' in r.text and '1 barang' in r.text)
r=g.get(B+'/barang?q=zzzz'); check('cari tidak ketemu','Barang tidak ditemukan' in r.text)
r=g.get(B+'/barang?min=100000'); check('filter harga min','Jaket Vintage' in r.text and 'Kipas' not in r.text)
r=g.get(B+"/barang?q=%25"); check('wildcard % di-escape', 'Barang tidak ditemukan' in r.text)
r=g.get(B+'/barang/1'); check('detail barang + login utk beli','Login untuk membeli' in r.text)
r=g.get(B+'/barang/999'); check('detail 404', r.status_code==404)
r=g.get(B+'/beli/1',allow_redirects=True); check('guest beli -> redirect login','Silakan login' in r.text)

# --- pembeli
b=sess()
post(b,'/login',{'peran':'pembeli','username':'andri','password':'rahasia1'},form_path='/login')
r=b.get(B+'/penjual/barang'); check('pembeli tak bisa akses area penjual', r.status_code==403)
r=post(b,'/beli/1',{'jumlah':'9','alamatPengiriman':'Jl. Godean 5','metodePembayaran':'Transfer Bank'},form_path='/beli/1')
check('beli melebihi stok ditolak','Stok barang tidak mencukupi' in r.text)
r=post(b,'/beli/1',{'jumlah':'2','alamatPengiriman':'Jl. Godean 5','metodePembayaran':'Transfer Bank'},form_path='/beli/1')
check('beli sukses -> halaman bayar','Pembayaran Pesanan #CM-001' in r.text and 'Rp 110.000' in r.text, r.text[-400:])
print(mysql:=__import__('subprocess').run(['mysql','-N','-e','USE cloud_market; SELECT stok FROM Barang WHERE idBarang=1'],capture_output=True,text=True).stdout.strip())
check('stok berkurang jadi 1', __import__('subprocess').run(['mysql','-N','-e','USE cloud_market; SELECT stok FROM Barang WHERE idBarang=1'],capture_output=True,text=True).stdout.strip()=='1')
r=post(b,'/pesanan/1/bayar',{},form_path='/pesanan/1/bayar')
check('bayar tanpa bukti ditolak','Bukti pembayaran wajib' in r.text)
r=post(b,'/pesanan/1/bayar',{},files={'bukti':('b.png',open('/tmp/t/bukti.png','rb'),'image/png')},form_path='/pesanan/1/bayar')
check('kirim bukti -> menunggu konfirmasi','Menunggu konfirmasi penjual' in r.text)

# harga snapshot: ubah harga barang lalu cek total tetap
post(p,'/penjual/barang/1/edit',{'namaBarang':'Kipas Miyako Putih','idKategori':'4','harga':'99000','stok':'1','deskripsi':'Masih mulus'},form_path='/penjual/barang/1/edit')
r=b.get(B+'/pesanan/1'); check('harga snapshot tidak berubah (Rp 110.000)','Rp 110.000' in r.text)

# pembeli lain tidak bisa lihat pesanan
# --- penjual konfirmasi
r=p.get(B+'/penjual/konfirmasi'); check('penjual melihat bukti & tombol','Konfirmasi' in r.text and 'bukti' in r.text)
r=post(p,'/penjual/konfirmasi/1/tolak',{},form_path='/penjual/konfirmasi')
check('tolak pembayaran','ditolak' in r.text.lower())
r=post(b,'/pesanan/1/bayar',{},files={'bukti':('b.png',open('/tmp/t/bukti.png','rb'),'image/png')},form_path='/pesanan/1/bayar')
check('bayar ulang setelah ditolak','Menunggu konfirmasi penjual' in r.text)
r=post(p,'/penjual/konfirmasi/1/terima',{},form_path='/penjual/konfirmasi')
check('konfirmasi pembayaran','Pembayaran dikonfirmasi' in r.text)
r=p.get(B+'/penjual/pesanan'); check('pesanan masuk: tombol Tandai Dikirim','Tandai Dikirim' in r.text)
r=post(p,'/penjual/pesanan/1/kirim',{},form_path='/penjual/pesanan'); check('kirim','sudah dikirim' in r.text)
r=post(b,'/pesanan/1/diterima',{},form_path='/pesanan/1'); check('pembeli terima','Transaksi kamu berhasil' in r.text or 'berhasil' in r.text)

# batal -> stok kembali
r=post(b,'/beli/2',{'jumlah':'1','alamatPengiriman':'Jl X','metodePembayaran':'COD'},form_path='/beli/2')
check('beli COD','Pembayaran Pesanan #CM-002' in r.text)
r=post(b,'/pesanan/2/batal',{},form_path='/pesanan/2/bayar'); check('batal pesanan','dibatalkan' in r.text.lower())
check('stok kembali jadi 1', __import__('subprocess').run(['mysql','-N','-e','USE cloud_market; SELECT stok FROM Barang WHERE idBarang=2'],capture_output=True,text=True).stdout.strip()=='1')

# hapus barang yang sudah pernah dipesan -> ditolak dgn pesan
r=post(p,'/penjual/barang/1/hapus',{},form_path='/penjual/barang/1/hapus'); check('hapus barang berriwayat ditolak halus','tidak bisa dihapus' in r.text)
r=post(p,'/penjual/barang/2/hapus',{},form_path='/penjual/barang/2/hapus'); check('hapus barang tak berriwayat?','tidak bisa dihapus' in r.text or 'berhasil dihapus' in r.text)

# --- laporan penjual
r=p.get(B+'/laporan'); check('laporan penjual + total pendapatan','Rp 110.000' in r.text and 'Total pendapatan' in r.text)

# --- CSRF
r=requests.post(B+'/login',data={'peran':'admin','username':'admin','password':'admin123'}); check('POST tanpa CSRF -> 419', r.status_code==419)

# --- admin
a=sess()
r=post(a,'/login',{'peran':'admin','username':'admin','password':'admin123'},form_path='/login'); check('login admin','Dashboard' in r.text)
r=a.get(B+'/admin'); check('dashboard stats','Total transaksi' in r.text)
r=a.get(B+'/admin/akun?q=allan'); check('kelola akun cari','Toko Allan' in r.text)
r=post(a,'/admin/akun/pembeli/1/status',{'aksi':'nonaktifkan'},form_path='/admin/akun'); check('nonaktif pembeli tanpa kolom aktif -> pesan jelas','migrasi_opsional' in r.text)
r=post(a,'/admin/akun/penjual/1/status',{'aksi':'nonaktifkan'},form_path='/admin/akun'); check('nonaktif penjual','dinonaktifkan' in r.text)
r=p.get(B+'/penjual/barang'); check('sesi penjual nonaktif langsung tertolak','Akun Anda sudah tidak aktif' in r.text)
r=post(a,'/admin/akun/penjual/1/status',{'aksi':'aktifkan'},form_path='/admin/akun'); check('aktifkan lagi','diaktifkan' in r.text)
r=post(a,'/admin/kategori/tambah',{'namaKategori':'Elektronik'},form_path='/admin/kategori/tambah'); check('tambah kategori','Kategori ditambahkan' in r.text)
r=post(a,'/admin/kategori/tambah',{'namaKategori':'elektronik'},form_path='/admin/kategori/tambah'); check('kategori duplikat ditolak','sudah ada' in r.text)
r=post(a,'/admin/kategori/8/hapus',{},form_path='/admin/kategori/8/hapus'); check('hapus kategori kosong','Kategori dihapus' in r.text)
r=post(a,'/admin/kategori/4/hapus',{},form_path='/admin/kategori/4/hapus'); check('hapus kategori terpakai dibatalkan','masih digunakan' in r.text)
r=a.get(B+'/laporan?status=Sudah+Dibayar'); check('laporan admin','Rp 110.000' in r.text and 'Semua Toko' in r.text)
r=a.get(B+'/penjual/barang'); check('admin tak bisa akses area penjual', r.status_code==403)
r=a.get(B+'/profil'); check('profil admin','Profil Saya' in r.text)
r=post(a,'/logout',{},form_path='/logout'); check('logout','Log in' in r.text)
print(f'\n{ok} lulus, {bad} gagal')
