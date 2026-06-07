<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $users = [
            [
                'name' => 'Mike Smith',
                'email' => 'mike@test.nl',
                'role' => 'rijschoolhouder',
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@test.nl',
                'role' => 'instructeur',
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'jane@test.nl',
                'role' => 'user',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => bcrypt('password'),
                ]
            );
        }

        $instructors = [
            [
                'email' => 'li@test.nl',
                'name' => 'Li Zhan',
                'first_name' => 'Li',
                'tussenvoegsel' => null,
                'last_name' => 'Zhan',
                'mobile' => '06-28493827',
                'datum_in_dienst' => '2015-04-17',
                'aantal_sterren' => 3,
                'role' => 'instructeur',
                'vehicles' => [
                    [
                        'vehicle_type' => 'Personenauto',
                        'vehicle_model' => 'Mercedes',
                        'license_plate' => 'TH-78-KL',
                        'build_year' => 2023,
                        'fuel_type' => 'Benzine',
                        'license_category' => 'B',
                    ],
                    [
                        'vehicle_type' => 'Bromfiets',
                        'vehicle_model' => 'Piaggio ZIP',
                        'license_plate' => '123-FR-T',
                        'build_year' => 2021,
                        'fuel_type' => 'Benzine',
                        'license_category' => 'AM',
                    ],
                    [
                        'vehicle_type' => 'Vrachtwagen',
                        'vehicle_model' => 'Scania',
                        'license_plate' => '34-TK-LP',
                        'build_year' => 2015,
                        'fuel_type' => 'Diesel',
                        'license_category' => 'C',
                    ],
                ],
            ],
            [
                'email' => 'leroy@test.nl',
                'name' => 'Leroy Boerhaven',
                'first_name' => 'Leroy',
                'tussenvoegsel' => null,
                'last_name' => 'Boerhaven',
                'mobile' => '06-39398734',
                'datum_in_dienst' => '2018-06-25',
                'aantal_sterren' => 1,
                'role' => 'instructeur',
                'vehicles' => [],
            ],
            [
                'email' => 'yoeri@test.nl',
                'name' => 'Yoeri van Veen',
                'first_name' => 'Yoeri',
                'tussenvoegsel' => 'van',
                'last_name' => 'Veen',
                'mobile' => '06-24383291',
                'datum_in_dienst' => '2010-05-12',
                'aantal_sterren' => 3,
                'role' => 'instructeur',
                'vehicles' => [],
            ],
            [
                'email' => 'bert@test.nl',
                'name' => 'Bert van Sali',
                'first_name' => 'Bert',
                'tussenvoegsel' => 'van',
                'last_name' => 'Sali',
                'mobile' => '06-48293823',
                'datum_in_dienst' => '2023-01-10',
                'aantal_sterren' => 4,
                'role' => 'instructeur',
                'vehicles' => [
                    [
                        'vehicle_type' => 'Personenauto',
                        'vehicle_model' => 'Fiat 500',
                        'license_plate' => '90-KL-TR',
                        'build_year' => 2021,
                        'fuel_type' => 'Benzine',
                        'license_category' => 'B',
                    ],
                ],
            ],
            [
                'email' => 'mohammed@test.nl',
                'name' => 'Mohammed el Yassidi',
                'first_name' => 'Mohammed',
                'tussenvoegsel' => 'el',
                'last_name' => 'Yassidi',
                'mobile' => '06-34291234',
                'datum_in_dienst' => '2010-06-14',
                'aantal_sterren' => 5,
                'role' => 'instructeur',
                'vehicles' => [
                    [
                        'vehicle_type' => 'Personenauto',
                        'vehicle_model' => 'Golf',
                        'license_plate' => 'AU-67-IO',
                        'build_year' => 2017,
                        'fuel_type' => 'Diesel',
                        'license_category' => 'B',
                    ],
                    [
                        'vehicle_type' => 'Bromfiets',
                        'vehicle_model' => 'Vespa',
                        'license_plate' => 'DRS-52-P',
                        'build_year' => 2022,
                        'fuel_type' => 'Benzine',
                        'license_category' => 'AM',
                    ],
                ],
            ],
        ];

        foreach ($instructors as $inst) {
            $user = User::updateOrCreate(
                ['email' => $inst['email']],
                [
                    'name' => $inst['name'],
                    'first_name' => $inst['first_name'],
                    'tussenvoegsel' => $inst['tussenvoegsel'],
                    'last_name' => $inst['last_name'],
                    'mobile' => $inst['mobile'],
                    'datum_in_dienst' => $inst['datum_in_dienst'],
                    'aantal_sterren' => $inst['aantal_sterren'],
                    'role' => $inst['role'],
                    'password' => bcrypt('password'),
                ]
            );

            foreach ($inst['vehicles'] as $veh) {
                DB::table('vehicles')->updateOrInsert(
                    ['license_plate' => $veh['license_plate']],
                    [
                        'instructor_id' => $user->id,
                        'vehicle_type' => $veh['vehicle_type'],
                        'vehicle_model' => $veh['vehicle_model'],
                        'build_year' => $veh['build_year'],
                        'fuel_type' => $veh['fuel_type'],
                        'license_category' => $veh['license_category'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
