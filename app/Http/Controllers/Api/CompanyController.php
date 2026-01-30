<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Transformers\CompanyTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $companies = Company::with(['instance', 'agencies'])->get();
            return $this->responder
                ->success($companies, [CompanyTransformer::class, 'transform'])
                ->message('Companies retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving companies: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving companies', 500)
                ->respond();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'instance_id' => 'required|exists:instances,id',
                'crm_company_id' => 'nullable|string',
                's3s_id' => 'nullable|string',
                'ruc' => 'required|string|unique:companies',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $company = Company::create($request->all());
            $company->load('instance');

            return $this->responder
                ->success($company, [CompanyTransformer::class, 'transform'])
                ->message('Company created successfully')
                ->statusCode(201)
                ->respond();
        } catch (Exception $e) {
            Log::error('Error creating company: ' . $e->getMessage());
            return $this->responder
                ->error('Error creating company', 500)
                ->respond();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $company = Company::with(['instance', 'agencies'])->find($id);

            if (!$company) {
                return $this->responder
                    ->error('Company not found', 404)
                    ->respond();
            }

            return $this->responder
                ->success($company, [CompanyTransformer::class, 'transform'])
                ->message('Company retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving company: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving company', 500)
                ->respond();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $company = Company::find($id);

            if (!$company) {
                return $this->responder
                    ->error('Company not found', 404)
                    ->respond();
            }

            $validator = Validator::make($request->all(), [
                'instance_id' => 'exists:instances,id',
                'crm_company_id' => 'nullable|string',
                's3s_id' => 'nullable|string',
                'ruc' => 'string|unique:companies,ruc,' . $id,
                'name' => 'string|max:255',
                'description' => 'nullable|string',
                'status' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $company->update($request->all());
            $company->load('instance');

            return $this->responder
                ->success($company, [CompanyTransformer::class, 'transform'])
                ->message('Company updated successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error updating company: ' . $e->getMessage());
            return $this->responder
                ->error('Error updating company', 500)
                ->respond();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $company = Company::find($id);

            if (!$company) {
                return $this->responder
                    ->error('Company not found', 404)
                    ->respond();
            }

            $company->delete();

            return $this->responder
                ->success(null)
                ->message('Company deleted successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error deleting company: ' . $e->getMessage());
            return $this->responder
                ->error('Error deleting company', 500)
                ->respond();
        }
    }

    /**
     * Get companies by instance.
     */
    public function byInstance(string $instanceId)
    {
        try {
            $companies = Company::with('agencies')
                ->where('instance_id', $instanceId)
                ->get();

            return $this->responder
                ->success($companies, [CompanyTransformer::class, 'transform'])
                ->message('Companies retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving companies by instance: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving companies', 500)
                ->respond();
        }
    }
}
