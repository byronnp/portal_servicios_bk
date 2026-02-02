<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Transformers\ApplicationTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $applications = Application::all();
            return $this->responder
                ->success($applications, [ApplicationTransformer::class, 'transform'])
                ->message('Applications retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving applications: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving applications', 500)
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
                'slug' => 'nullable|string|max:255|unique:applications,slug',
                'description' => 'nullable|string',
                'is_web' => 'boolean',
                'is_mobile' => 'boolean',
                'start_url' => 'nullable|url',
                'icon' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $application = Application::create($request->all());

            return $this->responder
                ->success($application, [ApplicationTransformer::class, 'transform'])
                ->message('Application created successfully')
                ->statusCode(201)
                ->respond();
        } catch (Exception $e) {
            Log::error('Error creating application: ' . $e->getMessage());
            return $this->responder
                ->error('Error creating application', 500)
                ->respond();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $application = Application::find($id);

            if (!$application) {
                return $this->responder
                    ->error('Application not found', 404)
                    ->respond();
            }

            return $this->responder
                ->success($application, [ApplicationTransformer::class, 'transform'])
                ->message('Application retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving application: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving application', 500)
                ->respond();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $application = Application::find($id);

            if (!$application) {
                return $this->responder
                    ->error('Application not found', 404)
                    ->respond();
            }

            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'slug' => 'string|max:255|unique:applications,slug,' . $id,
                'description' => 'nullable|string',
                'is_web' => 'boolean',
                'is_mobile' => 'boolean',
                'start_url' => 'nullable|url',
                'icon' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $application->update($request->all());

            return $this->responder
                ->success($application, [ApplicationTransformer::class, 'transform'])
                ->message('Application updated successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error updating application: ' . $e->getMessage());
            return $this->responder
                ->error('Error updating application', 500)
                ->respond();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $application = Application::find($id);

            if (!$application) {
                return $this->responder
                    ->error('Application not found', 404)
                    ->respond();
            }

            $application->delete();

            return $this->responder
                ->success(null)
                ->message('Application deleted successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error deleting application: ' . $e->getMessage());
            return $this->responder
                ->error('Error deleting application', 500)
                ->respond();
        }
    }

    /**
     * Get active applications.
     */
    public function active()
    {
        try {
            $applications = Application::active()->get();
            return $this->responder
                ->success($applications, [ApplicationTransformer::class, 'transform'])
                ->message('Active applications retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving active applications: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving active applications', 500)
                ->respond();
        }
    }

    /**
     * Get web applications.
     */
    public function web()
    {
        try {
            $applications = Application::web()->get();
            return $this->responder
                ->success($applications, [ApplicationTransformer::class, 'transform'])
                ->message('Web applications retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving web applications: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving web applications', 500)
                ->respond();
        }
    }

    /**
     * Get mobile applications.
     */
    public function mobile()
    {
        try {
            $applications = Application::mobile()->get();
            return $this->responder
                ->success($applications, [ApplicationTransformer::class, 'transform'])
                ->message('Mobile applications retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving mobile applications: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving mobile applications', 500)
                ->respond();
        }
    }
}
