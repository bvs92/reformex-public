<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Notifications\SendInvoiceToUserNotification;
use App\Payment;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoicesController extends Controller
{
    private $TVA = false;

    // download based on payment
    public function download($uuid)
    {

        $customer = auth()->user();

        if (!$customer->invoice_information) {
            return response()->json(['errors' => true], 401);
        }

        $payment = Payment::where('uuid', $uuid)->first();

        // $items = collect([
        //     'subject' => Str::ascii($payment->name),
        //     'amount' => $payment->amount_total / 100,
        // ]);

        // $total_amount =

        $data = [
            'id' => $payment->uuid,
            // 'logo_image' => URL::asset('assets/images/brand/reformex-logo.png'),
            'name' => Str::ascii($customer->last_name . ' ' . $customer->first_name),
            'email' => $customer->email,
            'created' => $payment->created_at,
            'tva' => $this->TVA ? '19%' : '0%',
            'hasTVA' => $this->TVA,
            'amount' => $payment->amount_total / 100,
            'company_name' => Str::ascii($customer->invoice_information->company_name),
            'address' => Str::ascii($customer->invoice_information->address),
            'cui' => $customer->invoice_information->cui,
            'number' => $customer->invoice_information->number,
            'subject' => Str::ascii($payment->name),
        ];
        $pdf = PDF::loadView('volgh.invoices.fiscal-pdf', $data);
        // $name = Str::uuid();
        $name = 'factura-' . $payment->uuid;
        return $pdf->download($name . '.pdf');
    }

    public function downloadInvoice($id)
    {

        $invoice = Invoice::find($id);

        if (!$this->authorize('update', $invoice)) {
            return response()->json(['errors' => true], 401);
        }

        // $filepath = storage_path('app/public') . '/invoices' . '/' . $invoice->name;
        // $filepath = Storage::disk('do_spaces')->get('uploads/invoices/' . $invoice->name);

        // return response()->json($filepath);

        if (Storage::disk('do_spaces')->exists('uploads/invoices/' . $invoice->name)) {
            // $mime_type = mime_content_type($filepath);

            $headers = [
                'Content-Type' => $invoice->mime_type,
                'Content-Disposition' => 'inline; filename="' . $invoice->name . '"',
                // 'Content-Type: application/pdf',
            ];

            return Storage::disk('do_spaces')->download('uploads/invoices/' . $invoice->name, $invoice->name, $headers);

            // return response()->download($filepath, $invoice->name, $headers);
        } else {
            return response()->json(['errors' => true], 404);
        }
    }

    public function uploadForPayment(Request $request, $uuid)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $payment = Payment::where('uuid', $uuid)->first();

        if (!$payment) {
            return response()->json(['errors' => true]);
        }

        if ($request->hasFile('invoice')) {
            $invoice = $request->file('invoice');

            $invoiceElement = new Invoice();
            $invoiceElement->payment_id = $payment->id;
            $invoiceElement->user_id = $payment->user_id;

            $infos = $this->prepareFile($invoice);
            $invoiceElement->name = $infos['file_name'];
            $invoiceElement->extension = $infos['ext'];
            $invoiceElement->mime_type = $infos['mime_type'];

            if (!$invoiceElement->save()) {
                return response()->json(['errors' => true]);
            }

            $this->uploadFile($invoice, $infos['file_name']);
        }

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['errors' => true], 404);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => true], 401);
        }

        $invoice_name = $invoice->name;

        if (!$invoice->delete()) {
            return response()->json(['errors' => true], 403);
        }

        // $filepath = storage_path('app/public') . '/invoices' . '/' . $invoice_name;

        // if (file_exists($filepath)) {
        //     unlink($filepath);
        // }

        $pathToFile = 'uploads/invoices/' . $invoice->name;
        if (Storage::disk('do_spaces')->exists($pathToFile)) {
            Storage::disk('do_spaces')->delete($pathToFile);
        }

        return response()->json(['success' => true]);
    }

    public function sendToUser($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['errors' => true], 404);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => true], 401);
        }

        $owner = $invoice->user;
        $payment = $invoice->payment;

        try {
            Notification::send($owner, new SendInvoiceToUserNotification($owner, $invoice));
        } catch (Exception $e) {
            return response()->json(['errors' => true], 401);
        }

        return response()->json(['success' => true]);
    }

    protected function prepareFile($file)
    {
        // Get image original extension
        $ext = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        $random = Str::uuid();

        // dd($mimeType);

        // Compose the name
        $file_name = 'factura-reformex-' . time() . "-" . $random . '.' . $ext;

        $infos = [
            'ext' => $ext,
            'mime_type' => $mimeType,
            'file_name' => $file_name,
        ];

        return $infos;
    }

    protected function uploadFile($file, $file_name)
    {
        // $this->make_directory();

        // Storage::disk('public')->putFileAs('invoices', $file, $file_name);

        $full_path = 'uploads/invoices';

        Storage::disk('do_spaces')->putFileAs($full_path, $file, $file_name, 'public');
    }

    private function make_directory()
    {

        if (!File::isDirectory(storage_path('app/public') . '/invoices')) {
            File::makeDirectory(storage_path('app/public') . '/invoices', 0777, true, true);
        }
    }
}
