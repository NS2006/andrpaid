<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use App\Models\Paper;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First User
        $user = User::create([
            'name'=> "Niko Sutiono",
            'email'=> "nikosutiono11@gmail.com",
            'password' => bcrypt("aa"),
            'description'=> "I am atomic",
        ]);

        $province = Province::where('provinceId', "banten" )->first();

        $lecturer = Lecturer::create([
            "user_id"=> $user->id,
            "nidn" => "921838384939",
            "province_id" => $province->id
        ]);

        Paper::factory()->count(20)->create([
            'lecturer_id' => $lecturer->id,
        ]);

        // Second User
        $user = User::create([
            'name'=> "LC140",
            'email'=> "lc140-lcas@binus.edu",
            'password' => bcrypt("aa"),
            'description'=> "I am LC140 URAAAAA",
        ]);
        
        $province = Province::where('provinceId', "banten" )->first();
        
        $lecturer = Lecturer::create([
            "user_id"=> $user->id,
            'nidn' => "0123456789",
            "province_id" => $province->id
        ]);

        // Best User (Top Funder)

        $user = User::create([
            'name'=> "Bahlil Lahadalia",
            'email'=> "contactcenter136@esdm.go.id",
            'password' => bcrypt("bahlilsunshine"),
            'description'=> "Saya penyuka sawit 😍",
        ]);
        
        $province = Province::where('provinceId', "banten" )->first();
        
        $lecturer = Lecturer::create([
            "user_id"=> $user->id,
            'nidn' => "123456789",
            "province_id" => $province->id
        ]);

        Lecturer::factory()->count(5)->has(
            Paper::factory()->count(5),
            'papers'
        )->create();
    }
}
