<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\invitation\SendInvitationRequest;
use App\Http\Requests\invitation\deleteInvitationRequest;

/**
 * @group Invitation
 */
class InvitationController extends Controller
{
    /**
     * Send an invitation.
     *
     * Sends an invitation from a company to a freelancer.
     *
     * @param  \App\Http\Requests\invitation\SendInvitationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function sendInvitation(SendInvitationRequest $request)
    {
        $this->authorize('sendInvitation', Invitation::class);

        $invitation = Invitation::firstOrCreate([
            'company_id' => auth('sanctum')->user()->id,
            'freelancer_id' => $request->freelancer_id,
        ]);

        // send a notification to freelancer

        return response()->json($invitation, 201);
    }

    /**
     * Get invitations.
     *
     * Retrieves all invitations for the authenticated user (company or freelancer).
     *
     * @return \Illuminate\Http\Response
     */
    public function getInvitations()
    {
        $user = auth('sanctum')->user();

        if ($user->role_name === 'company') {
            $invitations = $user->sentInvitations;
        } elseif ($user->role_name === 'freelancer') {
            $invitations = $user->receivedInvitations;
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($invitations->isEmpty()) {
            return response()->json(['message' => 'No invitations found']);
        }

        return response()->json($invitations);
    }

    /**
     * Delete an invitation.
     *
     * Deletes a specific invitation.
     *
     * @param  \App\Http\Requests\invitation\deleteInvitationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInvitation(deleteInvitationRequest $request)
    {
        foreach ($request->invitation_ids as $invitation_id) {
            $invitation = Invitation::findOrFail($invitation_id);
            $this->authorize('deleteInvitation', $invitation);
            $invitation->delete();
        }

        return response()->json(['message' => 'Invitations deleted successfully']);
    }

    /**
     * Accept an invitation.
     *
     * Accepts a specific invitation by the freelancer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        public function acceptInvitation($id)
    {
        $invitation = Invitation::findOrFail($id);
        $this->authorize('respondToInvitation', $invitation);

        if ($invitation->accepted_at) {
            return response()->json(['error' => 'Invitation has already been accepted.']);
        }

        if ($invitation->rejected_at) {
            return response()->json(['error' => 'Invitation has been rejected and cannot be accepted.']);
        }

        $invitation->accepted_at = now();
        $invitation->save();

        $conversation = Conversation::firstOrCreate();
        $conversation->participants()->attach([$invitation->company_id, $invitation->freelancer_id]);

        return response()->json(['message' => 'Invitation accepted', 'conversation' => $conversation]);
    }

    /**
     * Reject an invitation.
     *
     * Rejects a specific invitation by the freelancer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rejectInvitation($id)
    {
        $invitation = Invitation::findOrFail($id);
        $this->authorize('respondToInvitation', $invitation);

        if ($invitation->rejected_at) {
            return response()->json(['error' => 'Invitation has already been rejected.']);
        }

        if ($invitation->accepted_at) {
            return response()->json(['error' => 'Invitation has been accepted and cannot be rejected.']);
        }

        $invitation->rejected_at = now();
        $invitation->save();

        return response()->json(['message' => 'Invitation rejected']);
    }
}
