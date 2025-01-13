@extends('dashboard/layouts/main')

@section('content')
    <h1 class="text-3xl text-black pb-6">Dashboard</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6 text-black">
        <div class="p-6 bg-white">
            <p class="text-xl pb-3 flex items-center text-black">
                <i class="fas fa-plus mr-3"></i> Distribusi Suara per Kandidat
            </p>
            @include('dashboard/components/pie')
        </div>
        <div class="p-6 bg-white">
            <p class="text-xl pb-3 flex items-center">
                <i class="fas fa-check mr-3"></i> Jumlah Suara per Kandidat
            </p>
            @include('dashboard/components/bar')
        </div>
        <div class="p-6 bg-white">
            <p class="text-xl pb-3 flex items-center">
                <i class="fas fa-plus mr-3"></i> Waktu Pemungutan Suara
            </p>
            @include('dashboard/components/line')
        </div>
        <div class="p-6 bg-white">
            <p class="text-xl pb-3 flex items-center">
                <i class="fas fa-check mr-3"></i> Perbandingan Suara per Kelas
            </p>
            @include('dashboard/components/bar-grub')
        </div>
    </div>



    <div class="w-full mt-12">
        <p class="text-xl pb-3 flex items-center text-black">
            <i class="fas fa-list mr-3"></i> 5 Data Votes Terbaru
        </p>
        <div class="bg-white overflow-auto">
            <table class="min-w-full bg-white text-xs md:text-sm">
                <thead class="bg-gray-800 text-white ">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-xs md:text-sm">User Name</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-xs md:text-sm">Candidate Name</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-xs md:text-sm">Vote Time</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($recentVotes as $vote)
                        <tr>
                            <td class="text-left py-3 px-4">{{ $vote->user_name }}</td>
                            <td class="text-left py-3 px-4">{{ $vote->name_calon }}</td>
                            <td class="text-left py-3 px-4">{{ $vote->vote_time }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
