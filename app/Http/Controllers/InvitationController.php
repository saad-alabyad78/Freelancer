<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendInvitationRequest;
use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function sendInvitation(SendInvitationRequest $request)
    {
        $this->authorize('sendInvitation', Invitation::class);

        $invitation = Invitation::create([
            'company_id' => auth()->user()->id,
            'freelancer_id' => $request->freelancer_id,
            'job_offer_id' => $request->job_offer_id,
        ]);

        // send a notification to freelancer

        return response()->json($invitation, 201);
    }

    public function getInvitations()
    {
        $user = auth()->user();

        if ($user->role === 'company') {
            $invitations = $user->sentInvitations;
        } elseif ($user->role === 'freelancer') {
            $invitations = $user->receivedInvitations;
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($invitations);
    }
    public function deleteInvitation($id)
    {
        $invitation = Invitation::findOrFail($id);
        $this->authorize('deleteInvitation', $invitation);

        $invitation->delete();
        return response()->json(['message' => 'Invitation deleted successfully']);
    }

    public function acceptInvitation($id)
    {
        $invitation = Invitation::findOrFail($id);
        $this->authorize('respondToInvitation', $invitation);

        $invitation->status = InvitationStatus::ACCEPTED;
        $invitation->save();

        $conversation = Conversation::create();
        $conversation->participants()->attach([$invitation->company_id, $invitation->freelancer_id]);

        return response()->json(['message' => 'Invitation accepted', 'conversation' => $conversation]);
    }

    public function rejectInvitation($id)
    {
        $invitation = Invitation::findOrFail($id);
        $this->authorize('respondToInvitation', $invitation);

        $invitation->status = InvitationStatus::REJECTED;
        $invitation->save();

        return response()->json(['message' => 'Invitation rejected']);
    }
}

