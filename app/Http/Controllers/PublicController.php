<?php

namespace App\Http\Controllers;

use App\Model\Complaint;
use App\Model\ComplaintType;
use App\Model\Manifest;
use App\Model\ManifestTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    public function tracking(Request $request)
    {
        $resi = $request->no_resi;
        $tracking = Manifest::where('receipt_number', $request->no_resi)
            ->with(['tracking' => function ($query) {
                $query->orderBy('is_outbond', 'desc');
            }])
            ->first();
        return view('public.tracking', ['tracking' => $tracking, 'resi' => $resi]);
    }

    public function complain()
    {
        $complaintType = ComplaintType::all();
        return view('public.complain', ['complaintType' => $complaintType]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComplain(Request $request)
    {
        $data = Validator::make($request->all(), [
            'full_name' => 'required',
            'rating' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'complaint_type' => 'required',
            'receipt_number' => 'required',
            'complaint_description' => 'required',
        ]);

        if ($data->fails()) {
            return back()
                ->withErrors($data)
                ->withInput();
        }



        Complaint::create([
            'full_name' => $request->full_name,
            'rating' => $request->rating,
            'email' => $request->email,
            'phone' => $request->phone,
            'complaint_type' => $request->complaint_type,
            'receipt_number' => $request->receipt_number,
            'destination' => $request->destination,
            'complaint_description' => $request->complaint_description
        ]);

        $message = 'Halo Admin,

📢 Anda telah menerima pengaduan baru dari customer. Berikut detail pengaduannya:

👤 Nama Customer: ' . $request->full_name . '
📧 Email: ' . $request->email . '
📱 Nomor Telepon: ' . $request->phone . '
📦 Nomor Resi: ' . $request->receipt_number . '
🎯 Tujuan: ' . $request->destination . '
⭐ Rating: ' . $request->rating . '
📝 Keterangan: ' . $request->complaint_description . '

Silakan segera tindak lanjuti pengaduan ini melalui sistem.

Terima kasih.';

        $sendNotif = new WhatsappController();
        $sendNotif->sendMessage('0811886486', $message);

        
        return back()->with('success', 'Complaint submitted successfully');
    }

    public function querySearchResi(Request $request)
    {
        $resi = Manifest::where('receipt_number', $request->receipt_number)->first();

        if ($resi) {
            return response()->json([
                'status' => 200,
                'resi' => $resi
            ]);
        } else {
            return response()->json([
                'status' => 404
            ]);
        }
    }
}
