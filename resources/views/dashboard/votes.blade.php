@extends('dashboard/layouts/main')

@section('content')
    <div class="w-4/12 absolute -top-4 z-10 flex items-end right-0">
        @include('dashboard/components.alerd')
    </div>

    <div class="flex justify-between">
        <div>
            <form action="{{ route('delete.votes') }}" method="POST" id="deleteForm_votes">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white btn btn-sm mb-4 hidden"
                    id="deleteButton_votes">Delete</button>
            </form>
        </div>
        <div>
            <form method="GET" action="{{ route('votes') }}" class="mb-4">
                <select name="kelas" id="kelas"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    onchange="this.form.submit()">
                    @foreach ($allKelas as $kelasOption)
                        <option value="{{ $kelasOption->kelas }}" {{ $kelas == $kelasOption->kelas ? 'selected' : '' }}>
                            Kelas {{ $kelasOption->kelas }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>


    <div class="overflow-x-auto">
        <table id="votes" class="items-center w-full bg-transparent border-collapse">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-sm bg-gray-50 text-gray-700">
                        <div class="flex gap-3">
                            <span class="label-text text-sm">All</span>
                            <input type="checkbox" id="checkAll" class="checkbox-info" />
                        </div>
                    </th>

                    <th
                        class="px-4 bg-gray-50 text-gray-700 align-middle py-3 text-xs font-semibold text-left uppercase border-l-0 border-r-0 whitespace-nowrap">
                        #</th>
                    <th
                        class="px-4 bg-gray-50 text-gray-700 align-middle py-3 text-xs font-semibold text-left uppercase border-l-0 border-r-0 whitespace-nowrap">
                        Username</th>
                    <th
                        class="px-4 bg-gray-50 text-gray-700 align-middle py-3 text-xs font-semibold text-left uppercase border-l-0 border-r-0 whitespace-nowrap">
                        Nama Pemilih</th>
                    <th
                        class="px-4 bg-gray-50 text-gray-700 align-middle py-3 text-xs font-semibold text-left uppercase border-l-0 border-r-0 whitespace-nowrap">
                        Kelas</th>
                    <th
                        class="px-4 bg-gray-50 text-gray-700 align-middle py-3 text-xs font-semibold text-left uppercase border-l-0 border-r-0 whitespace-nowrap">
                        Nama Calon</th>

                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($vote as $i => $vote)
                    <tr class="text-gray-500">
                        <td class="border px-4 py-2">
                            <input type="checkbox" name="selected_votes[]" value="{{ $vote->id }}" class="checkItem">
                        </td>

                        <th class="border-t-0 px-4 align-middle text-sm font-normal whitespace-nowrap p-4 text-left">
                            {{ $i + 1 }}</th>
                        <td class="border-t-0 px-4 align-middle text-xs font-medium text-gray-900 whitespace-nowrap p-4">
                            {{ $vote->user->username }}</td>
                        <td class="border-t-0 px-4 align-middle text-xs font-medium text-gray-900 whitespace-nowrap p-4">
                            {{ $vote->user->name }}</td>

                        <td class="border-t-0 px-4 align-middle text-xs font-medium text-gray-900 whitespace-nowrap p-4">
                            {{ $vote->user->kelas }}</td>
                        <td class="border-t-0 px-4 align-middle text-xs font-medium text-gray-900 whitespace-nowrap p-4">
                            {{ $vote->kandidat->name_calon }}</td>


                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#votes').DataTable({});

            function toggleDeleteButton() {
                const checkItems = document.querySelectorAll('.checkItem');
                const deleteButton = document.getElementById('deleteButton_votes');
                let isAnyChecked = Array.from(checkItems).some(item => item.checked);

                deleteButton.classList.toggle('hidden', !isAnyChecked);
            }

            // Handle "Check All" functionality
            document.getElementById('checkAll').addEventListener('click', function(e) {
                let checkItems = document.querySelectorAll('.checkItem');
                checkItems.forEach(item => item.checked = e.target.checked);
                toggleDeleteButton();
            });

            // Handle individual checkbox click events
            document.querySelectorAll('.checkItem').forEach(item => {
                item.addEventListener('click', function() {
                    toggleDeleteButton();
                });
            });

            // Handle form submission with selected IDs
            document.getElementById('deleteButton_votes').addEventListener('click', function() {
                const selectedIds = Array.from(document.querySelectorAll('.checkItem:checked')).map(item =>
                    item.value);
                const form = document.getElementById('deleteForm_votes');

                // Remove any previously added hidden inputs
                form.querySelectorAll('input[name="selected_votes[]"]').forEach(input => input.remove());

                // Add selected IDs as a hidden input field
                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_votes[]';
                    input.value = id;
                    form.appendChild(input);
                });

                // Submit the form
                form.submit();
            });
        });
    </script>
@endsection
