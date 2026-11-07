<?php

namespace Tests\Feature;

use App\Mail\TalentOtpMail;
use App\Models\Order;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        $response->assertSee('Pesan');
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

        $order = Order::where('customer_name', 'Budi')->firstOrFail();

        $response->assertRedirect(route('orders.show', $order->order_code));
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi',
            'talent_id' => $talent->id,
            'service_id' => $service->id,
        ]);

        // Verifikasi Halaman Detail Pesanan Publik
        $detailResponse = $this->get(route('orders.show', $order->order_code));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($order->order_code);
        $detailResponse->assertSee('Budi');
        $detailResponse->assertSee($talent->name);
        $detailResponse->assertSee($service->service_name);
        $detailResponse->assertSee('Mau mabar santai');
        $detailResponse->assertSee('Konfirmasi & Kirim Data ke WhatsApp Admin', false);
        $detailResponse->assertSee('https://wa.me/', false);
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
        $response->assertSee('id="toggle-talent-password"', false);
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
            'category' => 'basic',
            'price' => 60000,
        ]);

        $premiumResponse = $this->actingAs($talent)->post('/talent/services', [
            'service_name' => 'VIP Video Call Exclusive',
            'category' => 'premium',
            'duration' => '120 Menit',
            'price' => 150000,
            'description' => 'Eksklusif 1 on 1 date',
        ]);

        $premiumResponse->assertRedirect();
        $this->assertDatabaseHas('services', [
            'talent_id' => $talent->id,
            'service_name' => 'VIP Video Call Exclusive',
            'category' => 'premium',
            'price' => 150000,
        ]);
    }

    public function test_talent_can_delete_service(): void
    {
        $talent = User::where('role', 'talent')->where('is_active', true)->firstOrFail();

        $service = Service::create([
            'talent_id' => $talent->id,
            'service_name' => 'Service To Delete',
            'duration' => '30 Menit',
            'price' => 25000,
            'is_active' => true,
        ]);

        $deleteResponse = $this->actingAs($talent)->delete("/talent/services/{$service->id}");
        $deleteResponse->assertRedirect();
        $deleteResponse->assertSessionHas('success');
        $this->assertNull(Service::find($service->id));
    }

    public function test_talent_delete_service_with_orders_deactivates_it(): void
    {
        $talent = User::where('role', 'talent')->where('is_active', true)->firstOrFail();

        $service = Service::create([
            'talent_id' => $talent->id,
            'service_name' => 'Service With Past Orders',
            'duration' => '1 Jam',
            'price' => 50000,
            'is_active' => true,
        ]);

        Order::create([
            'order_code' => 'RP-PAST-9999',
            'talent_id' => $talent->id,
            'service_id' => $service->id,
            'customer_name' => 'Order Tester',
            'customer_whatsapp' => '081234567890',
            'booking_schedule' => now()->addDay(),
            'service_name_snapshot' => $service->service_name,
            'price_snapshot' => $service->price,
        ]);

        $deleteResponse = $this->actingAs($talent)->delete("/talent/services/{$service->id}");
        $deleteResponse->assertRedirect();
        $deleteResponse->assertSessionHas('success');

        // Harus tetap ada di database namun dinonaktifkan
        $service->refresh();
        $this->assertFalse($service->is_active);
    }

    public function test_talent_can_change_password_with_valid_current_password(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->post('/talent/password', [
            'current_password' => 'password123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword456', $talent->fresh()->password));
    }

    public function test_talent_cannot_change_password_with_wrong_current_password(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->post('/talent/password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password123', $talent->fresh()->password));
    }

    public function test_talent_cannot_change_password_with_mismatched_confirmation(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->post('/talent/password', [
            'current_password' => 'password123',
            'password' => 'newpassword456',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
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
        $response->assertSee('id="toggle-admin-password"', false);
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

    public function test_admin_can_view_and_update_whatsapp_settings(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        // 1. Kunjungi halaman pengaturan WhatsApp
        $response = $this->actingAs($admin)->get(route('admin.settings.whatsapp'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Nomor WhatsApp');
        $response->assertSee('No. WA Beranda');
        $response->assertSee('No. WA Pemesanan');

        // 2. Simpan nomor WhatsApp baru (masukkan awalan 08...)
        $updateResponse = $this->actingAs($admin)->post(route('admin.settings.whatsapp.update'), [
            'whatsapp_home_number' => '081299998888',
            'whatsapp_order_number' => '081277776666',
        ]);
        $updateResponse->assertRedirect();
        $updateResponse->assertSessionHas('success');

        // 3. Verifikasi nomor tersimpan dan otomatis disanitasi menjadi awalan 62
        $this->assertEquals('6281299998888', Setting::get('whatsapp_home_number'));
        $this->assertEquals('6281277776666', Setting::get('whatsapp_order_number'));

        // 4. Verifikasi tombol di halaman beranda mengarah ke nomor WA baru
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('https://wa.me/6281299998888', false);

        // 5. Verifikasi pengiriman pesanan mengarah ke halaman detail pesanan yang memuat nomor WA pemesanan baru
        $talent = User::where('role', 'talent')->where('is_active', true)->firstOrFail();
        $service = $talent->services()->firstOrFail();

        $orderResponse = $this->post('/orders', [
            'talent_id' => $talent->id,
            'service_id' => $service->id,
            'customer_name' => 'Budi WhatsApp',
            'customer_whatsapp' => '081234567890',
            'booking_schedule' => now()->addDay()->format('Y-m-d\TH:i'),
        ]);

        $order = Order::where('customer_name', 'Budi WhatsApp')->firstOrFail();
        $orderResponse->assertRedirect(route('orders.show', $order->order_code));

        $detailResponse = $this->get(route('orders.show', $order->order_code));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('https://wa.me/6281277776666', false);
    }

    public function test_talent_user_cannot_access_whatsapp_settings(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->get(route('admin.settings.whatsapp'));
        $response->assertStatus(403);

        $updateResponse = $this->actingAs($talent)->post(route('admin.settings.whatsapp.update'), [
            'whatsapp_home_number' => '081299998888',
            'whatsapp_order_number' => '081277776666',
        ]);
        $updateResponse->assertStatus(403);
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

    public function test_admin_can_view_testimonials_index(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->get('/admin/testimonials');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Testimoni Pelanggan');
        $response->assertSee('Tambah Testimoni');
        $response->assertSee('R*** (Jakarta)');
    }

    public function test_admin_can_access_create_testimonial_page(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->get('/admin/testimonials/create');
        $response->assertStatus(200);
        $response->assertSee('Tambah Testimoni Pelanggan');
        $response->assertSee('Clarissa');
        $response->assertSee('Simpan & Terbitkan Testimoni', false);
    }

    public function test_admin_can_store_testimonial_and_it_appears_in_public(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'admin')->firstOrFail();
        $talent = User::where('role', 'talent')->firstOrFail();

        $screenshot = UploadedFile::fake()->image('chat_bukti.jpg', 600, 800);

        $response = $this->actingAs($admin)->post('/admin/testimonials', [
            'customer_alias' => 'Dimas (25, Bandung)',
            'talent_id' => $talent->id,
            'rating' => 5,
            'content' => 'Pelayanan sangat memuaskan, sangat mendengarkan curhat saya.',
            'screenshot' => $screenshot,
            'is_published' => '1',
        ]);

        $response->assertRedirect('/admin/testimonials');
        $response->assertSessionHas('success');

        $testimonial = Testimonial::where('customer_alias', 'Dimas (25, Bandung)')->first();
        $this->assertNotNull($testimonial);
        $this->assertEquals(5, $testimonial->rating);
        $this->assertTrue($testimonial->is_published);
        $this->assertNotNull($testimonial->screenshot_path);
        Storage::disk('public')->assertExists($testimonial->screenshot_path);

        // Verifikasi muncul di halaman publik /testimonials
        $publicResponse = $this->get('/testimonials');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Dimas (25, Bandung)');
        $publicResponse->assertSee('Pelayanan sangat memuaskan');
    }

    public function test_admin_can_store_draft_testimonial_and_it_does_not_appear_in_public(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->post('/admin/testimonials', [
            'customer_alias' => 'Budi Rahasia (Draft)',
            'rating' => 4,
            'content' => 'Ini ulasan yang masih menunggu konfirmasi.',
            'is_published' => '0',
        ]);

        $response->assertRedirect('/admin/testimonials');

        $testimonial = Testimonial::where('customer_alias', 'Budi Rahasia (Draft)')->first();
        $this->assertNotNull($testimonial);
        $this->assertFalse($testimonial->is_published);

        // Tidak boleh tampil di halaman publik
        $publicResponse = $this->get('/testimonials');
        $publicResponse->assertStatus(200);
        $publicResponse->assertDontSee('Budi Rahasia (Draft)');
    }

    public function test_admin_can_toggle_testimonial_status(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $testimonial = Testimonial::firstOrFail();
        $initialStatus = $testimonial->is_published;

        $response = $this->actingAs($admin)->post("/admin/testimonials/{$testimonial->id}/toggle-status");
        $response->assertSessionHas('success');

        $this->assertEquals(!$initialStatus, $testimonial->fresh()->is_published);
    }

    public function test_admin_can_update_and_delete_testimonial(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'admin')->firstOrFail();

        $testimonial = Testimonial::create([
            'customer_alias' => 'Testing Update',
            'rating' => 3,
            'content' => 'Konten lama',
            'is_published' => true,
        ]);

        // Uji Update
        $editResponse = $this->actingAs($admin)->get("/admin/testimonials/{$testimonial->id}/edit");
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Testing Update');

        $updateResponse = $this->actingAs($admin)->put("/admin/testimonials/{$testimonial->id}", [
            'customer_alias' => 'Testing Updated Name',
            'rating' => 5,
            'content' => 'Konten baru yang sudah diperbaiki',
            'is_published' => '1',
        ]);
        $updateResponse->assertRedirect('/admin/testimonials');
        $this->assertEquals('Testing Updated Name', $testimonial->fresh()->customer_alias);
        $this->assertEquals(5, $testimonial->fresh()->rating);

        // Uji Delete
        $deleteResponse = $this->actingAs($admin)->delete("/admin/testimonials/{$testimonial->id}");
        $deleteResponse->assertSessionHas('success');
        $this->assertNull(Testimonial::find($testimonial->id));
    }

    public function test_non_admin_cannot_manage_testimonials(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->get('/admin/testimonials');
        $response->assertStatus(403);

        $createResponse = $this->actingAs($talent)->get('/admin/testimonials/create');
        $createResponse->assertStatus(403);
    }

    public function test_guest_can_submit_testimonial_pending_admin_approval(): void
    {
        $talent = User::where('role', 'talent')->where('is_active', true)->firstOrFail();

        // Guest submit testimoni
        $response = $this->post('/testimonials', [
            'customer_alias' => 'Guest Tester (Surabaya)',
            'talent_id' => $talent->id,
            'rating' => 5,
            'content' => 'Pengalaman kencan virtual yang seru dan menyenangkan!',
        ]);

        $response->assertRedirect('/testimonials');
        $response->assertSessionHas('success');

        // Pastikan masuk ke database dengan is_published = false
        $testimonial = Testimonial::where('customer_alias', 'Guest Tester (Surabaya)')->firstOrFail();
        $this->assertFalse($testimonial->is_published);
        $this->assertEquals($talent->id, $testimonial->talent_id);

        // Pastikan TIDAK muncul di halaman publik sebelum disetujui
        $publicResponse = $this->get('/testimonials');
        $publicResponse->assertStatus(200);
        $publicResponse->assertDontSee('Guest Tester (Surabaya)');

        // Admin menyetujui testimoni tersebut
        $admin = User::where('role', 'admin')->firstOrFail();
        $toggleResponse = $this->actingAs($admin)->post("/admin/testimonials/{$testimonial->id}/toggle-status");
        $toggleResponse->assertRedirect();

        $testimonial->refresh();
        $this->assertTrue($testimonial->is_published);

        // Setelah disetujui admin, pastikan SEKARANG muncul di halaman publik
        $approvedResponse = $this->get('/testimonials');
        $approvedResponse->assertStatus(200);
        $approvedResponse->assertSee('Guest Tester (Surabaya)');
        $approvedResponse->assertSee('Pengalaman kencan virtual yang seru');
    }

    public function test_guest_testimonial_validation(): void
    {
        $response = $this->post('/testimonials', [
            'customer_alias' => '',
            'rating' => 6,
            'content' => '',
        ]);

        $response->assertSessionHasErrors(['customer_alias', 'rating', 'content']);
    }

    public function test_admin_can_access_create_talent_page(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->get('/admin/talents/create');
        $response->assertStatus(200);
        $response->assertSee('Tambah Talent Baru');
        $response->assertSee('Data Profil Talent');
        $response->assertSee('Paket Jasa / Layanan Talent');
        $response->assertSee('Simpan & Daftarkan Talent', false);
    }

    public function test_admin_can_store_new_talent_with_services_and_avatar(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'admin')->firstOrFail();

        $avatar = UploadedFile::fake()->image('jessica_avatar.jpg', 400, 400);

        $response = $this->actingAs($admin)->post('/admin/talents', [
            'name' => 'Jessica AdminCreated',
            'email' => 'jessica.new@rentalpacar.com',
            'phone' => '081299887766',
            'city' => 'Surabaya',
            'password' => 'secret123',
            'bio' => 'Senang ngobrol seputar musik dan kuliner seru.',
            'avatar' => $avatar,
            'is_active' => '1',
            'services' => [
                [
                    'service_name' => 'Chat Seharian',
                    'duration' => '1 Hari',
                    'price' => 35000,
                    'description' => 'Fast response pagi sampai malam',
                ],
                [
                    'service_name' => 'Voice Call 1 Jam',
                    'duration' => '60 Menit',
                    'price' => 55000,
                    'description' => 'Ngobrol santai via discord',
                ],
            ],
        ]);

        $response->assertRedirect('/admin/talents');
        $response->assertSessionHas('success');

        $talent = User::where('email', 'jessica.new@rentalpacar.com')->first();
        $this->assertNotNull($talent);
        $this->assertEquals('Jessica AdminCreated', $talent->name);
        $this->assertEquals('Surabaya', $talent->city);
        $this->assertTrue($talent->is_active);
        $this->assertNotNull($talent->avatar);
        Storage::disk('public')->assertExists($talent->avatar);

        // Verifikasi layanan dibuat
        $services = Service::where('talent_id', $talent->id)->get();
        $this->assertCount(2, $services);
        $this->assertEquals('Chat Seharian', $services[0]->service_name);
        $this->assertEquals(35000, $services[0]->price);

        // Verifikasi tampil di halaman publik /talents
        $publicResponse = $this->get('/talents');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Jessica AdminCreated');
        $publicResponse->assertSee('Surabaya');
    }

    public function test_admin_store_talent_validations(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->post('/admin/talents', [
            'name' => '',
            'email' => 'invalid-email',
            'phone' => '',
            'city' => '',
            'bio' => '',
            'services' => [],
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'phone', 'city', 'bio', 'services']);
    }

    public function test_non_admin_cannot_create_talent(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->get('/admin/talents/create');
        $response->assertStatus(403);

        $postResponse = $this->actingAs($talent)->post('/admin/talents', [
            'name' => 'Unauthorized Talent',
        ]);
        $postResponse->assertStatus(403);
    }

    public function test_admin_can_access_edit_talent_page(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($admin)->get("/admin/talents/{$talent->id}/edit");
        $response->assertStatus(200);
        $response->assertSee("Edit Talent: {$talent->name}");
        $response->assertSee('Data Profil Talent');
        $response->assertSee('Paket Jasa / Layanan Talent');
        $response->assertSee('Simpan Perubahan Talent & Layanan', false);
    }

    public function test_admin_can_update_talent_profile_and_edit_services(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'admin')->firstOrFail();
        $talent = User::where('role', 'talent')->firstOrFail();
        $existingService = $talent->services()->firstOrFail();

        $newAvatar = UploadedFile::fake()->image('updated_avatar.jpg', 500, 500);

        $response = $this->actingAs($admin)->put("/admin/talents/{$talent->id}", [
            'name' => 'Clarissa Updated',
            'email' => $talent->email,
            'phone' => '081299998888',
            'city' => 'Semarang',
            'bio' => 'Bio baru yang sudah diperbarui oleh admin.',
            'avatar' => $newAvatar,
            'is_active' => '1',
            'services' => [
                [
                    'id' => $existingService->id,
                    'service_name' => 'Chat Seharian VIP',
                    'duration' => '1 Hari Penuh',
                    'price' => 75000,
                    'description' => 'Fast response prioritas utama',
                    'is_active' => '1',
                ],
                [
                    // Layanan baru ditambahkan saat edit
                    'service_name' => 'Teman Nonton Bioskop Online',
                    'duration' => '1 Film',
                    'price' => 60000,
                    'description' => 'Nonton bareng via teleparty',
                    'is_active' => '1',
                ],
            ],
        ]);

        $response->assertRedirect('/admin/talents');
        $response->assertSessionHas('success');

        $talent->refresh();
        $this->assertEquals('Clarissa Updated', $talent->name);
        $this->assertEquals('Semarang', $talent->city);
        $this->assertEquals('081299998888', $talent->phone);
        $this->assertEquals('Bio baru yang sudah diperbarui oleh admin.', $talent->bio);
        $this->assertNotNull($talent->avatar);
        Storage::disk('public')->assertExists($talent->avatar);

        // Verifikasi service lama terupdate
        $existingService->refresh();
        $this->assertEquals('Chat Seharian VIP', $existingService->service_name);
        $this->assertEquals(75000, $existingService->price);

        // Verifikasi service baru terbuat
        $this->assertDatabaseHas('services', [
            'talent_id' => $talent->id,
            'service_name' => 'Teman Nonton Bioskop Online',
            'price' => 60000,
        ]);
    }

    public function test_non_admin_cannot_edit_or_update_talent(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->get("/admin/talents/{$talent->id}/edit");
        $response->assertStatus(403);

        $putResponse = $this->actingAs($talent)->put("/admin/talents/{$talent->id}", [
            'name' => 'Hacker Update',
        ]);
        $putResponse->assertStatus(403);
    }

    public function test_admin_can_delete_talent(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $talent = User::create([
            'name' => 'Talent To Delete',
            'email' => 'talent_to_delete@example.com',
            'password' => bcrypt('password123'),
            'role' => 'talent',
            'phone' => '081299990000',
            'city' => 'Jakarta',
            'is_active' => true,
        ]);

        $service = $talent->services()->create([
            'service_name' => 'Layanan Hapus',
            'duration' => '1 Jam',
            'price' => 50000,
            'is_active' => true,
            'category' => 'basic',
        ]);

        Order::create([
            'order_code' => 'DEL-' . uniqid(),
            'talent_id' => $talent->id,
            'service_id' => $service->id,
            'customer_name' => 'Tester',
            'customer_whatsapp' => '081211112222',
            'booking_schedule' => now()->addDays(1),
            'notes' => 'Test order to delete',
            'service_name_snapshot' => $service->service_name,
            'price_snapshot' => $service->price,
        ]);

        $response = $this->actingAs($admin)->delete("/admin/talents/{$talent->id}");

        $response->assertRedirect('/admin/talents');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $talent->id]);
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
        $this->assertDatabaseMissing('orders', ['talent_id' => $talent->id]);
    }

    public function test_non_admin_cannot_delete_talent(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->delete("/admin/talents/{$talent->id}");
        $response->assertStatus(403);
    }

    public function test_admin_can_view_orders_index_page(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $talent = User::where('role', 'talent')->firstOrFail();
        $service = $talent->services()->firstOrFail();

        $order = Order::create([
            'order_code' => 'RP-TEST-0001',
            'talent_id' => $talent->id,
            'service_id' => $service->id,
            'customer_name' => 'Andi Wijaya',
            'customer_whatsapp' => '081234567890',
            'booking_schedule' => now()->addDays(2),
            'notes' => 'Tolong datang on time ya',
            'service_name_snapshot' => $service->service_name,
            'price_snapshot' => $service->price,
        ]);

        $response = $this->actingAs($admin)->get('/admin/orders');
        $response->assertStatus(200);
        $response->assertSee('Daftar Pesanan Masuk');
        $response->assertSee('RP-TEST-0001');
        $response->assertSee('Andi Wijaya');
        $response->assertSee('081234567890');
    }

    public function test_admin_can_search_and_filter_orders(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $talent = User::where('role', 'talent')->firstOrFail();
        $service = $talent->services()->firstOrFail();

        $order1 = Order::create([
            'order_code' => 'RP-CARI-1111',
            'talent_id' => $talent->id,
            'service_id' => $service->id,
            'customer_name' => 'Bambang Spesifik',
            'customer_whatsapp' => '081211112222',
            'booking_schedule' => now()->addDay(),
            'service_name_snapshot' => $service->service_name,
            'price_snapshot' => $service->price,
        ]);

        $order2 = Order::create([
            'order_code' => 'RP-LAIN-2222',
            'talent_id' => $talent->id,
            'service_id' => $service->id,
            'customer_name' => 'Doni Berbeda',
            'customer_whatsapp' => '081233334444',
            'booking_schedule' => now()->addDays(3),
            'service_name_snapshot' => $service->service_name,
            'price_snapshot' => $service->price,
        ]);

        // Search by name
        $searchResponse = $this->actingAs($admin)->get('/admin/orders?q=Bambang');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('RP-CARI-1111');
        $searchResponse->assertDontSee('RP-LAIN-2222');

        // Search by order code
        $codeResponse = $this->actingAs($admin)->get('/admin/orders?q=LAIN-2222');
        $codeResponse->assertStatus(200);
        $codeResponse->assertSee('RP-LAIN-2222');
        $codeResponse->assertDontSee('RP-CARI-1111');
    }

    public function test_admin_can_view_order_show_page(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $talent = User::where('role', 'talent')->firstOrFail();
        $service = $talent->services()->firstOrFail();

        $order = Order::create([
            'order_code' => 'RP-DETAIL-3333',
            'talent_id' => $talent->id,
            'service_id' => $service->id,
            'customer_name' => 'Cindy Melati',
            'customer_whatsapp' => '081255556666',
            'booking_schedule' => now()->addDays(1),
            'notes' => 'Catatan penting untuk talent',
            'service_name_snapshot' => $service->service_name,
            'price_snapshot' => $service->price,
        ]);

        $response = $this->actingAs($admin)->get("/admin/orders/{$order->id}");
        $response->assertStatus(200);
        $response->assertSee('RP-DETAIL-3333');
        $response->assertSee('Cindy Melati');
        $response->assertSee('Catatan penting untuk talent');
        $response->assertSee($talent->name);
    }

    public function test_admin_can_delete_order(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $talent = User::where('role', 'talent')->firstOrFail();
        $service = $talent->services()->firstOrFail();

        $order = Order::create([
            'order_code' => 'RP-DEL-4444',
            'talent_id' => $talent->id,
            'service_id' => $service->id,
            'customer_name' => 'Pesanan Dihapus',
            'customer_whatsapp' => '081277778888',
            'booking_schedule' => now()->addDays(2),
            'service_name_snapshot' => $service->service_name,
            'price_snapshot' => $service->price,
        ]);

        $deleteResponse = $this->actingAs($admin)->delete("/admin/orders/{$order->id}");
        $deleteResponse->assertSessionHas('success');
        $this->assertNull(Order::find($order->id));
    }

    public function test_non_admin_cannot_access_orders(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->get('/admin/orders');
        $response->assertStatus(403);
    }

    public function test_admin_can_view_profile_page(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->get('/admin/profile');

        $response->assertStatus(200);
        $response->assertSee('Kelola Profil Administrator');
        $response->assertSee($admin->name);
        $response->assertSee($admin->email);
        $response->assertSee('Data Profil Admin');
        $response->assertSee('Keamanan & Password', false);
    }

    public function test_admin_can_update_profile_info(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->put('/admin/profile', [
            'name' => 'Super Administrator Baru',
            'email' => 'superadmin.baru@rentalpacar.com',
            'phone' => '081299998888',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('profile_success');

        $admin->refresh();
        $this->assertEquals('Super Administrator Baru', $admin->name);
        $this->assertEquals('superadmin.baru@rentalpacar.com', $admin->email);
        $this->assertEquals('081299998888', $admin->phone);
    }

    public function test_admin_cannot_update_profile_with_existing_email(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($admin)->put('/admin/profile', [
            'name' => 'Nama Bebas',
            'email' => $talent->email,
            'phone' => '081299998888',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_admin_can_change_password(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('password_success');

        $admin->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $admin->password));
    }

    public function test_admin_cannot_change_password_with_incorrect_current_password(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'passwordsalah123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password123', $admin->fresh()->password));
    }

    public function test_admin_cannot_change_password_with_mismatched_confirmation(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'berbedapassword',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_non_admin_cannot_access_or_update_admin_profile(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $responseGet = $this->actingAs($talent)->get('/admin/profile');
        $responseGet->assertStatus(403);

        $responsePutProfile = $this->actingAs($talent)->put('/admin/profile', [
            'name' => 'Hacker Name',
            'email' => 'hacker@test.com',
            'phone' => '081234567890',
        ]);
        $responsePutProfile->assertStatus(403);

        $responsePutPassword = $this->actingAs($talent)->put('/admin/profile/password', [
            'current_password' => 'password',
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ]);
        $responsePutPassword->assertStatus(403);
    }

    public function test_talent_can_view_forgot_password_page(): void
    {
        $response = $this->get('/talent/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Lupa Kata Sandi Talent');
        $response->assertSee('Alamat Email Talent');
        $response->assertSee('Kirim Kode OTP');
    }

    public function test_talent_can_request_otp_email(): void
    {
        Mail::fake();

        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->post('/talent/forgot-password', [
            'email' => $talent->email,
        ]);

        $response->assertRedirect('/talent/reset-password');
        $response->assertSessionHas('status');
        $response->assertSessionHas('talent_reset_email', $talent->email);

        // Pastikan token OTP tersimpan di tabel database
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $talent->email,
        ]);

        Mail::assertSent(TalentOtpMail::class, function ($mail) use ($talent) {
            return $mail->hasTo($talent->email) && !empty($mail->otp) && strlen($mail->otp) === 6;
        });
    }

    public function test_admin_email_cannot_request_talent_otp(): void
    {
        Mail::fake();

        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->post('/talent/forgot-password', [
            'email' => $admin->email,
        ]);

        $response->assertSessionHasErrors(['email']);
        Mail::assertNothingSent();
    }

    public function test_talent_can_view_reset_password_page_with_session(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->withSession(['talent_reset_email' => $talent->email])
            ->get('/talent/reset-password');

        $response->assertStatus(200);
        $response->assertSee('Verifikasi OTP & Kata Sandi Baru', false);
        $response->assertSee($talent->email);
        $response->assertSee('Kode OTP (6 Digit Angka)');
    }

    public function test_talent_cannot_view_reset_password_page_without_session(): void
    {
        $response = $this->get('/talent/reset-password');

        $response->assertRedirect(route('talent.password.request'));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_talent_can_reset_password_with_valid_otp(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();
        $otp = '829143';

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $talent->email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        $response = $this->withSession(['talent_reset_email' => $talent->email])
            ->post('/talent/reset-password', [
                'email' => $talent->email,
                'otp' => $otp,
                'password' => 'sandibaru999',
                'password_confirmation' => 'sandibaru999',
            ]);

        $response->assertRedirect(route('talent.login'));
        $response->assertSessionHas('success');

        $talent->refresh();
        $this->assertTrue(Hash::check('sandibaru999', $talent->password));

        // Pastikan token dihapus setelah reset berhasil
        $this->assertEquals(0, DB::table('password_reset_tokens')->where('email', $talent->email)->count());
    }

    public function test_talent_cannot_reset_password_with_invalid_otp(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $talent->email],
            [
                'token' => Hash::make('123456'),
                'created_at' => now(),
            ]
        );

        $response = $this->withSession(['talent_reset_email' => $talent->email])
            ->post('/talent/reset-password', [
                'email' => $talent->email,
                'otp' => '999999',
                'password' => 'sandibaru999',
                'password_confirmation' => 'sandibaru999',
            ]);

        $response->assertSessionHasErrors(['otp']);
        $this->assertTrue(Hash::check('password123', $talent->fresh()->password));
    }

    public function test_talent_cannot_reset_password_with_expired_otp(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();
        $otp = '654321';

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $talent->email],
            [
                'token' => Hash::make($otp),
                'created_at' => now()->subMinutes(20),
            ]
        );

        $response = $this->withSession(['talent_reset_email' => $talent->email])
            ->post('/talent/reset-password', [
                'email' => $talent->email,
                'otp' => $otp,
                'password' => 'sandibaru999',
                'password_confirmation' => 'sandibaru999',
            ]);

        $response->assertSessionHasErrors(['otp']);
        $this->assertTrue(Hash::check('password123', $talent->fresh()->password));
    }

    public function test_talent_can_resend_otp(): void
    {
        Mail::fake();

        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->withSession([
            'talent_reset_email' => $talent->email,
            'talent_otp_sent_at' => now()->subSeconds(70)->timestamp,
        ])->post('/talent/resend-otp', [
            'email' => $talent->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        Mail::assertSent(TalentOtpMail::class);
    }

    public function test_admin_user_cannot_login_via_talent_portal(): void
    {
        $response = $this->post('/talent/login', [
            'email' => 'admin@rentalpacar.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_talent_login_rate_limiting_locks_out_after_five_failed_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/talent/login', [
                'email' => 'clarissa@rentalpacar.com',
                'password' => 'wrongpassword',
            ]);
        }

        $lockoutResponse = $this->post('/talent/login', [
            'email' => 'clarissa@rentalpacar.com',
            'password' => 'password123',
        ]);

        $lockoutResponse->assertSessionHasErrors('email');
        $this->assertStringContainsString('Terlalu banyak percobaan masuk', session('errors')->first('email'));
        $this->assertGuest();
    }

    public function test_admin_login_rate_limiting_locks_out_after_five_failed_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => 'admin@rentalpacar.com',
                'password' => 'wrongpassword',
            ]);
        }

        $lockoutResponse = $this->post('/admin/login', [
            'email' => 'admin@rentalpacar.com',
            'password' => 'password123',
        ]);

        $lockoutResponse->assertSessionHasErrors('email');
        $this->assertStringContainsString('Terlalu banyak percobaan masuk', session('errors')->first('email'));
        $this->assertGuest();
    }

    public function test_talent_dashboard_renders_tabs_and_components(): void
    {
        $talent = User::where('role', 'talent')->firstOrFail();

        $response = $this->actingAs($talent)->get(route('talent.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('data-tab-target="services"', false);
        $response->assertSee('data-tab-target="orders"', false);
        $response->assertSee('data-tab-target="profile"', false);
        $response->assertSee('data-tab-target="security"', false);
        $response->assertSee('Tambah Paket Layanan Baru');
        $response->assertSee('Riwayat Pesanan Masuk');
        $response->assertSee('Profil & Identitas Talent', false);
        $response->assertSee('Keamanan & Password', false);
    }
}
