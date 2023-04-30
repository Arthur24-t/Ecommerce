<?php

namespace App\Controller;

use App\Helpers\Request;
use App\Services\TokenService;
use App\Resources\UserResource;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api')]
class UserController extends RestController implements TokenAuthenticatedController
{
    #[Route('/users', name: 'user_getinfo', methods: ["GET"])]
    public function getInfoUser(EntityManagerInterface $entityManager, SymfonyRequest $request, UserRepository $userRepository, TokenService $token)
    {
        $request = new Request($request);
        $user = $request->getUser($userRepository, $token);

        $userResource = new UserResource($entityManager);

        return $this->handleResponse('Information successfully found', [
            'user' => $userResource->resource($user)
        ]);
    }


    #[Route('/users', name: 'user_update', methods: ["POST"])]
    public function updateUser(
        EntityManagerInterface $entityManager,
        SymfonyRequest $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasherInterface,
        TokenService $token
    ) {
        $request = new Request($request);
        $user = $request->getUser($userRepository, $token);

        if (!isset($user)) {
            return $this->handleResponse('Wrong credentials', [], Response::HTTP_CONFLICT);
        }

        if (!$userPasswordHasherInterface->isPasswordValid($user, $request->get('password'))) {
            return $this->handleResponse('Wrong credentials', [], Response::HTTP_UNAUTHORIZED);
        }

        $user->setLogin($request->get('login', $user->getLogin()));
        $user->setEmail($request->get('email', $user->getEmail()));
        $user->setFirstname($request->get('firstname', $user->getFirstname()));
        $user->setLastname($request->get('lastname', $user->getLastname()));

        $userRepository->persist();

        $userResource = new UserResource($entityManager);

        return $this->handleResponse('Information successfully updated', [
            'user' => $userResource->resource($user)
        ]);
    }
}
