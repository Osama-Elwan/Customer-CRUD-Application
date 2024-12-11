<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    public function index(Request $request)
    {
        //add search feature
        // if($request->has('search')) {
        //     // dd($request->all());

        // }

        $customers = Customer::when($request->has('search'),function($query) use ($request){ //to access $request use (use)
            $query->where('first_name','LIKE', "%$request->search%")
            ->orWhere('last_name','LIKE', "%$request->search%")
            ->orWhere('phone','LIKE', "%$request->search%")
            ->orWhere('email','LIKE', "%$request->search%");

        // })->get();
        // })->orderBy('id','desc')->get();//oroder by asc and desc
        })->orderBy('id',$request->has('order') && $request->order =='asc' ? 'ASC': 'DESC')->get();//oroder by asc and desc

        //end of search
        // $customers = Customer::all();
        // return view('customer.index',['customers'=>$customers]);
        return view('customer.index',compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreRequest $request)
    {
        $customer = new Customer();
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName= $image->store('','public');
            $filePath ='/uploads/' . $fileName;
            $customer->image = $filePath;
        }
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->bank_account_number = $request->bank_account_number;
        $customer->about = $request->about;
        $customer->save();
        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::findorfail($id);
        return view('customer.show',compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(int $id)//change the type as u want from here but there is another way will talk about it later
    public function edit(string $id)
    {
        $customer = Customer::findorfail($id);
        return view('customer.edit',compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerStoreRequest $request, string $id)
    {
        $customer = Customer::findorfail($id);

        if($request->hasFile('image') ) {
            //delete old image
            // dd(public_path($customer->image));
            if($customer->image != '/default-images/avatar.png') {
                File::delete(public_path($customer->image));
            }
            $image = $request->file('image');
            $fileName= $image->store('','public');
            $filePath ='/uploads/' . $fileName;
            $customer->image = $filePath;
        }
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->bank_account_number = $request->bank_account_number;
        $customer->about = $request->about;
        $customer->save();
        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findorfail($id);
        // if($customer->image != '/default-images/avatar.png') { //replace this to be in hard delete
        //     File::delete(public_path($customer->image));
        // }
        $customer->delete();
        return redirect()->back();
    }


    function trashIndex(Request $request) {

        $customers = Customer::onlyTrashed()
        ->when($request->has('search') , function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('last_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('phone', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%");
            });
        })
    ->orderBy('id', $request->has('order') && $request->order == 'asc' ? 'ASC' : 'DESC')
    ->get();


        return view('customer.trash',compact('customers'));

    }
    /**
     * Restore specified resource from storage.
     */
    function restore(int $id) {//int is type hinting
        $customer = Customer::withTrashed()->findorfail($id);
        $customer->restore();
        return redirect()->back();
    }
    /**
     * forceDestroy specified resource from storage.
     */
    function forceDestroy(int $id) {
        $customer = Customer::withTrashed()->findorfail($id);

        if($customer->image != '/default-images/avatar.png') {
            File::delete(public_path($customer->image));
        }
        $customer->forceDelete();//dont forget its here forceDelete dont use delete only
        return redirect()->back();
    }
}
