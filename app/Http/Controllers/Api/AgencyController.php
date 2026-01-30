<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Transformers\AgencyTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class AgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $agencies = Agency::with('company.instance')->get();
            return $this->responder
                ->success($agencies, [AgencyTransformer::class, 'transform'])
                ->message('Agencies retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving agencies: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving agencies', 500)
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
                'company_id' => 'required|exists:companies,id',
                'crm_agency_id' => 'nullable|string',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                's3s_id' => 'nullable|string',
                'status' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $agency = Agency::create($request->all());
            $agency->load('company.instance');

            return $this->responder
                ->success($agency, [AgencyTransformer::class, 'transform'])
                ->message('Agency created successfully')
                ->statusCode(201)
                ->respond();
        } catch (Exception $e) {
            Log::error('Error creating agency: ' . $e->getMessage());
            return $this->responder
                ->error('Error creating agency', 500)
                ->respond();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $agency = Agency::with('company.instance')->find($id);

            if (!$agency) {
                return $this->responder
                    ->error('Agency not found', 404)
                    ->respond();
            }

            return $this->responder
                ->success($agency, [AgencyTransformer::class, 'transform'])
                ->message('Agency retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving agency: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving agency', 500)
                ->respond();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $agency = Agency::find($id);

            if (!$agency) {
                return $this->responder
                    ->error('Agency not found', 404)
                    ->respond();
            }

            $validator = Validator::make($request->all(), [
                'company_id' => 'exists:companies,id',
                'crm_agency_id' => 'nullable|string',
                'name' => 'string|max:255',
                'description' => 'nullable|string',
                's3s_id' => 'nullable|string',
                'status' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $agency->update($request->all());
            $agency->load('company.instance');

            return $this->responder
                ->success($agency, [AgencyTransformer::class, 'transform'])
                ->message('Agency updated successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error updating agency: ' . $e->getMessage());
            return $this->responder
                ->error('Error updating agency', 500)
                ->respond();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $agency = Agency::find($id);

            if (!$agency) {
                return $this->responder
                    ->error('Agency not found', 404)
                    ->respond();
            }

            $agency->delete();

            return $this->responder
                ->success(null)
                ->message('Agency deleted successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error deleting agency: ' . $e->getMessage());
            return $this->responder
                ->error('Error deleting agency', 500)
                ->respond();
        }
    }

    /**
     * Get agencies by company.
     */
    public function byCompany(string $companyId)
    {
        try {
            $agencies = Agency::with('company.instance')
                ->where('company_id', $companyId)
                ->get();

            return $this->responder
                ->success($agencies, [AgencyTransformer::class, 'transform'])
                ->message('Agencies retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving agencies by company: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving agencies', 500)
                ->respond();
        }
    }
}
