<?php

namespace Database\Seeders;

use App\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $urls = [
            'https://www.youtube.com/embed/Jfrjeg26Cwk',
            'https://www.youtube.com/embed/IP7uGKgJL8U',
            'https://www.youtube.com/embed/A-twOC3W558',
        ];

        foreach ($urls as $url) {
            Video::create(['url' => $url]);
        }
    }
}
