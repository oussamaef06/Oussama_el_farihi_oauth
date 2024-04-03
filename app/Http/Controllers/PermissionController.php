<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *   schema="Permission",
 *   type="object",
 *   required={"name"},
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     format="int64",
 *     description="Permission ID"
 *   ),
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     description="Permission's name"
 *   )
 * )
 */

class PermissionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/permissions",
     *     summary="List all permissions",
     *     tags={"Permissions"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="A list of permissions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Permission")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    /**
     * @OA\Post(
     *     path="/api/permissions",
     *     summary="Create a new permission",
     *     tags={"Permissions"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Permission created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Permission")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $permission = Permission::create($request->only('name'));

        return response()->json($permission, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/permissions/{permission}",
     *     summary="Update an existing permission",
     *     tags={"Permissions"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="permission",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Permission")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function update(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $permission->update($request->only('name'));

        return response()->json($permission);
    }

    /**
     * @OA\Delete(
     *     path="/api/permissions/{permission}",
     *     summary="Delete a permission",
     *     tags={"Permissions"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="permission",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
