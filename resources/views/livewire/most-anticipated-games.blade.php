<div class="most-anticipated-container space-y-10 mt-8" wire:init="loadMostAnticipated">
    @forelse($mostAnticipated as $game)
        <x-game-card-small :game="$game" />
    @empty
        @foreach(range(1,4) as $game)
            <x-game-card-small-skeleton />
        @endforeach
    @endforelse
</div>
