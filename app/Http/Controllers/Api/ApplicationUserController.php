<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApplicationUser;
use App\Transformers\ApplicationUserTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApplicationUserController extends Controller
{
    public function index()
    {
        try {
            $assignments = ApplicationUser::with(['user.profile', 'application', 'assignedBy.profile'])->get();

            return $this->responder
                ->success($assignments, [ApplicationUserTransformer::class, 'transform'])
                ->message('Application user assignments retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving application user assignments: '.$e->getMessage());
            return $this->responder->error('Error retrieving application user assignments', 500)->respond();
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'application_id' => 'required|exists:applications,id',
                'assigned_at' => 'nullable|date',
                'assigned_by' => 'nullable|exists:users,id',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder->error($validator->errors()->first(), 422)->respond();
            }

            $exists = ApplicationUser::where('user_id', $request->user_id)
                ->where('application_id', $request->application_id)
                ->exists();

            if ($exists) {
                return $this->responder->error('Application is already assigned to this user', 422)->respond();
            }

            $assignment = ApplicationUser::create([
                'user_id' => $request->user_id,
                'application_id' => $request->application_id,
                'assigned_at' => $request->assigned_at ?? now(),
                'assigned_by' => $request->assigned_by,
                'is_active' => $request->input('is_active', true),
            ]);
            $assignment->load(['user.profile', 'application', 'assignedBy.profile']);

            return $this->responder
                ->success($assignment, [ApplicationUserTransformer::class, 'transform'])
                ->message('Application user assignment created successfully')
                ->statusCode(201)
                ->respond();
        } catch (Exception $e) {
            Log::error('Error creating application user assignment: '.$e->getMessage());
            return $this->responder->error('Error creating application user assignment', 500)->respond();
        }
    }

    public function show(string $id)
    {
        try {
            $assignment = ApplicationUser::with(['user.profile', 'application', 'assignedBy.profile'])->find($id);

            if (!$assignment) {
                return $this->responder->error('Application user assignment not found', 404)->respond();
            }

            return $this->responder
                ->success($assignment, [ApplicationUserTransformer::class, 'transform'])
                ->message('Application user assignment retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving application user assignment: '.$e->getMessage());
            return $this->responder->error('Error retrieving application user assignment', 500)->respond();
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $assignment = ApplicationUser::find($id);

            if (!$assignment) {
                return $this->responder->error('Application user assignment not found', 404)->respond();
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'exists:users,id',
                'application_id' => 'exists:applications,id',
                'assigned_at' => 'nullable|date',
                'assigned_by' => 'nullable|exists:users,id',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder->error($validator->errors()->first(), 422)->respond();
            }

            $userId = $request->input('user_id', $assignment->user_id);
            $applicationId = $request->input('application_id', $assignment->application_id);
            $exists = ApplicationUser::where('user_id', $userId)
                ->where('application_id', $applicationId)
                ->where('id', '!=', $assignment->id)
                ->exists();

            if ($exists) {
                return $this->responder->error('Application is already assigned to this user', 422)->respond();
            }

            $assignment->update($request->only([
                'user_id',
                'application_id',
                'assigned_at',
                'assigned_by',
                'is_active',
            ]));
            $assignment->load(['user.profile', 'application', 'assignedBy.profile']);

            return $this->responder
                ->success($assignment, [ApplicationUserTransformer::class, 'transform'])
                ->message('Application user assignment updated successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error updating application user assignment: '.$e->getMessage());
            return $this->responder->error('Error updating application user assignment', 500)->respond();
        }
    }

    public function destroy(string $id)
    {
        try {
            $assignment = ApplicationUser::find($id);

            if (!$assignment) {
                return $this->responder->error('Application user assignment not found', 404)->respond();
            }

            $assignment->delete();

            return $this->responder
                ->success(null)
                ->message('Application user assignment deleted successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error deleting application user assignment: '.$e->getMessage());
            return $this->responder->error('Error deleting application user assignment', 500)->respond();
        }
    }
}
