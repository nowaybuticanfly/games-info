<?php

namespace App\Http\Livewire;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;

class PopularGames extends Component
{
    public $popularGames = [];


    public function loadPopularGames() {
        $unformattedPopularGames = Cache::remember('popular-games', 30, function () {
            $before = Carbon::now()->subMonths(2)->timestamp;
            $after = Carbon::now()->addMonths(2)->timestamp;

            return  Http::withHeaders([
                'Client-ID' => env('Client_ID'),
                'Authorization' => 'Bearer ' . env('Auth_Key')
            ])->withBody(
                "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, slug;
                    where platforms = (48,49,130,6)
                    & (first_release_date >= {$before}
                    & first_release_date < {$after}
                    & total_rating_count > 5);
                    sort total_rating_count desc;
                    limit 12;",'text/plain'
            )->post('https://api.igdb.com/v4/games')->json();
        });
        $this->popularGames = $this->formatForView($unformattedPopularGames);


        collect($this->popularGames)->filter(function ($game) {
            return $game['rating'];
        })->each(function ($game) {
            $this->emit('gameWithRatingAdded', [
                'slug' => $game['slug'],
                'rating' => $game['rating']
            ]);
        });
    }

    public function render()
    {
        return view('livewire.popular-games');
    }

    private function formatForView($games) {
        return collect($games)->map(function($game) {
            return collect($game)->merge([
                'coverImageUrl' => isset($game['cover']) ? Str::replaceFirst('thumb','cover_big', $game['cover']['url'])
                    : 'https://via.placeholder.com/264x352',
                'rating' => isset($game['rating']) ? round($game['rating'])  : null,
                'platforms' => collect($game['platforms'])->pluck('abbreviation')->implode(', ')
            ]);
        });
    }
}
