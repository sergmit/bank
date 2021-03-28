<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    /**
     * @var CurrencyService
     */
    private $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'currency' => 'required|string',
            'buy' => 'required|numeric',
            'sell' => 'required|numeric',
            'begins_at' => 'required|date_format:d.m.Y H:i:s',
            'office_id' => 'string|nullable'
        ]);

        if ($validator->fails() === true) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $currency = $this->currencyService->create($data);

        return response()->json(['success' => true, 'currency' => $currency], Response::HTTP_ACCEPTED);
    }

    public function getCurrency(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'office_id' => 'required|string',
            'at_date' => 'required|date_format:d.m.Y H:i:s'
        ]);

        if ($validator->fails() === true) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $result = $this->currencyService->getCurrency($data);

        if (empty($result)) {
            return response()->json(['success' => false], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['success' => true, 'currency' => $result]);
    }
}
