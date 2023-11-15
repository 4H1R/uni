<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
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
    public function index(Request $request): Collection
    {
        $request->validate([
            'query' => ['nullable', 'string', 'max:255'],
            'province_id' => ['nullable', 'integer'],
        ]);

        $select = [
            'query' => 'select * from cities where name like :query ',
            'params' => [
                'query' => '%'.$request->get('query').'%',
            ],
        ];

        if ($request->filled('province_id')) {
            $select['query'] .= ' and province_id = :province_id';
            $select['params'] = array_merge($select['params'], [
                'province_id' => $request->get('province_id'),
            ]);
        }

        return City::hydrate(DB::select($select['query'], $select['params']));
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city): City
    {
        return $city;
    }
}
