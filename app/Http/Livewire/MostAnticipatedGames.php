<?php

namespace App\Http\Livewire;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class MostAnticipatedGames extends Component
{
    public $mostAnticipated = [];

    public function loadMostAnticipated(){


        $this->mostAnticipated = Cache::remember('most-anticipated', 30, function () {
            $after = Carbon::now()->addMonths(2)->timestamp;
            $current = Carbon::now()->timestamp;
            return  Http::withHeaders([
                'Client-ID' => env('Client_ID'),
                'Authorization' => 'Bearer ' . env('Auth_Key')
            ])->withBody(
                "fields name, cover.url, first_release_date, hypes, platforms.abbreviation, summary, slug;
                    where platforms = (48,49,130,6)
                        & (first_release_date >= {$current}
                        & first_release_date < {$after}
                        & hypes > 5);
                    sort hypes desc;
                    limit 4;",'text/plain'
            )->post('https://api.igdb.com/v4/games')->json();
        });


    }

    public function render()
    {
        return view('livewire.most-anticipated-games');
    }
}