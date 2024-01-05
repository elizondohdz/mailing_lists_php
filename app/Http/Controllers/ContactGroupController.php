<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Group;
use Illuminate\Http\Request;

class ContactGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
    * @OA\Post(
    *     path="/api/contacts_groups",
    *     summary="Add a contact to a group",
    *     tags = {"ContactGroups"},
    *     @OA\RequestBody(
    *     required=true,
    *     @OA\JsonContent(
    *     example={
    *      "contact_id": "5555-aaaa-ssss-7777",
    *      "group_id": "rrrr-6666-kkkk-8888",
    *     },
    *     required={"contact_id", "group_id"},
    *      @OA\Property(
    *          property="contact_id",
    *          type="string",
    *          description="uuid of the contact",
    *      ),
    *      @OA\Property(
    *          property="group_id",
    *          type="string",
    *          description="uuid of the group")
    *      )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Add contact."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="There was an error."
    *     )
    * )
    */
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,uuid',
            'contact_id' => 'required|exists:contacts,uuid'
        ]);

        $group = Group::where('uuid', $request->group_id)->firstOrFail();
        $contact = Contact::where('uuid', $request->contact_id)->firstOrFail();

        $contact_group = ContactGroup::create([
            'group_id' => $group->id,
            'contact_id' => $contact->id
        ]);

        return response(status: 200);
    }

    /**
    * @OA\Get(
    *     path="/api/contacts_groups/{uuid}",
    *     summary="Show contacts from group by group uuid",
    *     tags = {"ContactGroups"},
    *     @OA\Response(
    *         response=200,
    *         description="Show specific contacts in group."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="There was an error."
    *     ),
    *     @OA\Parameter(
    *         name="uuid",
    *         in="path",
    *         description="ID of the group",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    * )
    */
    public function show(string $uuid)
    {
        $group = Group::where('uuid', $uuid)->firstOrFail();
        $contacts = $group->contacts;
        
        return ContactResource::collection($contacts);
    }

    /**
    * @OA\DELETE(
    *     path="/api/contacts_groups/{uuid}",
    *     summary="Delete a Contact from a group",
    *     tags = {"ContactGroups"},
    *     @OA\RequestBody(
    *     required=true,
    *     @OA\JsonContent(
    *     example={
    *      "contact_id": "5555-7777-8888-9999",
    *     },
    *     required={"contact_id"},
    *      @OA\Property(
    *          property="contact_id",
    *          type="string",
    *          description="uuid of the contact to remove"
    *      ),
    *      @OA\Property(
    *          property="email",
    *          type="string",
    *          description="email of the contact")
    *      )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Show contact."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="There was an error."
    *     ),
    *     @OA\Parameter(
    *         name="uuid",
    *         in="path",
    *         description="ID of contact to update",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    * )
    */
    public function destroy(string $uuid, Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:contacts,uuid'
        ]);

        $group = Group::where('uuid', $uuid)->firstOrFail();
        $contact = Contact::where('uuid', $request->contact_id)->firstOrFail();

        $contact_group = ContactGroup::where('group_id', $group->id)
            ->where('contact_id', $contact->id)->firstOrFail();

        $contact_group->delete();
    }
}
