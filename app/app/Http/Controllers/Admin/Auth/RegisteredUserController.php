<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RegisteredUserController extends Controller
{
    #[OA\Post(
        path: "/api/v1/admin/create",
        summary: "Create an Admin Account",
        requestBody: new OA\RequestBody(required: true,
                content: new OA\MediaType(mediaType: "application/x-www-form-urlencoded",
                schema: new OA\Schema(required: ["first_name", "last_name", "email", "password", "password_confirmation", "address", "phone_number", "avatar"],
                        properties: [
                            new OA\Property(property: 'first_name', description: "User first name", type: "string"),
                            new OA\Property(property: 'last_name', description: "User last name", type: "string"),
                            new OA\Property(property: 'email', description: "User email", type: "string"),
                            new OA\Property(property: 'password', description: "User password", type: "string"),
                            new OA\Property(property: 'password_confirmation', description: "User password confirmation", type: "string"),
                            new OA\Property(property: 'address', description: "User address", type: "string"),
                            new OA\Property(property: 'phone_number', description: "User phone number", type: "string"),
                            new OA\Property(property: 'avatar', description: "User profile picture UUID", type: "string"),
                            new OA\Property(property: 'marketing', description: "marketing preferences", type: "string")
                    ]
                ))),
        tags: ["Admin"],
        responses: [
            new OA\Response(response: ResponseAlias::HTTP_CREATED, description: "Register Successfully"),
            new OA\Response(response: ResponseAlias::HTTP_UNPROCESSABLE_ENTITY, description: "Unprocessable entity"),
            new OA\Response(response: ResponseAlias::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: ResponseAlias::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function store(RegisterRequest $request): Response
    {
        $formFields = $request->validated();

        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'first_name' => $formFields['first_name'],
            'last_name' => $formFields['last_name'],
            'is_admin' => 1,
            'email' => $formFields['email'],
            'password' => Hash::make($formFields['password']),
            'address' => $formFields['address'],
            'phone_number' => $formFields['phone_number'],
            'avatar' => $formFields['avatar'],
            'is_marketing' => $request->has('marketing') ? 1 : 0
        ]);

        return response([
            'status' => 'success',
            'message' => 'admin account created successfully!',
            'data' => [
               'user' => $user,
            ]
        ], \Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
    }
}
