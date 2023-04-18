<?php

namespace App\Controller;

use App\Entity\Order;
use DateTimeImmutable;
use App\Helpers\Request;
use App\Entity\CartProduct;
use App\Entity\OrderProduct;
use App\Services\TokenService;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\CartProductRepository;
use App\Repository\OrderProductRepository;
use App\Resources\CartProductResource;
use App\Resources\OrderProductResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

#[Route('/api')]
class CartController extends RestController implements TokenAuthenticatedController
{
    #[Route('/carts', name: 'api_get_product_cart', methods: ["GET"])]
    public function cart(
        SymfonyRequest $request,
        UserRepository $userRepository,
        TokenService $token,
        EntityManagerInterface $entityManagerInterface
    ) {
        $request = new Request($request);
        $user = $request->getUser($userRepository, $token);

        $cart = $user->getCart();

        $cartProductResource = new CartProductResource($entityManagerInterface);

        return $this->handleResponse("", [
            'products' => $cartProductResource->resourceCollection($cart->getCartProducts())
        ]);
    }

    #[Route('/carts/{productId}', name: 'api_add_product_cart', methods: ["POST"])]
    public function addProduct(
        SymfonyRequest $request,
        UserRepository $userRepository,
        TokenService $token,
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        CartProductRepository $cartProductRepository,
        EntityManagerInterface $entityManagerInterface,
        $productId
    ) {
        $request = new Request($request);
        $user = $request->getUser($userRepository, $token);

        $product = $productRepository->findOneById($productId);

        if (!isset($product)) {
            return $this->handleError("Product not found", [], '404');
        }

        $cart = $user->getCart();

        $cartProduct = $cartProductRepository->findOneByCartAndProduct($cart->getId(), $product->getId());

        if (!isset($cartProduct)) {
            $cartProduct = new CartProduct();
            $cartProduct->setCart($cart);
            $cartProduct->setProduct($product);
            $cartProduct->setQuantity(0);
            $cartProductRepository->add($cartProduct);

            $cart->addCartProduct($cartProduct);
            $cartRepository->persist();
        }

        $cartProduct->setQuantity($cartProduct->getQuantity() + 1);
        $cartProductRepository->persist();

        $cartProductResource = new CartProductResource($entityManagerInterface);

        return $this->handleResponse("Product added to your cart", [
            'products' => $cartProductResource->resourceCollection($cart->getCartProducts())
        ]);
    }

    #[Route('/carts/{productId}', name: 'api_delete_product_cart', methods: ["DELETE"])]
    public function removeProduct(
        SymfonyRequest $request,
        UserRepository $userRepository,
        TokenService $token,
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        CartProductRepository $cartProductRepository,
        EntityManagerInterface $entityManagerInterface,
        $productId
    ) {
        $request = new Request($request);
        $user = $request->getUser($userRepository, $token);

        $product = $productRepository->findOneById($productId);

        if (!isset($product)) {
            return $this->handleError("Product not found", [], '404');
        }

        $cart = $user->getCart();

        $cartProduct = $cartProductRepository->findOneByCartAndProduct($cart->getId(), $product->getId());

        if (!isset($cartProduct)) {
            return $this->handleError('This product isn\'t in your cart', [], 404);
        }

        $quantity = max(0, $cartProduct->getQuantity() - 1);
        $cartProduct->setQuantity($quantity);
        if ($quantity === 0) {
            $cartProductRepository->remove($cartProduct);
            $cart->removeCartProduct($cartProduct);
            $cartRepository->persist();
        }

        $cartProductRepository->persist();

        $cartProductResource = new CartProductResource($entityManagerInterface);

        return $this->handleResponse("Product removed from your cart", [
            'products' => $cartProductResource->resourceCollection($cart->getCartProducts())
        ]);
    }



    #[Route('/carts/validate', name: 'api_validate_cart', methods: ["GET"])]
    public function validate(
        SymfonyRequest $request,
        UserRepository $userRepository,
        TokenService $token,
        OrderRepository $orderRepository,
        OrderProductRepository $orderProductRepository,
        EntityManagerInterface $entityManagerInterface
    ) {
        $request = new Request($request);
        $user = $request->getUser($userRepository, $token);

        $cart = $user->getCart();

        if ($cart->getCartProducts()->count() < 1) {
            return $this->handleError("Your cart is empty");
        }

        $order = new Order();
        $order->setCreationDate(new DateTimeImmutable());
        $order->setTotalPrice(0);
        $order->setUser($user);
        $orderRepository->add($order);

        $totalPrice = 0;
        foreach ($cart->getCartProducts() as $cartProduct) {
            $product = $cartProduct->getProduct();
            $totalPrice += $product->getPrice() * $cartProduct->getQuantity();

            $orderProduct = new OrderProduct();
            $orderProduct->setTheOrder($order);
            $orderProduct->setProduct($product);
            $orderProduct->setQuantity($cartProduct->getQuantity());
            $orderProductRepository->add($orderProduct);

            $order->addProduct($orderProduct);
        }

        $order->setTotalPrice($totalPrice);
        $orderRepository->persist();

        $orderProductResource = new OrderProductResource($entityManagerInterface);

        return $this->handleResponse("Order completed", [
            'order' => [
                'id' => $order->getId(),
                'totalPrice' => $order->getTotalPrice(),
                'creationDate' => $order->getCreationDate(),
                'products' => $orderProductResource->resourceCollection($order->getProducts())
            ]
        ], 201);
    }
}
