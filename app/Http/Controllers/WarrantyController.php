<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarrantyCollection;
use App\Models\Warranty;
use Illuminate\Http\Request;

class WarrantyController extends BaseController
{
    public function index(Request $request)
    {
        $filters = $request->query('filter', []);

        $warranties = Warranty::query()
            ->when(isset($filters['name']), fn($query) => $query->where('name', 'like', '%' . $filters['name'] . '%'))
            ->when(isset($filters['price']), fn($query) => $query->where('price', '<=', $filters['price']))
            ->get();

        $success = new WarrantyCollection($warranties);
        return $this->sendResponse($success, 'Garanties récupérées avec succès.');
    }
}
