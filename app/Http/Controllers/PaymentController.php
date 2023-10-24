<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class PaymentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $payment = Payment::all();

        return $this->sendResponse(PaymentResource::collection($payment), 'List of payment data...');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'credit_id' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $payment = Payment::create([
            'credit_id' => $input['credit_id'],
            'amount' => $input['amount'],
            'status' => "PROCESSED"
        ]);

        return $this->sendResponse('Success', 'Payment has been added!!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $payment = Payment::find($id);

        if (is_null($payment)) {
            return $this->sendError('Payment not available!!', []);
        }

        return $this->sendResponse(new PaymentResource($payment), 'Detail Payment.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'credit_id' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $payment = Payment::where('id', $id)->update([
            'credit_id' => $input['credit_id'],
            'amount' => $input['amount'],
            'status' => $request->status,
        ]);

        return $this->sendResponse($payment, 'Payment has been updated!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
