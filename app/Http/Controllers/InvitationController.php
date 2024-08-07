<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\invitation\SendInvitationRequest;
use App\Http\Requests\invitation\AcceptInvitationRequest;
use App\Http\Requests\invitation\RejectInvitationRequest;

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

        $invitation = Invitation::create([
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

        if ($user->role === 'company') {
            $invitations = $user->sentInvitations;
        } elseif ($user->role === 'freelancer') {
            $invitations = $user->receivedInvitations;
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($invitations);
    }

    /**
     * Delete an invitation.
     *
     * Deletes a specific invitation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteInvitation($id)
    {
        $invitation = Invitation::findOrFail($id);
        $this->authorize('deleteInvitation', $invitation);

        $invitation->delete();
        return response()->json(['message' => 'Invitation deleted successfully']);
    }

    /**
     * Accept an invitation.
     *
     * Accepts a specific invitation by the freelancer.
     *
     * @param  \App\Http\Requests\Invitation\AcceptInvitationRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function acceptInvitation(AcceptInvitationRequest $request, $id)
    {
        $invitation = Invitation::findOrFail($id);
        $this->authorize('respondToInvitation', $invitation);

        $invitation->accepted_at = now();
        $invitation->save();

        // Create a conversation between the company and the freelancer
        $conversation = Conversation::create();
        $conversation->participants()->attach([$invitation->company_id, $invitation->freelancer_id]);

        return response()->json(['message' => 'Invitation accepted', 'conversation' => $conversation]);
    }

    /**
     * Reject an invitation.
     *
     * Rejects a specific invitation by the freelancer.
     *
     * @param  \App\Http\Requests\invitation\RejectInvitationRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rejectInvitation(RejectInvitationRequest $request, $id)
    {
        $invitation = Invitation::findOrFail($id);
        $this->authorize('respondToInvitation', $invitation);

        $invitation->rejected_at = now();
        $invitation->save();

        return response()->json(['message' => 'Invitation rejected']);
    }
}
