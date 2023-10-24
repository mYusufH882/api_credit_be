<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\CreditResource;
use App\Models\Credit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class CreditController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $credit = Credit::all();

        return $this->sendResponse(CreditResource::collection($credit), 'List of Credit data.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'credit_type' => 'required',
            'name' => 'required',
            'total_transaction' => 'required',
            'tenor' => 'required',
            'total_credit' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $credit = Credit::create([
            'user_id' => Auth::user()->id,
            'credit_type' => $input['credit_type'],
            'name' => $input['name'],
            'total_transaction' => $input['total_transaction'],
            'tenor' => $input['tenor'],
            'total_credit' => $input['total_credit'],
            'status' => 'WAITING'
        ]);

        return $this->sendResponse($credit, 'Credit has been added!!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $credit = Credit::find($id);

        if (is_null($credit)) {
            return $this->sendError('Credit not available!!', []);
        }

        return $this->sendResponse(new CreditResource($credit), 'Detail Credit.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'credit_type' => 'required',
            'name' => 'required',
            'total_transaction' => 'required',
            'tenor' => 'required',
            'total_credit' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $credit = Credit::where('id', $id)->update([
            'credit_type' => $input['credit_type'],
            'name' => $input['name'],
            'total_transaction' => $input['total_transaction'],
            'tenor' => $input['tenor'],
            'total_credit' => $input['total_credit'],
            'status' => $request->status,
        ]);

        return $this->sendResponse('Success', 'Credit has been updated!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
