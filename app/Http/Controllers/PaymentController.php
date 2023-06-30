<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\DataTables\PaymentsDataTable;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaymentsDataTable $dataTable)
    {
        // $payments = payment::all();
        // return $dataTable->render('payments.index', compact('payments'));
        return $dataTable->render('payments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return View::make('payments.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $rules = [
            'payment_img' => 'required|array',
            'payment_img.*' => 'required|image|mimes:jpeg,jpg,png',
            'payment_name' => 'required',
        ];

        $messages = [
            'payment_img.required' => 'Please Input a payment Image',
            'payment_img.array' => 'Please Input at least one payment Image',
            'payment_img.*.required' => 'Please Input a payment Image',
            'image' => 'Image format not supported',
            'payment_name.required' => 'Please Input a payment Name',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $payments = new Payment;

        $payment_img = array();
        if ($request->hasFile('payment_img')) {
            foreach ($request->file('payment_img') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $payment_img[] = '/storage/images/' . $fileName;
            }
            $payments->payment_img = implode(',', $payment_img);
        }

        $payments->payment_name = $request->payment_name;
        $payments->save();

        return redirect()->route('payment.index')->with('message', 'Payment Created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payments = payment::find($id);
        return View::make('payments.edit', compact('payments'))->with('message', 'Payment Edited');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'payment_img' => 'array',
            'payment_img.*' => 'image|mimes:jpeg,jpg,png',
            'payment_name' => 'required',
        ];

        $messages = [
            'image' => 'Image format not supported',
            'payment_name.required' => 'Please Input a payment Name',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $payments = payment::find($id);

        $payment_img = array();
        if ($request->hasFile('payment_img')) {
            foreach ($request->file('payment_img') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $payment_img[] = '/storage/images/' . $fileName;
            }
            $payments->payment_img = implode(',', $payment_img);
        }

        $payments->payment_name = $request->payment_name;
        $payments->save();
        // if($request->file()) {
        //     $fileName = time().'_'.$request->file('payment_img')->getClientOriginalName();
        //     $filePath = $request->file('payment_img')->storeAs('uploads', $fileName,'public');
        //     $path = Storage::putFileAs(
        //         'public/images', $request->file('payment_img'), $fileName
        //     );
        //     $payments->payment_img = '/storage/images/' . $fileName;
        // }
        // $payments->payment_name = $request->payment_name;
        // $payments->save();
        return redirect()->route('payment.index')->with('message', 'Payment Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        payment::destroy($id);
        return back()->with('message', 'Payment Deleted');;
    }
}
