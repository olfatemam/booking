<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;
use Resources\CustomerResource;
use Resources\CustomerCollection;

class CustomerController extends Controller
{
    public function index()
    {
        return CustomerCollection::collection(Customer::paginate(5));
    }


 
    public function store(Request $request)
    {
        return Customer::create($request->all());
    }


    public function update(Request $request, Customer $customer)
    {
        
        $customer->update($request->all());

        return $customer;
    }


    public function destroy(Customer $customer)
    {
        $customer->delete();

        return 204;
    }
}
