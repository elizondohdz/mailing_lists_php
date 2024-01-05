<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/contacts",
    *     summary="Show all contacts",
    *     tags = {"Contacts"},
    *     @OA\Response(
    *         response=200,
    *         description="Show all contacts."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="There was an error."
    *     )
    * )
    */
    public function index()
    {
        Return ContactResource::collection(Contact::all());
    }

    /**
    * @OA\Post(
    *     path="/api/contacts",
    *     summary="Create a new Contact",
    *     tags = {"Contacts"},
    *     @OA\RequestBody(
    *     required=true,
    *     @OA\JsonContent(
    *     example={
    *      "fullname": "Jhon Doe",
    *      "email": "jhon@example.com",
    *      "phone": "555-555555",
    *     },
    *     required={"fullname", "email"},
    *      @OA\Property(
    *          property="name",
    *          type="string",
    *          description="name of the contact",
    *          example="Jhon Doe"
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
    *     )
    * )
    */
    public function store(Request $request)
    {        
        $contact = Contact::create([             
            ... $request->validate([
                'fullname' => 'required|string|max:255',
                'phone' => 'required|string|max:50',
                'email' => 'required|email|unique:contacts,email'
            ]),
            'uuid' => Str::uuid()->toString()        
        ]);

        return new ContactResource($contact);
    }
    
    /**
    * @OA\Get(
    *     path="/api/contacts/{uuid}",
    *     summary="Show specific contact by uuid",
    *     tags = {"Contacts"},
    *     @OA\Response(
    *         response=200,
    *         description="Show specific contact."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="There was an error."
    *     ),
    *     @OA\Parameter(
    *         name="uuid",
    *         in="path",
    *         description="ID of contact to return",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    * )
    */
    public function show(string $uuid)
    {
        $contact = Contact::where('uuid', $uuid)->FirstOrFail();
        return new ContactResource($contact);        
    }

    /**
    * @OA\PUT(
    *     path="/api/contacts/{uuid}",
    *     summary="Update a Contact",
    *     tags = {"Contacts"},
    *     @OA\RequestBody(
    *     required=true,
    *     @OA\JsonContent(
    *     example={
    *      "fullname": "Jhon Saveri",
    *      "email": "jhon@example.com",
    *      "phone": "555-555555",
    *     },
    *     required={"fullname", "email"},
    *      @OA\Property(
    *          property="name",
    *          type="string",
    *          description="name of the contact",
    *          example="Jhon Doe"
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
    public function update(Request $request, string $uuid)
    {
        $contact = Contact::where('uuid', $uuid)->firstOrFail();

        $contact->update([
            ... $request->validate([
                'fullname' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|max:50'
            ])
        ]);

        return new ContactResource($contact);
    }

    /**
    * @OA\DELETE(
    *     path="/api/contacts/{uuid}",
    *     summary="Delete specific contact by uuid",
    *     tags = {"Contacts"},
    *     @OA\Response(
    *         response=200,
    *         description="Delete specific contact."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="There was an error."
    *     ),
    *     @OA\Parameter(
    *         name="uuid",
    *         in="path",
    *         description="ID of contact to delete",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    * )
    */
    public function destroy(string $uuid)
    {
        $contact = Contact::where('uuid', $uuid)->firstOrFail();
        $contact->delete();

        return response(status: 204);
    }
}
