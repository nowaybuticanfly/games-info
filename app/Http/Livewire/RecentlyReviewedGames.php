<?php

namespace App\Http\Livewire;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class RecentlyReviewedGames extends Component
{
    public $recentlyReviewed = [];

    public function loadRecentlyReviewed(){

        $this->recentlyReviewed =  Cache::remember('recently-reviewed', 30, function () {
            $before = Carbon::now()->subMonths(2)->timestamp;
            $current = Carbon::now()->timestamp;

            return Http::withHeaders([
            'Client-ID' => env('Client_ID'),
            'Authorization' => 'Bearer ' . env('Auth_Key')])
                ->withBody(
                "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, slug, summary;
                        where platforms = (48,49,130,6)
                        & (first_release_date >= {$before}
                        & first_release_date < {$current}
                        & total_rating_count > 5);
                        sort total_rating_count desc;
                        limit 3;",'text/plain'
            )->post('https://api.igdb.com/v4/games')->json();
        });
    }

    public function render()
    {
        return view('livewire.recently-reviewed-games');
    }
}
