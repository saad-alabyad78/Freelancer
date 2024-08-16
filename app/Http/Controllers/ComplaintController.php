<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComplaintRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @group Complaint Management
 *
 * APIs to manage complaints.
 */
class ComplaintController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Complaint::class, 'complaint');
    }

    /**
     * Submit a complaint.
     *
     * @bodyParam accused_id integer required The ID of the accused user.
     * @bodyParam reason string required The reason for the complaint.
     * @bodyParam type string The type of the complaint (optional).
     */
    public function store(StoreComplaintRequest $request)
    {
        $complaint = Complaint::create([
            'complainant_id' => Auth::id(),
            'accused_id' => $request->accused_id,
            'reason' => $request->reason,
            'type' => $request->type,
        ]);

        return response()->json($complaint, 201);
    }

    /**
     * Get all complaints.
     */
    public function index()
    {
        $this->authorize('viewAny', Complaint::class);

        $complaints = Complaint::with(['complainant', 'accused'])->get();
        return response()->json($complaints);
    }

    /**
     * Freeze a user.
     *
     * @urlParam id integer required The ID of the user to be frozen.
     */
    public function freezeUser($id)
    {
        $this->authorize('freezeUser', Auth::user());

        $user = User::findOrFail($id);
        $user->delete(); // Soft Delete
        return response()->json(['message' => 'User has been frozen.']);
    }
}
