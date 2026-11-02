<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSettingController extends Controller
{
    public function showWhatsAppSettings()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $defaultWa = env('WHATSAPP_ADMIN_NUMBER', '6281234567890');
        $whatsappHome = Setting::get('whatsapp_home_number', $defaultWa);
        $whatsappOrder = Setting::get('whatsapp_order_number', $defaultWa);

        return view('admin.settings.whatsapp', compact('whatsappHome', 'whatsappOrder', 'defaultWa'));
    }

    public function updateWhatsAppSettings(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $validated = $request->validate([
            'whatsapp_home_number' => 'required|string|min:8|max:25',
            'whatsapp_order_number' => 'required|string|min:8|max:25',
        ], [
            'whatsapp_home_number.required' => 'Nomor WhatsApp beranda wajib diisi.',
            'whatsapp_home_number.min' => 'Nomor WhatsApp beranda minimal 8 digit.',
            'whatsapp_order_number.required' => 'Nomor WhatsApp pemesanan wajib diisi.',
            'whatsapp_order_number.min' => 'Nomor WhatsApp pemesanan minimal 8 digit.',
        ]);

        $cleanHome = Setting::sanitizePhone($validated['whatsapp_home_number']);
        $cleanOrder = Setting::sanitizePhone($validated['whatsapp_order_number']);

        Setting::set('whatsapp_home_number', $cleanHome);
        Setting::set('whatsapp_order_number', $cleanOrder);

        return back()->with('success', 'Nomor WhatsApp beranda dan pemesanan berhasil diperbarui!');
    }
}

