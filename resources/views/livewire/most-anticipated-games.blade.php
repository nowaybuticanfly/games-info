<div class="most-anticipated-container space-y-10 mt-8" wire:init="loadMostAnticipated">
    @forelse($mostAnticipated as $game)
        <div class="game flex">
            <a href="#">
                <img  src="{{ Str::replaceFirst('thumb', 'cover_small', $game['cover']['url'])}}"
                      alt="game cover" class=" w-16 transition ease-in-out
                            duration-150">
            </a>
            <div class="ml-4">
                <a href="#">{{$game['name']}}</a>
                <div class="text-gray-400 text-sm mt-1">{{\Carbon\Carbon::parse
                                ($game['first_release_date'])->format('M d, Y')}}</div>
            </div>
        </div>
    @empty
        @foreach(range(1,4) as $game)
            <div class="game flex">
                <div class="w-16 h-20 bg-gray-700"></div>
                <div class="ml-4">
                    <div class="text-transparent bg-gray-700 block rounded leading-tight">Game title</div>
                    <div class="mt-3 text-transparent bg-gray-700 inline-block text-sm rounded
                    leading-tight">September 14, 2021</div>
                </div>
            </div>
        @endforeach
    @endforelse
</div>