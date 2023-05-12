<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\DataTables\ShipmentsDataTable;
use Illuminate\Http\Request;
use App\Models\Shipment;


class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ShipmentsDataTable $dataTable)
    {
        $shipments = shipment::all();
        return $dataTable->render('shipments.index');
        // return View::make('shipments.index',compact('shipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return View::make('shipments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'shipment_img' => 'required|array',
            'shipment_img.*' => 'required|image|mimes:jpeg,jpg,png',
            'shipment_name' => 'required',
            'shipment_cost' => 'required',
        ];

        $messages = [
            'shipment_img.required' => 'Please Input a Shipment Image',
            'image' => 'Image format not supported',
            'shipment_name.required' => 'Please Input a Shipment Name',
            'shipment_cost.required' => 'Please Input a Shipment Cost',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $shipments = new shipment;

        $shipment_img = array();
        if ($request->hasFile('shipment_img')) {
            foreach ($request->file('shipment_img') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $shipment_img[] = '/storage/images/' . $fileName;
            }
            $shipments->shipment_img = implode(',', $shipment_img);
        }
        // if($request->file()) {
        //     $fileName = time().'_'.$request->file('shipment_img')->getClientOriginalName();
        //     $filePath = $request->file('shipment_img')->storeAs('uploads', $fileName,'public');
        //     $path = Storage::putFileAs(
        //         'public/images', $request->file('shipment_img'), $fileName
        //     );
        //     $shipments->shipment_img = '/storage/images/' . $fileName;
        // }
        $shipments->shipment_name = $request->shipment_name;
        $shipments->shipment_cost = $request->shipment_cost;
        $shipments->save();
        return redirect()->route('shipment.index')->with('message', 'Shipment Created!');
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
        $shipments = shipment::find($id);
        return View::make('shipments.edit', compact('shipments'))->with('message', 'Shipment Edited');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'shipment_img' => 'array',
            'shipment_img.*' => 'image|mimes:jpeg,jpg,png',
            'shipment_name' => 'required',
            'shipment_cost' => 'required',
        ];

        $messages = [
            'image' => 'Image format not supported',
            'shipment_name.required' => 'Please Input a Shipment Name',
            'shipment_cost.required' => 'Please Input a Shipment Cost',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $shipments = shipment::find($id);
        $shipment_img = array();
        if ($request->hasFile('shipment_img')) {
            foreach ($request->file('shipment_img') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $shipment_img[] = '/storage/images/' . $fileName;
            }
            $shipments->shipment_img = implode(',', $shipment_img);
        }
        $shipments->shipment_name = $request->shipment_name;
        $shipments->shipment_cost = $request->shipment_cost;
        $shipments->save();
        return redirect()->route('shipment.index')->with('message', 'Shipment Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        shipment::destroy($id);
        return back()->with('message', 'Shipment Deleted');;
    }
}
