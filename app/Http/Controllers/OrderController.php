<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'talent_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'customer_name' => 'required|string|max:100',
            'customer_whatsapp' => 'required|string|max:30',
            'booking_schedule' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $talent = User::where('id', $validated['talent_id'])
            ->where('role', 'talent')
            ->where('is_active', true)
            ->firstOrFail();

        $service = Service::where('id', $validated['service_id'])
            ->where('talent_id', $talent->id)
            ->where('is_active', true)
            ->firstOrFail();

        // Generate unique order code: RP-yymmdd-XXXX
        do {
            $orderCode = 'RP-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));
        } while (Order::where('order_code', $orderCode)->exists());

        $order = Order::create([
            'order_code' => $orderCode,
            'talent_id' => $talent->id,
            'service_id' => $service->id,
            'customer_name' => $validated['customer_name'],
            'customer_whatsapp' => $validated['customer_whatsapp'],
            'booking_schedule' => $validated['booking_schedule'],
            'notes' => $validated['notes'] ?? null,
            'service_name_snapshot' => $service->service_name,
            'price_snapshot' => $service->price,
        ]);

        return redirect()->route('orders.show', $order->order_code)->with('just_created', true);
    }

    public function show(string $order_code)
    {
        $order = Order::with(['talent', 'service'])
            ->where('order_code', $order_code)
            ->firstOrFail();

        $adminWa = \App\Models\Setting::get('whatsapp_order_number', config('services.whatsapp.admin_number', env('WHATSAPP_ADMIN_NUMBER', '6281234567890')));
        $scheduleFormatted = Carbon::parse($order->booking_schedule)->translatedFormat('l, d F Y - H:i');
        $priceFormatted = 'Rp ' . number_format($order->price_snapshot, 0, ',', '.');
        $duration = $order->service ? $order->service->duration : null;

        $message = "Halo Admin RentalPacar, saya ingin konfirmasi pesanan dengan detail berikut:\n\n"
            . "• Kode Pesanan: *{$order->order_code}*\n"
            . "• Nama: {$order->customer_name}\n"
            . "• No WhatsApp: {$order->customer_whatsapp}\n"
            . "• Talent: {$order->talent->name}\n"
            . "• Layanan: {$order->service_name_snapshot}" . ($duration ? " ({$duration})" : "") . "\n"
            . "• Tarif: {$priceFormatted}\n"
            . "• Jadwal: {$scheduleFormatted} WIB\n"
            . ($order->notes ? "• Catatan: {$order->notes}\n\n" : "\n")
            . "Mohon info ketersediaan dan nomor rekening pembayaran. Terima kasih!";

        $waUrl = "https://wa.me/{$adminWa}?text=" . rawurlencode($message);

        return view('orders.show', compact('order', 'waUrl', 'message', 'adminWa', 'scheduleFormatted', 'priceFormatted', 'duration'));
    }
}

