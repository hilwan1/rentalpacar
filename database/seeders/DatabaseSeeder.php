<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@rentalpacar.com'],
            [
                'name' => 'Admin RentalPacar',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        // 2. Akun Talent Sample
        $talent = User::firstOrCreate(
            ['email' => 'clarissa@rentalpacar.com'],
            [
                'name' => 'Clarissa',
                'password' => Hash::make('password123'),
                'role' => 'talent',
                'phone' => '081298765432',
                'city' => 'Jakarta Selatan',
                'bio' => 'Mahasiswi tingkat akhir yang hobi dengerin musik indie, ngobrol santai seputar kehidupan, dan mabar game. Siap jadi teman cerita yang hangat dan ramah.',
                'is_active' => true,
            ]
        );
        $talent->update(['city' => 'Jakarta Selatan']);

        // 3. Layanan untuk Talent
        $services = [
            [
                'service_name' => 'Chat Seharian',
                'category' => 'basic',
                'description' => 'Fast response chat dari pagi hingga malam hari, siap menemani aktivitas dan mendengarkan keluh kesahmu.',
                'duration' => '1 Hari',
                'price' => 35000,
                'is_active' => true,
            ],
            [
                'service_name' => 'Voice Call 1 Jam',
                'category' => 'basic',
                'description' => 'Obrolan santai atau deep talk via Discord / WA Call. Bebas bahas topik apapun yang kamu sukai.',
                'duration' => '60 Menit',
                'price' => 50000,
                'is_active' => true,
            ],
            [
                'service_name' => 'Main Game Bareng',
                'category' => 'premium',
                'description' => 'Mabar Mobile Legends atau game santai lainnya. Suasana fun dan anti toxic.',
                'duration' => '2 Match',
                'price' => 45000,
                'is_active' => true,
            ],
            [
                'service_name' => 'VIP Video Call & Date Virtual',
                'category' => 'premium',
                'description' => 'Kencan virtual eksklusif tatap muka 1-on-1, ngobrol seru sambil makan bareng atau nonton film.',
                'duration' => '90 Menit',
                'price' => 120000,
                'is_active' => true,
            ],
        ];

        foreach ($services as $srv) {
            Service::firstOrCreate(
                [
                    'talent_id' => $talent->id,
                    'service_name' => $srv['service_name'],
                ],
                $srv
            );
        }

        // 4. Testimoni Awal
        Testimonial::firstOrCreate(
            [
                'customer_alias' => 'R*** (Jakarta)',
                'talent_id' => $talent->id,
            ],
            [
                'rating' => 5,
                'content' => 'Clarissa seru banget diajak ngobrol. Suaranya ramah dan beneran jadi pendengar yang baik waktu lagi butuh teman curhat soal kerjaan. Bakal order lagi!',
                'is_published' => true,
            ]
        );
    }
}
