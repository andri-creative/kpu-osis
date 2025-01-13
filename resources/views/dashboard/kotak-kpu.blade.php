<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kotak Suara</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>
    <!-- Nav -->
    <div class="navbar bg-base-100 md:px-10 px-2 fixed top-0 left-0 z-50">
        <div class="flex-1">
            <a class="w-10 rounded-full">
                <img src="{{ asset('assets/images/kpu.png') }}" alt="">
            </a>
        </div>
        <div class="flex-none">
            <div class="bg-[#4391D9] px-4 py-1 me-2 rounded text-white">
                <i class="fa-solid fa-user me-3"></i>
                {{ $user->name }}
            </div>
            <div class="dropdown dropdown-end ms-3">
                <div class="px-5 py-1 rounded bg-red-400 text-white">
                    <form action="{{ route('logout.kpu') }}" method="POST">
                        @csrf
                        <button>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Nav End -->
    <!-- Nav End -->
    <div class="bg-gray-300 md:px-10 px-2 py-20">
        <div class="">
            <div class="w-full bg-red-500 text-white font-bold mb-4 rounded-lg text-center py-3">
                <p>Waktu tersisa: <span id="countdown">2:00</span></p>
            </div>
            <div class="mb-4 text-center text-black">
                <h1 class="font-bold mb-2 typing-effect"></h1>
            </div>
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-4 w-full justify-items-center md:px-3 px-2">
                @if ($kandidat->count())
                    @foreach ($kandidat as $i => $item)
                        <div
                            class="w-72 bg-white shadow-md rounded-xl duration-500 hover:scale-105 hover:shadow-xl relative">
                            <button onclick="document.getElementById('detail_modal{{ $item->id }}').showModal()">

                                <div class="mt-5 mb-3 text-center text-black">
                                    <h1 class="font-bold mb-2">{{ $item->name_calon }}</h1>

                                    </h4>
                                </div>
                                <div>
                                    <img src="{{ 'images/' . $item->image_calon }}" alt="Product"
                                        class="h-80 w-72 object-cover rounded-t-xl" />
                                    <div class="px-4 py-3 w-72 flex justify-between text-white"></div>
                                </div>
                                <div class="mb-2 text-center text-black">
                                    <h4 class="font-bold text-2xl">{{ sprintf('%02d', $i + 1) }}
                                </div>
                            </button>
                        </div>

                        <!-- Detail Modal -->
                        <dialog id="detail_modal{{ $item->id }}" class="modal">
                            <div class="modal-box bg-blue-500 text-gray-700">
                                <h3 class="text-lg font-bold text-gray-100">Hello!</h3>
                                <h2 class="text-xl font-bold text-gray-100">Nama: <span>{{ $item->name_calon }}</span>
                                </h2>
                                <h2 class="text-xl font-bold mb-4 text-gray-100">Kelas:
                                    <span>{{ $item->kelas_calon }}</span>
                                </h2>
                                <div class="bg-gray-100 rounded p-1 mb-4">
                                    <div class="bg-visi-text mb-2">
                                        <div class="py-1 w-max font-bold">Visi</div>
                                        <p class="text-center">{{ $item->visi_calon }}</p>
                                    </div>
                                </div>
                                <div class="bg-white rounded p-1">
                                    <div class="bg-visi-text mb-2">
                                        <div class="py-1 w-max font-bold">Misi</div>
                                    </div>
                                    <div class="bg-visi-text mb-2">
                                        <p>{!! nl2br(e($item->misi_calon)) !!}</p>
                                    </div>
                                </div>
                                <div class="modal-action">
                                    <button
                                        onclick="closeModal('detail_modal{{ $item->id }}'); openModal('votes{{ $item->id }}');"
                                        class="btn bg-[#EDAB00] border-0 text-white hover:text-[#514341]">Votes</button>
                                    <form method="dialog">
                                        <button
                                            class="btn bg-[#514341] border-0 text-white hover:text-[#4391D9]">Close</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>

                        <!-- Votes Modal -->
                        <dialog id="votes{{ $item->id }}" class="modal">
                            <div class="modal-box">
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                </form>
                                <div class="w-full flex flex-col items-start justify-center">
                                    <p class="text-md mb-4">Apakah Anda Yakin Memilih Kandidat Ini?</p>
                                    <div class="grid grid-cols-2 gap-5">
                                        <div>
                                            <form id="vote-form{{ $item->id }}"
                                                action="{{ route('vote.store', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="candidate_id" value="{{ $item->id }}">
                                                <input type="hidden" name="redirect" value="{{ route('thanksKpu') }}">
                                                <button class="px-10 py-4 bg-blue-400 rounded-lg text-white text-bold"
                                                    type="submit">YA</button>
                                            </form>
                                        </div>

                                        <div>
                                            <form action="" method="dialog">
                                                <button
                                                    class="px-10 py-4 bg-red-400 rounded-lg text-white text-bold">Tidak</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </dialog>
                    @endforeach
                @else
                    <p>Anda belum melakukan vote.</p>
                @endif
            </div>
        </div>

    </div>
    <!--  -->
    <script>
        function closeModal(modalId) {
            document.getElementById(modalId).close();
        }

        function openModal(modalId) {
            document.getElementById(modalId).showModal();
        }

        // Countdown Timer
        var timeLeft = 120;
        var countdownTimer = setInterval(function() {
            var minutes = Math.floor(timeLeft / 60);
            var seconds = timeLeft % 60;

            if (seconds < 10) seconds = "0" + seconds;
            document.getElementById("countdown").textContent = minutes + ":" + seconds;

            if (timeLeft <= 0) {
                clearInterval(countdownTimer);
                document.querySelectorAll('button').forEach(button => button.disabled = true);
                setTimeout(function() {
                    window.location.href = '{{ route('logout.kpu') }}';
                }, 0); // immediately logout
            }

            timeLeft -= 1;
        }, 1000);

        document.getElementById('vote-form{{ $item->id }}').addEventListener('submit', function(event) {
            event.preventDefault();
            var form = event.target;
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                }
            }).then(response => {
                if (response.ok) {
                    window.location.href = form.querySelector('input[name="redirect"]').value;

                    setTimeout(function() {
                        window.location.href = '{{ route('logout.kpu') }}';
                    }, 1000);
                }
            });
        });
    </script>
    <script>
        const text = "JANGAN LUPA KLIK FOTO SAYA";
        const typingSpeed = 100; // kecepatan mengetik (ms per karakter)
        const delayBeforeRepeat = 2000; // waktu jeda sebelum mengulangi pengetikan (dalam ms)

        function typeText(element, text, typingSpeed, delayBeforeRepeat) {
            let index = 0;

            function type() {
                if (index < text.length) {
                    element.innerHTML += text.charAt(index);
                    index++;
                    setTimeout(type, typingSpeed);
                } else {
                    // Tunggu sebentar, lalu hapus teks dan mulai lagi
                    setTimeout(() => {
                        element.innerHTML = "";
                        index = 0; // reset index
                        setTimeout(type, typingSpeed); // mulai ulang pengetikan
                    }, delayBeforeRepeat);
                }
            }

            type();
        }

        window.onload = function() {
            // Ambil semua elemen dengan class "typing-effect"
            const typingElements = document.querySelectorAll(".typing-effect");

            // Mulai efek pengetikan pada setiap elemen
            typingElements.forEach((element) => {
                typeText(element, text, typingSpeed, delayBeforeRepeat);
            });
        };
    </script>
</body>

</html>
