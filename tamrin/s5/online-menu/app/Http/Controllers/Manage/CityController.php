<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\CityRequest;
use App\Models\City;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(City::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Collection
    {
        return City::hydrate(DB::select('select * from cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CityRequest $request): City
    {
        DB::insert('insert into cities (province_id,name, created_at, updated_at) values (:province_id,:name,now(),now())', $request->validated());

        return City::hydrate(DB::select('select * from cities where id = ?', [DB::getPdo()->lastInsertId()]))->first();
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city): City
    {
        return $city;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CityRequest $request, City $city): City
    {
        DB::update('update cities set province_id = :province_id, name = :name, updated_at = now() where id = :id', [...$request->validated(), 'id' => $city->id]);

        return City::hydrate(DB::select('select * from cities where id = ?', [$city->id]))->first();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city): JsonResponse
    {
        DB::delete('delete from cities where id = ?', [$city->id]);

        return response()->json(['message' => "You've deleted the city successfully."]);
    }
}
