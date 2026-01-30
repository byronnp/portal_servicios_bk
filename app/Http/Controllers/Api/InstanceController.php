<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Instance;
use App\Transformers\InstanceTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class InstanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $instances = Instance::with('companies')->get();
            return $this->responder
                ->success($instances, [InstanceTransformer::class, 'transform'])
                ->message('Instances retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving instances: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving instances', 500)
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
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'url' => 'required|string|unique:instances',
                'crm_token' => 'nullable|string',
                'can_send_to_crm' => 'boolean',
                'status' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $instance = Instance::create($request->all());

            return $this->responder
                ->success($instance, [InstanceTransformer::class, 'transform'])
                ->message('Instance created successfully')
                ->statusCode(201)
                ->respond();
        } catch (Exception $e) {
            Log::error('Error creating instance: ' . $e->getMessage());
            return $this->responder
                ->error('Error creating instance', 500)
                ->respond();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $instance = Instance::with('companies.agencies')->find($id);

            if (!$instance) {
                return $this->responder
                    ->error('Instance not found', 404)
                    ->respond();
            }

            return $this->responder
                ->success($instance, [InstanceTransformer::class, 'transform'])
                ->message('Instance retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving instance: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving instance', 500)
                ->respond();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $instance = Instance::find($id);

            if (!$instance) {
                return $this->responder
                    ->error('Instance not found', 404)
                    ->respond();
            }

            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'description' => 'nullable|string',
                'url' => 'string|unique:instances,url,' . $id,
                'crm_token' => 'nullable|string',
                'can_send_to_crm' => 'boolean',
                'status' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $instance->update($request->all());

            return $this->responder
                ->success($instance, [InstanceTransformer::class, 'transform'])
                ->message('Instance updated successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error updating instance: ' . $e->getMessage());
            return $this->responder
                ->error('Error updating instance', 500)
                ->respond();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $instance = Instance::find($id);

            if (!$instance) {
                return $this->responder
                    ->error('Instance not found', 404)
                    ->respond();
            }

            $instance->delete();

            return $this->responder
                ->success(null)
                ->message('Instance deleted successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error deleting instance: ' . $e->getMessage());
            return $this->responder
                ->error('Error deleting instance', 500)
                ->respond();
        }
    }
}
