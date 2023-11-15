<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\ProvinceRequest;
use App\Models\Product;
use App\Models\Province;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProvinceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Province::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Collection
    {
        return Province::hydrate(DB::select('select * from provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProvinceRequest $request): Province
    {
        DB::insert('insert into provinces(name, created_at, updated_at) values (:name,now(),now())', $request->validated());

        return Province::hydrate(DB::select('select * from provinces where id = ?', [DB::getPdo()->lastInsertId()]))->first();
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province): Province
    {
        return $province;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProvinceRequest $request, Province $province): Province
    {
        DB::update('update provinces set name = :name, updated_at = now() where id = :id', [...$request->validated(), 'id' => $province->id]);

        return Product::hydrate(DB::select('select * from provinces where id = ?', [$province->id]))->first();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province): JsonResponse
    {
        DB::delete('delete from provinces where id = ?', [$province->id]);

        return response()->json(['message' => "You've deleted the province successfully."]);
    }
}
