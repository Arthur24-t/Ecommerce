<?php

namespace App\Controller;

use App\Helpers\Request;
use App\Repository\OrderRepository;
use App\Services\TokenService;
use App\Repository\UserRepository;
use App\Resources\OrderProductResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

#[Route('/api')]
class OrderController extends RestController implements TokenAuthenticatedController
{
    #[Route('/orders/{orderId}', name: 'app_orders_getOne', methods: ["GET"])]
    public function getOneOrder(
        UserRepository $userRepository,
        SymfonyRequest $request,
        TokenService $token,
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManagerInterface,
        $orderId
    ) {
        $request = new Request($request);
        $user = $request->getUser($userRepository, $token);

        $order = $orderRepository->findOneById($orderId);

        if (!isset($order)) {
            return $this->handleError("Order not found", [], 404);
        }

        if ($order->getUser() !== $user) {
            return $this->handleError("You can't see this order", [], 403);
        }

        $orderProductResource = new OrderProductResource($entityManagerInterface);

        return $this->handleResponse("", [
            'order' => [
                'id' => $order->getId(),
                'totalPrice' => $order->getTotalPrice(),
                'creationDate' => $order->getCreationDate(),
                'products' => $orderProductResource->resourceCollection($order->getProducts())
            ]
        ], 201);
    }

    #[Route('/orders', name: 'app_orders_getAll', methods: ["GET"])]
    public function getAllOrder(
        SymfonyRequest $request,
        UserRepository $userRepository,
        TokenService $token,
        EntityManagerInterface $entityManagerInterface,
    ) {
        $request = new Request($request);
        $user = $request->getUser($userRepository, $token);

        $orders = [];

        foreach ($user->getOrders() as $key => $order) {
            $orderProductResource = new OrderProductResource($entityManagerInterface);

            $orders[$key] = [
                'id' => $order->getId(),
                'totalPrice' => $order->getTotalPrice(),
                'creationDate' => $order->getCreationDate(),
                'products' => $orderProductResource->resourceCollection($order->getProducts())
            ];
        }

        return $this->handleResponse("Order completed", [
            'orders' => $orders
        ], 201);
    }


}
