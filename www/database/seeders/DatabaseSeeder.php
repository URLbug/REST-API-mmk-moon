<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Models\Phone;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\OrganizationFactory;
use Database\Factories\PhoneFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Organization::factory(10)->configure()->create();

        for($i=1;$i<=10;$i++){
            DB::table('organization_activity')->insert([
                'organizationID' => rand(1, 10),
                'activityID' => rand(1, 10),
            ]);
        }
    }
}
