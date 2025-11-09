<?php

namespace App\Http\Controllers;

use App\Model\Manifest;
use App\Model\ManifestProduct;
use App\Model\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Util\Json;
use Yajra\DataTables\Facades\DataTables;
use Mpdf\Mpdf as PDF;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Logo\Logo;
use App\Model\District;

class ManifestController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Manifest::with('shipping')->latest();

            return DataTables::eloquent($data)->filter(function ($query) use ($request) {
                if($request->start_date != null && $request->end_date != null){
                    $query->whereBetween('date_manifest', [$request->start_date ?? Carbon::now()->format('Y-m-d'), $request->end_date ?? Carbon::now()->format('Y-m-d')]);
                }
                if($request->has('status') && $request->status != 'all'){
                    if($request->status == 0){
                        $query->whereNull('warehouse_exit_date');
                    }else{
                        $query->whereNotNull('warehouse_exit_date');
                    }
                }
                if(Auth::user()->role == 'finance'){
                    $query->where('status', 1);
                }
            })->make(true);
        }

        return view('gudang.manifest');
    }

    public function create()
    {
        $counter = $this->getResiCounter();
        return view('gudang.create', compact('counter'));
    }

    public function store(Request $request)
    {
        // dd(json_decode($request->details));
        $request->validate([
            'invoice_type' => 'required',
            'date_manifest' => 'date',
            'sales_name' => 'nullable',
            'shipping_name' => 'nullable',
            'shipping_address' => 'nullable',
            'shipping_city' => 'nullable',
            'destination' => 'nullable',
            'phone_number' => 'required',
            'moda' => 'required',
            'drop_pickup' => 'nullable',
            'recipient_address' => 'nullable',
            'recipient_city' => 'nullable',
            'recipient_zip' => 'nullable',
            'recipient_name' => 'nullable',
            'recipient_phone' => 'nullable',
            'photo_product' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo_travel_document' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'destination_id' => 'required',
        ]);

        $details = json_decode($request->details);

        // Validator::make((array) $details, [
        //     'product_name.*' => 'required',
        //     'colly.*' => 'required',
        //     'weight.*' => 'required',
        //     'dimension_p.*' => 'required',
        //     'dimension_l.*' => 'required',
        //     'dimension_t.*' => 'required',
        // ]);


        DB::beginTransaction();
        try {

            $destination = District::findOrFail($request->destination_id);

            if($destination->code == "" || $destination->code == null){
                $duaKarakterAwal = substr($destination->regency_id, 0, 2);
                $otherDestination = District::where('regency_id','like', $duaKarakterAwal.'%')->orderBy('code', 'desc')->where('code', '!=', null)->where('code', '!=', "")->first();
                // Pisahkan huruf
                preg_match_all('/[a-zA-Z]+/', $otherDestination->code, $huruf);
                // Pisahkan angka
                preg_match_all('/\d+/', $otherDestination->code, $angka);

                $huruf_string = implode('', $huruf[0]); // Menggabungkan array menjadi satu string
                $angka_string = implode('', $angka[0]);

                $destination->code = $huruf_string . ($angka_string + 1);

                $destination->save();
            }

            $counter = $this->getResiCounter();
            $receipt_number = '165-'.$destination->code.$counter;

            $data = [
                'user_id' => Auth::user()->id ?? 1,
                'date_manifest' => $request->date_manifest,
                'invoice_type' => $request->invoice_type,
                'sales_name' => $request->sales_name,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'destination' => $request->destination,
                'phone_number' => $request->phone_number,
                'receipt_number' => $receipt_number,
                'moda' => $request->moda,
                'drop_pickup' => $request->drop_pickup,
                "total_colly" => $request->total_colly,
                "total_weight" => $request->total_weight,
                "total_volume" => $request->total_volume,
                "total_volume_m3" => $request->total_volume_m3,
                "total_actual" => $request->total_actual,
                "total_chargeable_weight" => $request->total_chargeable_weight,
                "total_charge_packaging" => $request->total_charge_packaging,
                'recipient_company' => $request->recipient_company,
                'recipient_address' => $request->recipient_address,
                'recipient_city' => $request->recipient_city,
                'recipient_zip' => $request->recipient_zip,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'created_by' => Auth::user()->uuid,
                'recipient_detail' => $request->recipient_detail,
                'instruction_special' => $request->instruction_special,
                'payment_type' => $request->payment_type,
                'do_number' => $request->do_number,
                'service_type' => $request->service_type,
                'vendor_id' => $request->vendor_id,
                'vendor_name' => $request->vendor_name,
                'vendor_type' => $request->vendor_type,
            ];

            if($request->hasFile('photo_product')){
                $image_product = $request->file('photo_product');
                $image_product_path = $image_product->store('manifest', 'public');
                $data['photo_product'] = $image_product_path;
            }

            if($request->hasFile('photo_travel_document')){
                $image_travel_document = $request->file('photo_travel_document');
                $image_travel_document_path = $image_travel_document->store('manifest', 'public');
                $data['photo_travel_document'] = $image_travel_document_path;
            }

            $manifest = Manifest::create($data);
            foreach ($details as $key => $value) {
                $manifest->product()->create([
                    'product_name' => $value->product_name,
                    'colly' => $value->colly,
                    'weight' => $value->weight,
                    'dimension_p' => $value->dimension_p,
                    'dimension_l' => $value->dimension_l,
                    'dimension_t' => $value->dimension_t,
                    'volume' => $value->volume,
                    'volume_m3' => $value->volume_m3,
                    'actual' => $value->actual,
                    'chargeable_weight' => $value->chargeable_weight,
                    'packaging' => $value->packaging ?? 0,
                    'charge_packaging' => $value->charge_packaging ?? 0,
                ]);
            }


            $this->incrementResiCounter();

            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Membuat Manifest.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal Membuat Manifest.',
                'error' => $th->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        $manifest = Manifest::with('details')->find($id);
        return view('gudang.edit', compact('manifest'));
    }

    public function duplicate($id)
    {
        $manifest = Manifest::with('details')->find($id);
        $counter = $this->getResiCounter();
        return view('gudang.duplicate', compact('manifest','counter'));
    }
    public function show($id)
    {
        $manifest = Manifest::with('details')->find($id);
        return view('gudang.detail', compact('manifest'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_type' => 'required',
            'date_manifest' => 'date',
            'sales_name' => 'required',
            'shipping_name' => 'nullable',
            'shipping_address' => 'nullable',
            'shipping_city' => 'nullable',
            'destination' => 'nullable',
            'phone_number' => 'nullable',
            'receipt_number' => 'nullable',
            'moda' => 'nullable',
            'drop_pickup' => 'nullable',
            'photo_product' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo_travel_document' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'recipient_address' => 'required',
            'recipient_city' => 'required',
            'recipient_zip' => 'nullable',
            'recipient_name' => 'nullable',
            'recipient_phone' => 'nullable',
        ]);

        $details = json_decode($request->details);

        // Validator::make((array) $details, [
        //     'product_name.*' => 'required',
        //     'colly.*' => 'required',
        //     'weight.*' => 'required',
        //     'dimension_p.*' => 'required',
        //     'dimension_l.*' => 'required',
        //     'dimension_t.*' => 'required',
        // ]);


        DB::beginTransaction();
        try {

            $data = [
                'user_id' => Auth::user()->id ?? 1,
                'date_manifest' => $request->date_manifest,
                'invoice_type' => $request->invoice_type,
                'sales_name' => $request->sales_name,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'destination' => $request->destination,
                'phone_number' => $request->phone_number,
                'receipt_number' => $request->receipt_number,
                'moda' => $request->moda,
                'drop_pickup' => $request->drop_pickup,
                "total_colly" => $request->total_colly,
                "total_weight" => $request->total_weight,
                "total_volume" => $request->total_volume,
                "total_volume_m3" => $request->total_volume_m3,
                "total_actual" => $request->total_actual,
                "total_chargeable_weight" => $request->total_chargeable_weight,
                "total_charge_packaging" => $request->total_charge_packaging,
                'recipient_company' => $request->recipient_company,
                'recipient_address' => $request->recipient_address,
                'recipient_city' => $request->recipient_city,
                'recipient_zip' => $request->recipient_zip,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'recipient_detail' => $request->recipient_detail,
                'instruction_special' => $request->instruction_special,
                'payment_type' => $request->payment_type,
                'do_number' => $request->do_number,
                'service_type' => $request->service_type,
                'vendor_id' => $request->vendor_id,
                'vendor_name' => $request->vendor_name,
                'vendor_type' => $request->vendor_type,
            ];

            if ($request->hasFile('photo_product')) {
                $image_product = $request->file('photo_product');
                $image_product_path = $image_product->store('manifest', 'public');
                $data['photo_product'] = $image_product_path;
            }

            if ($request->hasFile('photo_travel_document')) {
                $image_travel_document = $request->file('photo_travel_document');
                $image_travel_document_path = $image_travel_document->store('manifest', 'public');
                $data['photo_travel_document'] = $image_travel_document_path;
            }

            Manifest::where('id', $id)->update($data);


            ManifestProduct::where('manifest_id', $id)->delete();

            $manifest = Manifest::find($id);

            foreach ($details as $key => $value) {

                $manifest->product()->create([
                    'product_name' => $value->product_name,
                    'colly' => $value->colly,
                    'weight' => $value->weight,
                    'dimension_p' => $value->dimension_p,
                    'dimension_l' => $value->dimension_l,
                    'dimension_t' => $value->dimension_t,
                    'volume' => $value->volume,
                    'volume_m3' => $value->volume_m3,
                    'actual' => $value->actual,
                    'chargeable_weight' => $value->chargeable_weight,
                    'packaging' => $value->packaging,
                    'charge_packaging' => $value->charge_packaging,
                ]);
            }


            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Update Manifest.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal Update Manifest.',
                'error' => $th->getMessage()
            ]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $manifest = Manifest::find($id);
        $manifest->update([
            'status' => 1
        ]);

        return response()->json([
            'status' => 200,
            'msg' => 'Berhasil Update Status.'
        ]);
    }

    public function getVendor()
    {
        $vendor = Vendor::all();
        return response()->json([
            'status' => 200,
            'data' => $vendor
        ]);
    }

    public function delete($id)
    {
        $manifest = Manifest::with('product')->find($id);
        $manifest->product()->delete();
        $manifest->delete();
        return response()->json([
            'status' => 200,
            'msg' => 'Berhasil Delete Manifest.'
        ]);
    }

    public function cetakResi($id){
        ini_set("pcre.backtrack_limit", "5000000");
        ini_set("memory_limit", "-1");


        try {
            $manifest = Manifest::with('creaedUser')->find($id);
            $qrCodeBase64 = $this->generateQrCodeBase64($manifest->receipt_number);

            $document = new PDF([
                'mode' => 'utf-8',
                'format' => 'Legal',
                'orientation' => 'Portrait',
                'margin_top' => '0',
                'margin_left' => '10',
                'margin_right' => '10',
                'margin_bottom' => '0',
            ]);
            $document->showImageErrors = true;
            $document->SetWatermarkImage(new \Mpdf\WatermarkImage(public_path('mas-express-watermark.png')), 0.1, '', 'F');
            $document->showWatermarkImage = true;
            $document->watermarkImageAlpha = 0.1;
            $document->imageVars['myvariable'] = file_get_contents(public_path('mas-express.png'));
            $documentFileName = "STT" . $manifest->receipt_number .  ".pdf";

            $html = view('pdf.resi-new', compact('manifest', 'qrCodeBase64'));
            $document->img_dpi = 50;
            $document->writeHTML($html, \Mpdf\HTMLParserMode::DEFAULT_MODE);

            // Save PDF on your public storage
            $document->Output($documentFileName, "I");
        } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
            // Process the exception, log, print etc.
            echo $e->getMessage();
        }

    }

    public function generateQrCodeBase64($receiptNumber)
    {
        $writer = new PngWriter();

        // Create a QR code with the URL
        $qrCode = QrCode::create('https://masexpress.id/cek-resi?no_resi=' . $receiptNumber)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());

        // Use the PNG writer to generate the QR code
        $result = $writer->write($qrCode);

        // Get the QR code binary data
        $qrCodeData = $result->getString();

        // Encode the binary data as base64
        return 'data:image/png;base64,' . base64_encode($qrCodeData);
    }

    public function getDestination()
    {
        $districts = District::with('regency')->get();

        return response()->json($districts);
    }
}
