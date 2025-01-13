@extends('dashboard/layouts/main')

@section('content')
    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-4 w-full justify-items-center md:px-3 px-2">
        @foreach ($kandidat as $item)
            <a href="{{ route('score.show', $item->id) }}">
                <div class="w-72 bg-white shadow-md rounded-xl duration-500 hover:scale-105 hover:shadow-xl relative">
                    <div class="mt-5 mb-3 text-center text-black">
                        <h1 class="font-bold mb-2">{{ $item->name_calon }}</h1>

                        </h4>
                    </div>
                    <div>
                        <img src="{{ 'images/' . $item->image_calon }}" alt="Product"
                            class="h-80 w-72 object-cover rounded-t-xl" />
                        <div class="px-4 py-3 w-72 flex justify-between text-white"></div>
                    </div>
                    <h1 class="text-lg font-semibold text-gray-800">
                        Score:
                        @if ($item->votes_count > 0)
                            {{ number_format($item->votes_count) }}
                        @else
                            No votes yet
                        @endif
                    </h1>

                </div>

            </a>
        @endforeach
    </div>
@endsection
