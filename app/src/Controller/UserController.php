<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

use App\Service\UserService;

class UserController extends AbstractController
{
    /**
     * Создание пользователя.
     */
    #[OA\Response(
        response: 200,
        description: 'Пользователь создан успешно',
    )]
    #[OA\Response(
        response: 400,
        description: 'Пользователь не был создан.',
    )]
    #[OA\Response(
        response: 500,
        description: 'Ошибка на стороне сервера.',
    )]
    #[Route('/api/user-create', name: 'app_create', methods: ['POST'], format: 'json')]
    public function createUser(
        #[OA\RequestBody(
            required: true,
            description: 'Новый пользователь',
            content: new OA\JsonContent(
                example: '{
                    "email": "test@example.com",
                    "name": "Test",
                    "age": 35,
                    "sex": "male",
                    "birthday": "1992-05-15",
                    "phone": "89261234567"
                }'
            )
         )] 
        Request $request, 
        UserService $userService): JsonResponse
    {
        return $this->processUserRequest($request, $userService, 'create');
    }

    /**
     * Изменение существующего пользователя.
     */
    #[OA\Response(
        response: 200,
        description: 'Пользователь создан успешно',
    )]
    #[OA\Response(
        response: 400,
        description: 'Пользователь не был создан.',
    )]
    #[OA\Response(
        response: 500,
        description: 'Ошибка на стороне сервера.',
    )]
    #[Route('/api/user-update', name: 'user-update', methods: ['POST'], format: 'json')]
    public function updateUser(
        #[OA\RequestBody(
            required: true,
            description: 'Новый пользователь',
            content: new OA\JsonContent(
                example: '{
                    "id": 1
                    "email": "test@example.com",
                    "name": "Test",
                    "age": 35,
                    "sex": "male",
                    "birthday": "1992-05-15",
                    "phone": "89261234567"
                }'
            )
         )] 
        Request $request, UserService $userService): JsonResponse
    {
        return $this->processUserRequest($request, $userService, 'update');
    }

    private function processUserRequest(Request $request, UserService $userService, string $operation): JsonResponse
    {
        $userData = json_decode($request->getContent(), true);
        try {
            $errors = $operation === 'update' ? $userService->updateUser($userData) : $userService->saveUser($userData); 
            $response = ($errors !== null) ?  
                $this->json(['message' => $errors], 400) :
                $this->json(['message' => 'User is updated successfully'], 201);
        } catch (\Exception $e) {
            return $this->json(['message' => 'Internal Server Error'], 500);
        }
        return $response;
    }
}
