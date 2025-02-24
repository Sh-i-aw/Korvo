<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    public function index() {
        $purchases = Purchase::all();
        return Inertia::render('Purchase/Index', ['purchases' => $purchases]);
    }

    public function create() {
        return Inertia::render('Purchase/Create');
    }
    public function store (StorePurchaseRequest $request) {
        $data = $request->validated();

        Purchase::query()->create($data);

        return redirect()->route('purchase.index');
    }
}
