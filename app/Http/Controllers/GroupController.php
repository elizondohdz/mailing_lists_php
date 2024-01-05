<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class GroupController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/groups",
    *     summary="Show all groups",
    *     tags = {"Groups"},
    *     @OA\Response(
    *         response=200,
    *         description="Show all groups."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="There was an error."
    *     )
    * )
    */
    public function index()
    {
        return GroupResource::collection(Group::all());
    }

    /**
    * @OA\Post(
    *     path="/api/groups",
    *     summary="Create a new Group",
    *     tags = {"Groups"},
    *     @OA\RequestBody(
    *     required=true,
    *     @OA\JsonContent(
    *     example={
    *      "name": "New group",
    *      "description": "Lorem ipsum dolor sit amet",
    *     },
    *     required={"name", "description"},
    *      @OA\Property(
    *          property="name",
    *          type="string",
    *          description="name of the group",
    *          example="New group"
    *      ),
    *      @OA\Property(
    *          property="description",
    *          type="string",
    *          description="description of the group")
    *      )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Show Group."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="There was an error."
    *     )
    * )
    */
    public function store(Request $request)
    {
        $group = Group::create([
            ... $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'required|string|max:255'        
            ]),
            'uuid' => Str::uuid()
        ]);

        return new GroupResource($group);
    }

    /**
    * @OA\Get(
    *     path="/api/groups/{uuid}",
    *     summary="Show specific group by uuid",
    *     tags = {"Groups"},
    *     @OA\Response(
    *         response=200,
    *         description="Show specific group."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="There was an error."
    *     ),
    *     @OA\Parameter(
    *         name="uuid",
    *         in="path",
    *         description="ID of group to return",
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

        return new GroupResource($group);
    }

    /**
    * @OA\PUT(
    *     path="/api/groups/{uuid}",
    *     summary="Update a group",
    *     tags = {"Groups"},
    *     @OA\RequestBody(
    *     required=true,
    *     @OA\JsonContent(
    *     example={
    *      "name": "the chill group",
    *      "description": "this is a group to chill",
    *     },
    *     required={"name", "description"},
    *      @OA\Property(
    *          property="name",
    *          type="string",
    *          description="name of the group",
    *          example="Jhon Doe"
    *      ),
    *      @OA\Property(
    *          property="description",
    *          type="string",
    *          description="description of the group")
    *      )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Show group."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="There was an error."
    *     ),
    *     @OA\Parameter(
    *         name="uuid",
    *         in="path",
    *         description="ID of group to update",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    * )
    */
    public function update(Request $request, string $uuid)
    {
        $group = Group::where('uuid', $uuid)->firstOrFail();

        $group->update([
            ... $request->validate([
                'name' => 'sometimes|string|max:100',
                'description' => 'required|string|max:255'
            ])
        ]);

        return new GroupResource($group);
    }

    /**
    * @OA\DELETE(
    *     path="/api/groups/{uuid}",
    *     summary="Delete specific group by uuid",
    *     tags = {"Groups"},
    *     @OA\Response(
    *         response=200,
    *         description="Delete specific group."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="There was an error."
    *     ),
    *     @OA\Parameter(
    *         name="uuid",
    *         in="path",
    *         description="ID of group to delete",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    * )
    */
    public function destroy(string $uuid)
    {
        $group = Group::where('uuid', $uuid)->firstOrFail();
        $group->delete();
        
        return response(status: 204);
    }
}
