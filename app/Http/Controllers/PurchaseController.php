<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Models\Category;
use App\Models\Purchase;
use Illuminate\Support\Number;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    public function index() {
        $purchases = Purchase::query()->with('category')->get();
        collect($purchases)->each(function ($purchase) {
            $purchase->amount = Number::currency($purchase->amount / 100);
        });
        return Inertia::render('Purchase/Index', ['purchases' => $purchases]);
    }

    public function create() {
        $categories = Category::all();
        return Inertia::render('Purchase/Create', ['categories' => $categories] );
    }
    public function store (StorePurchaseRequest $request) {
        $data = $request->validated();
        Purchase::query()->create($data);

        return redirect()->route('purchase.index');
    }
}
