<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendInvitationRequest;
use App\Http\Requests\AcceptInvitationRequest;
use App\Http\Requests\RejectInvitationRequest;
use App\Models\Conversation;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Constants\InvitationStatus;

class InvitationController extends Controller
{
    /**
     * Send an invitation
     *
     * Sends an invitation from a company to a freelancer for a job offer.
     *
     * @param  \App\Http\Requests\SendInvitationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function sendInvitation(SendInvitationRequest $request)
    {
        $this->authorize('sendInvitation', Invitation::class);

        $invitation = Invitation::create([
            'company_id' => auth()->user()->id,
            'freelancer_id' => $request->freelancer_id,
            'job_offer_id' => $request->job_offer_id,
            'status' => InvitationStatus::PENDING,
        ]);

        // send a notification to freelancer

        return response()->json($invitation, 201);
    }

    /**
     * Get invitations
     *
     * Retrieves all invitations for the authenticated user (company or freelancer).
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Delete an invitation
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
     * Accept an invitation
     *
     * Accepts a specific invitation by the freelancer.
     *
     * @param  \App\Http\Requests\AcceptInvitationRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function acceptInvitation(AcceptInvitationRequest $request, $id)
    {
        $invitation = Invitation::findOrFail($id);
        $this->authorize('respondToInvitation', $invitation);

        $invitation->status = InvitationStatus::ACCEPTED;
        $invitation->accepted_at = now();
        $invitation->save();

        $conversation = Conversation::create();
        $conversation->participants()->attach([$invitation->company_id, $invitation->freelancer_id]);

        return response()->json(['message' => 'Invitation accepted', 'conversation' => $conversation]);
    }

    /**
     * Reject an invitation
     *
     * Rejects a specific invitation by the freelancer.
     *
     * @param  \App\Http\Requests\RejectInvitationRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rejectInvitation(RejectInvitationRequest $request, $id)
    {
        $invitation = Invitation::findOrFail($id);
        $this->authorize('respondToInvitation', $invitation);

        $invitation->status = InvitationStatus::REJECTED;
        $invitation->rejected_at = now();
        $invitation->save();

        return response()->json(['message' => 'Invitation rejected']);
    }
}
