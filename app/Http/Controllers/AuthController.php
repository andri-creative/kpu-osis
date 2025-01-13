<?php

namespace App\Http\Controllers;

use App\Models\KandidatModel;
use App\Models\User;
use App\Models\VotesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth')->except(['login']);
    // }

    public function loginSiswa()
    {
        if (Auth::check()) {
            return redirect()->route('kpu');
        }

        return view('login-siswa');
    }
    public function login(Request $request)
    {

    }

    // KPU
    public function kpu()
    {
        return view('dashboard.kotak-kpu');
    }

    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();

        $kandidatData = KandidatModel::withCount('votes')
            ->get()
            ->map(function ($kandidat) {
                return [
                    'name' => $kandidat->name_calon,
                    'votes' => $kandidat->votes_count,
                ];
            });

        $pemilihPerKelas = User::select('kelas', DB::raw('count(id) as jumlah_pemilih'))
            ->groupBy('kelas')
            ->get()
            ->map(function ($item) {
                return [
                    'kelas' => $item->kelas,
                    'jumlah_pemilih' => $item->jumlah_pemilih,
                ];
            });

      
        $votesData = VotesModel::select(DB::raw('DATE_FORMAT(vote_time, "%H:%i") as minute'), DB::raw('count(*) as total_votes'))
            ->groupBy('minute')
            ->orderBy('minute', 'ASC')
            ->get();

        $recentVotes = DB::table('votes')
            ->join('users', 'votes.user_id', '=', 'users.id')
            ->join('kandidats', 'votes.candidate_id', '=', 'kandidats.id')
            ->select('users.name as user_name', 'kandidats.name_calon', 'votes.vote_time')
            ->orderBy('votes.vote_time', 'desc')
            ->take(5)
            ->get();

        $candidates = KandidatModel::all();
        $candidateNames = $kandidatData->pluck('name')->toArray(); 
        $classes = User::distinct()->pluck('kelas')->toArray();
        $candidateVotes = [];

        // Ambil semua suara untuk setiap kandidat
        foreach ($candidates as $candidate) {
            // Ambil suara untuk kandidat ini
            $votes = VotesModel::where('candidate_id', $candidate->id)
                ->with('user') // Eager load untuk mendapatkan data user
                ->get();

            // Inisialisasi suara per kelas
            $classVotes = array_fill(0, count($classes), 0);

            foreach ($votes as $vote) {
                $userClass = $vote->user->kelas;
                $classIndex = array_search($userClass, $classes);

                if ($classIndex !== false) {
                    $classVotes[$classIndex]++;
                }
            }

            // Tambahkan suara kandidat ke array
            $candidateVotes[] = $classVotes;
        }


        $groupedBarData = [
            'classes' => $classes,
            'candidateNames' => $candidateNames,
            'candidateVotes' => $candidateVotes,
        ];


        return view('dashboard.dashboard', [
            'role' => $admin->roles_admin,
            'kandidatData' => $kandidatData,
            'pemilihPerKelas' => $pemilihPerKelas,
            'votesData' => $votesData,
            'groupedBarData' => $groupedBarData,
            'recentVotes' => $recentVotes,
            'candidateNames' => $candidateNames, 
        ]);
    }

    public function tambahUser()
    {
        return view('dashboard.tambah-user');
    }

    public function createUser(Request $req)
    {

        $validated = $req->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        $status = 'aktif';

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'kelas' => $validated['kelas'],
            'password' => bcrypt($validated['password']),
            'status' => $status,
        ]);

        return redirect()->route('tambah-user')->with('success', 'User created successfully');
    }
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {

            return redirect()->intended('dashboard')->with('success', 'Login successful');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function listUsers(Request $request)
    {
        $allKelas = User::select('kelas')->distinct()->get();

        $kelas = $request->input('kelas');

        if(!$kelas && $allKelas->isNotEmpty())
        {
            $kelas = $allKelas->first()->kelas;
        }

        $users = User::where('kelas', $kelas)
                    ->where('status', 'aktif')
                    ->get();

        return view('dashboard.list-tabel', compact('users', 'allKelas', 'kelas'));
    }
    public function updateUser(Request $req, $id)
    {
        $user = User::find($id);

        $validate = $req->validate([
            'name' => 'required',
            'kelas' => 'required',
        ]);

        $user->name = $req->input('name');
        $user->kelas = $req->input('kelas');

        $user->save();

        return redirect()->route('listUsers')->with('success', 'User updated successfully!');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function destroy(Request $req)
    {

        $ids = $req->input('selected_users', []);

        if (is_array($ids) && !empty($ids)) {
            User::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Users deleted successfully.');
        }

        return redirect()->back()->with('error', 'No users selected.');
    }

    public function votesView(Request $request)
    {

    $allKelas = User::select('kelas')->distinct()->get();

    $kelas = $request->input('kelas');

    if (!$kelas && $allKelas->isNotEmpty()) {
        $kelas = $allKelas->first()->kelas;
    }

    $vote = VotesModel::with(['user' => function ($query) use ($kelas) {
        $query->where('kelas', $kelas); 
    }, 'kandidat'])
    ->when($kelas, function ($query) use ($kelas) {
        return $query->whereHas('user', function ($q) use ($kelas) {
            $q->where('kelas', $kelas);
        });
    })
    ->get();

        return view('dashboard.votes',compact('vote', 'allKelas', 'kelas'));
    }

}
