<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('RentalPacar');
        $response->assertSee('Daftar Jasa & Talent', false);
        $response->assertSee('Testimoni Pelanggan');
        $response->assertSee('Hubungi WA RentalPacar');
    }

    public function test_testimonials_page_loads_with_data(): void
    {
        $response = $this->get('/testimonials');

        $response->assertStatus(200);
        $response->assertSee('Testimoni Pelanggan');
        $response->assertSee('Menu Utama');
        $response->assertSee('R*** (Jakarta)');
    }

    public function test_talents_page_loads_with_data(): void
    {
        $response = $this->get('/talents');

        $response->assertStatus(200);
        $response->assertSee('Pilih Talent');
        $response->assertSee('Clarissa');
        $response->assertSee('Jakarta Selatan');
    }

    public function test_talents_page_search_and_filter_by_city(): void
    {
        $bandungTalent = User::create([
            'name' => 'Nadia Bandung',
            'email' => 'nadia@example.com',
            'password' => bcrypt('password'),
            'role' => 'talent',
            'phone' => '081288776655',
            'city' => 'Bandung',
            'bio' => 'Senang ngobrol kopi dan seni desain',
            'is_active' => true,
        ]);
        Service::create([
            'talent_id' => $bandungTalent->id,
            'service_name' => 'Ngobrol Santai',
            'duration' => '1 Jam',
            'price' => 50000,
            'is_active' => true,
        ]);

        // Filter kota Bandung
        $cityResponse = $this->get('/talents?city=Bandung');
        $cityResponse->assertStatus(200);
        $cityResponse->assertSee('Nadia Bandung');
        $cityResponse->assertDontSee('Clarissa');

        // Pencarian nama Clarissa
        $searchResponse = $this->get('/talents?q=Clarissa');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Clarissa');
        $searchResponse->assertDontSee('Nadia Bandung');

        // Pencarian keyword yang tidak ada
        $notFoundResponse = $this->get('/talents?q=TidakAdaSamaSekali');
        $notFoundResponse->assertStatus(200);
        $notFoundResponse->assertSee('Talent Tidak Ditemukan');
        $notFoundResponse->assertSee('Reset Pencarian dan Filter');
    }

    public function test_talent_detail_page_loads(): void
    {
        $talent = User::where('role', 'talent')->where('is_active', true)->firstOrFail();

        $response = $this->get('/talents/' . $talent->id);

        $response->assertStatus(200);
        $response->assertSee($talent->name);
        $response->assertSee('Jakarta Selatan');
        $response->assertSee('Kirim Pesanan ke WhatsApp');
    }

    public function test_order_submission_redirects_to_whatsapp(): void
    {
        $talent = User::where('role', 'talent')->where('is_active', true)->firstOrFail();
        $service = $talent->services()->firstOrFail();

        $response = $this->post('/orders', [
            'talent_id' => $talent->id,
            'service_id' => $service->id,
            'customer_name' => 'Budi',
            'customer_whatsapp' => '081234567890',
            'booking_schedule' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'notes' => 'Mau mabar santai',
        ]);

        $response->assertRedirect();
        $this->assertStringContainsString('https://wa.me/', $response->headers->get('Location'));
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi',
            'talent_id' => $talent->id,
            'service_id' => $service->id,
        ]);
    }

    public function test_generic_login_redirects_to_talent_login(): void
    {
        $response = $this->get('/login');

        $response->assertRedirect(route('talent.login'));
    }

    public function test_talent_login_page_loads(): void
    {
        $response = $this->get('/talent/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk Portal Talent');
        $response->assertSee('Portal Admin');
    }

    public function test_talent_register_page_loads(): void
    {
        $response = $this->get('/talent/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Jadi Talent');
        $response->assertSee('Nomor WhatsApp');
    }

    public function test_talent_registration_works(): void
    {
        $response = $this->post('/talent/register', [
            'name' => 'Jessica Test',
            'email' => 'jessica@example.com',
            'phone' => '081299887766',
            'city' => 'Bandung',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('talent.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'jessica@example.com',
            'name' => 'Jessica Test',
            'phone' => '081299887766',
            'city' => 'Bandung',
            'role' => 'talent',
        ]);

        // Verifikasi masuk ke dashboard talent dan menampilkan data pendaftaran
        $dashboardResponse = $this->get(route('talent.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Halo, Jessica Test!');
        $dashboardResponse->assertSee('081299887766');
        $dashboardResponse->assertSee('Bandung');
    }

    public function test_talent_can_update_profile_and_add_service(): void
    {
        $talent = User::where('role', 'talent')->where('is_active', true)->firstOrFail();

        $response = $this->actingAs($talent)->post('/talent/profile', [
            'name' => 'Clarissa Updated',
            'phone' => '081211223344',
            'city' => 'Surabaya',
            'bio' => 'Bio baru yang diperbarui',
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $talent->id,
            'name' => 'Clarissa Updated',
            'phone' => '081211223344',
            'city' => 'Surabaya',
        ]);

        $serviceResponse = $this->actingAs($talent)->post('/talent/services', [
            'service_name' => 'Paket Mabar Seru',
            'duration' => '3 Match',
            'price' => 60000,
            'description' => 'Mabar game santai',
        ]);

        $serviceResponse->assertRedirect();
        $this->assertDatabaseHas('services', [
            'talent_id' => $talent->id,
            'service_name' => 'Paket Mabar Seru',
            'price' => 60000,
        ]);
    }

    public function test_talent_can_upload_and_delete_profile_photo(): void
    {
        Storage::fake('public');
        $talent = User::where('role', 'talent')->where('is_active', true)->firstOrFail();

        $file = UploadedFile::fake()->image('profile.jpg', 400, 400);

        $response = $this->actingAs($talent)->post('/talent/profile', [
            'name' => $talent->name,
            'phone' => $talent->phone,
            'avatar' => $file,
        ]);

        $response->assertRedirect();
        $talent->refresh();

        $this->assertNotNull($talent->avatar);
        Storage::disk('public')->assertExists($talent->avatar);
        $this->assertNotNull($talent->avatar_url);

        // Uji hapus foto
        $deleteResponse = $this->actingAs($talent)->post('/talent/profile', [
            'name' => $talent->name,
            'phone' => $talent->phone,
            'remove_avatar' => '1',
        ]);

        $deleteResponse->assertRedirect();
        $talent->refresh();
        $this->assertNull($talent->avatar);
    }

    public function test_talent_registration_with_avatar(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('talent_foto.png', 300, 300);

        $response = $this->post('/talent/register', [
            'name' => 'Talent Dengan Foto',
            'email' => 'foto_talent@example.com',
            'phone' => '081234567899',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'avatar' => $file,
        ]);

        $response->assertRedirect(route('talent.dashboard'));
        $created = User::where('email', 'foto_talent@example.com')->firstOrFail();
        $this->assertNotNull($created->avatar);
        Storage::disk('public')->assertExists($created->avatar);
    }

    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Login Administrator');
        $response->assertSee('Area Khusus Admin');
    }

    public function test_admin_login_works_for_admin_user(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@rentalpacar.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_talent_user_cannot_login_via_admin_portal(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'clarissa@rentalpacar.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_dashboard_loads_with_sidebar_for_admin_user(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Panel Admin');
        $response->assertSee('Dashboard Ringkasan');
        $response->assertSee('Navigasi Utama');
        $response->assertSee('Pesanan Masuk');
        $response->assertSee('Daftar Akun Talent');
        $response->assertSee('Keluar Akun');
    }

    public function test_talent_user_cannot_access_admin_dashboard(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_talents_list_and_toggle_status(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $talent = User::where('role', 'talent')->firstOrFail();

        // 1. Kunjungi halaman daftar talent
        $response = $this->actingAs($admin)->get(route('admin.talents.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Talent');
        $response->assertSee($talent->name);
        $response->assertSee('Nonaktifkan');

        // 2. Nonaktifkan talent
        $toggleResponse = $this->actingAs($admin)->post(route('admin.talents.toggle-status', $talent->id));
        $toggleResponse->assertRedirect();
        $this->assertFalse($talent->fresh()->is_active);

        // 3. Verifikasi halaman sekarang menampilkan tombol 'Aktifkan Akun'
        $afterDeactivate = $this->actingAs($admin)->get(route('admin.talents.index'));
        $afterDeactivate->assertSee('Aktifkan Akun');

        // 4. Aktifkan kembali talent
        $activateResponse = $this->actingAs($admin)->post(route('admin.talents.toggle-status', $talent->id));
        $activateResponse->assertRedirect();
        $this->assertTrue($talent->fresh()->is_active);
    }

    public function test_talent_user_cannot_manage_talents(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->get(route('admin.talents.index'));
        $response->assertStatus(403);

        $toggleResponse = $this->actingAs($talent)->post(route('admin.talents.toggle-status', $talent->id));
        $toggleResponse->assertStatus(403);
    }

    public function test_top_talent_page_loads_successfully(): void
    {
        $response = $this->get('/top-talent');

        $response->assertStatus(200);
        $response->assertSee('Top Talent Mingguan');
        $response->assertSee('Peringkat Mingguan');
        $response->assertSee('Minggu Ini');
    }

    public function test_top_talent_page_with_week_filter(): void
    {
        $response = $this->get('/top-talent?week=1');

        $response->assertStatus(200);
        $response->assertSee('1 Minggu Lalu');
    }

    public function test_top_talent_ranks_by_weekly_order_count(): void
    {
        $talent = User::where('role', 'talent')->where('is_active', true)->firstOrFail();
        $service = $talent->services()->firstOrFail();

        // Create an order in the current week for this talent
        \App\Models\Order::create([
            'order_code' => 'RP-TEST-WEEK-01',
            'talent_id' => $talent->id,
            'service_id' => $service->id,
            'customer_name' => 'Top Customer',
            'customer_whatsapp' => '081233445566',
            'booking_schedule' => now()->addDay(),
            'service_name_snapshot' => $service->service_name,
            'price_snapshot' => $service->price,
            'created_at' => now(),
        ]);

        $response = $this->get('/top-talent');

        $response->assertStatus(200);
        $response->assertSee('1 Pesanan');
        $response->assertSee($talent->name);
    }

    public function test_header_adjusts_for_authenticated_talent_and_admin(): void
    {
        // 1. Saat Guest: terlihat tombol Masuk & Daftar Jadi Talent
        $guestResponse = $this->get('/');
        $guestResponse->assertStatus(200);
        $guestResponse->assertSee('Masuk');
        $guestResponse->assertSee('Daftar Jadi Talent');

        // 2. Saat Talent Login: tombol Masuk & Daftar Jadi Talent hilang, muncul Dashboard Talent
        $talent = User::where('role', 'talent')->firstOrFail();
        $talentResponse = $this->actingAs($talent)->get('/');
        $talentResponse->assertStatus(200);
        $talentResponse->assertDontSee('Daftar Jadi Talent');
        $talentResponse->assertSee('Dashboard Talent');
        $talentResponse->assertSee('Keluar');

        // 3. Saat Admin Login: muncul Dashboard Admin
        $admin = User::where('role', 'admin')->firstOrFail();
        $adminResponse = $this->actingAs($admin)->get('/');
        $adminResponse->assertStatus(200);
        $adminResponse->assertDontSee('Daftar Jadi Talent');
        $adminResponse->assertSee('Dashboard Admin');
        $adminResponse->assertSee('Keluar');
    }

    public function test_talents_page_and_top_talent_pagination_renders_custom_view(): void
    {
        // Buat 15 talent tambahan agar pagination aktif (> 10 items)
        for ($i = 1; $i <= 15; $i++) {
            $user = User::create([
                'name' => "Talent $i",
                'email' => "talent$i@example.com",
                'password' => bcrypt('password'),
                'role' => 'talent',
                'phone' => "0812000000$i",
                'is_active' => true,
            ]);
            Service::create([
                'talent_id' => $user->id,
                'service_name' => 'Chat Santai',
                'duration' => '1 Jam',
                'price' => 40000,
                'is_active' => true,
            ]);
        }

        // Uji halaman talents page 1 dan 2
        $responsePage1 = $this->get('/talents');
        $responsePage1->assertStatus(200);
        $responsePage1->assertSee('Navigasi Halaman');
        $responsePage1->assertSee('Hal.');

        $responsePage2 = $this->get('/talents?page=2');
        $responsePage2->assertStatus(200);
        $responsePage2->assertSee('Navigasi Halaman');

        // Uji halaman top-talent page 2 dan verifikasi peringkatnya lanjut (#11 dst)
        $topTalentPage2 = $this->get('/top-talent?page=2');
        $topTalentPage2->assertStatus(200);
        $topTalentPage2->assertSee('#11');
        $topTalentPage2->assertSee('Navigasi Halaman');
    }
}
