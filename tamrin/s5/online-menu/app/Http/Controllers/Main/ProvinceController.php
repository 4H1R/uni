<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
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
    public function index(Request $request): Collection
    {
        $request->validate([
            'query' => ['nullable', 'string', 'max:255'],
        ]);

        return Province::hydrate(DB::select('select * from provinces where name like :query', ['query' => '%'.$request->get('query').'%']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province): Province
    {
        return $province;
    }
}
