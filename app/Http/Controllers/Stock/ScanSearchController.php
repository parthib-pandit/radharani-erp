<?php
namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Support\ScanSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** The search box inside the camera scanner. Any signed-in staff member can use the scanner, so this is not behind stock.manage. */
class ScanSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(['results' => ScanSearch::run((string) $request->query('q', ''))]);
    }
}
