<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cleaner;
use Resources\CleanerResource;
use Resources\CleanerCollection;

class CleanerController extends Controller
{
    public function index()
    {
        return CleanerCollection::collection(Cleaner::paginate(5));
    }


 
    public function store(Request $request)
    {
        return Cleaner::create($request->all());
    }


    public function update(Request $request, Cleaner $cleaner)
    {
        
        $cleaner->update($request->all());

        return $cleaner;
    }


    public function destroy(Cleaner $cleaner)
    {
        $cleaner->delete();

        return 204;
    }
}
