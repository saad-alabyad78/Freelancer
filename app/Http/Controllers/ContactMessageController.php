<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Http\Resources\ContactMessageResource;
use App\Http\Requests\StoreContactMessageRequest;
/**
 * @group ContactMessages
 **/
class ContactMessageController extends Controller
{
    /**
     * Paginate contact messages(admin)
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $messages = ContactMessage::paginate(50) ;

        return ContactMessageResource::collection($messages) ;
    }
    /**
     * Store contact message for any one 
     * 
     * @unauthenticated
     * 
     * @param \App\Http\Requests\StoreContactMessageRequest $request
     * @return ContactMessageResource
     */
    public function store(StoreContactMessageRequest $request)
    {
        $message = ContactMessage::create($request->validated()) ;

        return ContactMessageResource::make($message) ;
    }
    /**
     * Delete message(admin)
     * @param \App\Models\ContactMessage $contactMessage
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(ContactMessage $contactMessage)
    {
        $contactMessage->delete() ;

        return response()->json(['message' => 'deleted']) ;
    }
}