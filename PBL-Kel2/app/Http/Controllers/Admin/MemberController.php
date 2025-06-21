<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware(function ($request, $next) {
    //         if (auth()->user()->role !== 'admin') {
    //             abort(403, 'Unauthorized. Admin access required.');
    //         }
    //         return $next($request);
    //     });
    // }

    public function index()
    {
        $members = User::where('role', 'user') // Sesuai dengan default role di migration
                      ->latest()
                      ->paginate(15);

        // Statistics
        $totalMembers = User::where('role', 'user')->count();
        $verifiedMembers = User::where('role', 'user')->where('is_verified', true)->count();
        $unverifiedMembers = User::where('role', 'user')->where('is_verified', false)->count();
        $newMembersThisMonth = User::where('role', 'user')
                                  ->whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count();

        return view('admin.members.index', compact(
            'members', 
            'totalMembers', 
            'verifiedMembers', 
            'unverifiedMembers', 
            'newMembersThisMonth'
        ));
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date|before:today',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_verified' => 'boolean',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'agama' => $request->agama,
            'role' => 'user', // Set role as user (member)
            'is_verified' => $request->boolean('is_verified'),
        ];

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('members/photos', 'public');
            $userData['foto'] = $fotoPath;
        }

        // Set email_verified_at if is_verified is true
        if ($request->boolean('is_verified')) {
            $userData['email_verified_at'] = now();
        }

        $member = User::create($userData);

        return redirect()->route('members.index')
                        ->with('success', 'Member created successfully');
    }

    public function show(User $member)
    {
        // Pastikan hanya menampilkan user dengan role 'user' (member)
        if ($member->role !== 'user') {
            abort(404);
        }

        return view('admin.members.show', compact('member'));
    }

    public function edit(User $member)
    {
        // Pastikan hanya mengedit user dengan role 'user' (member)
        if ($member->role !== 'user') {
            abort(404);
        }

        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, User $member)
    {
        // Pastikan hanya mengupdate user dengan role 'user' (member)
        if ($member->role !== 'user') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date|before:today',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_verified' => 'boolean',
        ]);

        $updateData = $request->only([
            'name', 
            'email', 
            'phone', 
            'address', 
            'birthdate', 
            'gender', 
            'agama',
            'is_verified'
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($member->foto && Storage::disk('public')->exists($member->foto)) {
                Storage::disk('public')->delete($member->foto);
            }

            // Store new foto
            $fotoPath = $request->file('foto')->store('members/photos', 'public');
            $updateData['foto'] = $fotoPath;
        }

        // Set email_verified_at if is_verified is true
        if ($request->boolean('is_verified') && !$member->is_verified) {
            $updateData['email_verified_at'] = now();
        } elseif (!$request->boolean('is_verified') && $member->is_verified) {
            $updateData['email_verified_at'] = null;
        }

        $member->update($updateData);

        return redirect()->route('members.show', $member)
                        ->with('success', 'Member updated successfully');
    }

    public function destroy(User $member)
    {
        // Pastikan hanya menghapus user dengan role 'user' (member)
        if ($member->role !== 'user') {
            abort(404);
        }

        // Delete foto if exists
        if ($member->foto && Storage::disk('public')->exists($member->foto)) {
            Storage::disk('public')->delete($member->foto);
        }

        $member->delete();
        
        return redirect()->route('members.index')
                        ->with('success', 'Member deleted successfully');
    }

    public function verify(User $member)
    {
        // Pastikan hanya memverifikasi user dengan role 'user' (member)
        if ($member->role !== 'user') {
            abort(404);
        }

        $member->update([
            'is_verified' => true,
            'email_verified_at' => now()
        ]);

        return back()->with('success', 'Member verified successfully');
    }

    public function unverify(User $member)
    {
        // Pastikan hanya membatalkan verifikasi user dengan role 'user' (member)
        if ($member->role !== 'user') {
            abort(404);
        }

        $member->update([
            'is_verified' => false,
            'email_verified_at' => null
        ]);

        return back()->with('success', 'Member verification removed successfully');
    }

    public function toggleVerification(User $member)
    {
        // Pastikan hanya toggle verifikasi user dengan role 'user' (member)
        if ($member->role !== 'user') {
            abort(404);
        }

        $isVerified = !$member->is_verified;
        
        $member->update([
            'is_verified' => $isVerified,
            'email_verified_at' => $isVerified ? now() : null
        ]);

        $message = $isVerified ? 'Member verified successfully' : 'Member verification removed successfully';

        return back()->with('success', $message);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $status = $request->get('status');

        $members = User::where('role', 'user')
                      ->when($query, function ($q) use ($query) {
                          return $q->where(function ($q) use ($query) {
                              $q->where('name', 'like', "%{$query}%")
                                ->orWhere('email', 'like', "%{$query}%")
                                ->orWhere('phone', 'like', "%{$query}%");
                          });
                      })
                      ->when($status === 'verified', function ($q) {
                          return $q->where('is_verified', true);
                      })
                      ->when($status === 'unverified', function ($q) {
                          return $q->where('is_verified', false);
                      })
                      ->latest()
                      ->paginate(15)
                      ->withQueryString();

        // Statistics
        $totalMembers = User::where('role', 'user')->count();
        $verifiedMembers = User::where('role', 'user')->where('is_verified', true)->count();
        $unverifiedMembers = User::where('role', 'user')->where('is_verified', false)->count();
        $newMembersThisMonth = User::where('role', 'user')
                                  ->whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count();

        return view('admin.members.index', compact(
            'members', 
            'totalMembers', 
            'verifiedMembers', 
            'unverifiedMembers', 
            'newMembersThisMonth'
        ));
    }

    public function export(User $member)
    {
        // Pastikan hanya export user dengan role 'user' (member)
        if ($member->role !== 'user') {
            abort(404);
        }

        // Generate member data for export
        $memberData = [
            'Name' => $member->name,
            'Email' => $member->email,
            'Phone' => $member->phone ?: 'N/A',
            'Address' => $member->address ?: 'N/A',
            'Gender' => $member->gender ?: 'N/A',
            'Birth Date' => $member->birthdate ? $member->birthdate->format('Y-m-d') : 'N/A',
            'Religion' => $member->agama ?: 'N/A',
            'Verified' => $member->is_verified ? 'Yes' : 'No',
            'Joined Date' => $member->created_at->format('Y-m-d H:i:s'),
        ];

        $filename = 'member_' . $member->id . '_' . date('Y-m-d') . '.json';
        
        return response()->json($memberData)
                        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function exportAll()
    {
        $members = User::where('role', 'user')->get();
        
        $membersData = $members->map(function ($member) {
            return [
                'ID' => $member->id,
                'Name' => $member->name,
                'Email' => $member->email,
                'Phone' => $member->phone ?: 'N/A',
                'Address' => $member->address ?: 'N/A',
                'Gender' => $member->gender ?: 'N/A',
                'Birth Date' => $member->birthdate ? $member->birthdate->format('Y-m-d') : 'N/A',
                'Religion' => $member->agama ?: 'N/A',
                'Verified' => $member->is_verified ? 'Yes' : 'No',
                'Joined Date' => $member->created_at->format('Y-m-d H:i:s'),
            ];
        });

        $filename = 'all_members_' . date('Y-m-d') . '.json';
        
        return response()->json($membersData)
                        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function bulkVerify(Request $request)
    {
        $memberIds = $request->input('member_ids', []);
        
        if (empty($memberIds)) {
            return back()->with('error', 'No members selected');
        }

        User::where('role', 'user')
            ->whereIn('id', $memberIds)
            ->update([
                'is_verified' => true,
                'email_verified_at' => now()
            ]);

        return back()->with('success', count($memberIds) . ' members verified successfully');
    }

    public function bulkDelete(Request $request)
    {
        $memberIds = $request->input('member_ids', []);
        
        if (empty($memberIds)) {
            return back()->with('error', 'No members selected');
        }

        // Get members to delete their photos
        $members = User::where('role', 'user')->whereIn('id', $memberIds)->get();
        
        // Delete photos
        foreach ($members as $member) {
            if ($member->foto && Storage::disk('public')->exists($member->foto)) {
                Storage::disk('public')->delete($member->foto);
            }
        }

        // Delete members
        User::where('role', 'user')->whereIn('id', $memberIds)->delete();

        return back()->with('success', count($memberIds) . ' members deleted successfully');
    }
}
