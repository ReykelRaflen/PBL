class Naskah extends Model
{
    use HasFactory;

    /**
     * Get the URL of the naskah file.
     *
     * @return string
     */
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_naskah);
    }

    /**
     * Get the excerpt of the naskah description.
     *
     * @param int $length
     * @return string
     */
    public function getExcerpt($length = 100)
    {
        return Str::limit($this->deskripsi_singkat, $length);
    }
}
   use Illuminate\Http\Request;
   use App\Models\Naskah;

   public function store(Request $request) {
       $request->validate([
           'judul' => 'required|string|max:255',
           'penulis' => 'required|string|max:255',
           'deskripsi' => 'required|string',
           'naskah' => 'required|file|mimes:pdf,doc,docx|max:20480',
           'buktipembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
           'paket' => 'required|string',
       ]);

       // Handle file uploads
       $naskahPath = $request->file('naskah')->store('naskah');
       $buktiPath = $request->file('buktipembayaran')->store('bukti');

       // Save to database
       Naskah::create([
           'judul' => $request->judul,
           'pengarang' => $request->penulis,
           'deskripsi_singkat' => $request->deskripsi,
           'file_naskah' => $naskahPath,
           'status' => 'Dalam Review', // Default status
           // Add other fields if necessary
       ]);

       return redirect()->back()->with('success', 'Pengajuan berhasil!');
   }
   