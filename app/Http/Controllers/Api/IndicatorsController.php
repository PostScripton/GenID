<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Indicator;
use App\Services\IndicatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndicatorsController extends Controller
{
    private $indicator;

    public function __construct(IndicatorService $indicatorService)
    {
        $this->indicator = $indicatorService;
    }

    public function show($id)
    {
        $indicator = Indicator::find($id);

        if (!$indicator) {
            return response()->json([
                'errors' => ['id' => "ID [{$id}] not found"]
            ], 404); // Not Found
        }

        return response()->json($indicator);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'length' => 'numeric|min:8'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 400); // Bad Request
        }

        $validated = $validator->validated();
        $code = $this->indicator->randomize($validated['type'], $validated['length']);

        if (!$code) {
            return response(['errors' => ['type' => 'The type has wrong value. ' .
                'Following ones are provided: string, number, alphanumeric, guid.']], 400); // Bad Request
        }

        Indicator::create(['code' => $code]);
        return response(['message' => 'Created'], 201); // Created
    }
}