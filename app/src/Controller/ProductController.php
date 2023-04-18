<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Resources\ProductResource;
use App\Services\TokenService;
use App\Services\ValidationService;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Helpers\Request;

#[Route('/api')]
class ProductController extends RestController implements TokenAuthenticatedController
{

    #[Route('/products', name: 'app_products_getAll', methods: ["GET"])]
    public function getProduct(ManagerRegistry $doctrine): Response
    {

        $products = $doctrine
            ->getRepository(product::class)
            ->findAll();

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'photo' => $product->getPhoto()
            ];
        }


        return $this->json($data);
    }
    
    #[Route('/product', name: 'api_product_add', methods: ["POST"])]
    public function createProduct(
        EntityManagerInterface $entityManager,
        SymfonyRequest $request,
        ProductRepository $productRepository,
        UserRepository $userRepository,
        TokenService $token,
        ValidatorInterface $validator
    ): Response {

        $request = new Request($request);
        $user = $request->getUser($userRepository, $token);

        if (!isset($user)) {
            return $this->handleError('', []);
        }

        $product = new Product();
        $product->setName($request->get('name'));
        $product->setDescription($request->get('description'));
        $product->setPrice($request->get('price'));
        $product->setPhoto($request->get('photo'));

        $errors = $validator->validate($product);

        if (count($errors) > 0) {
            return $this->handleError('The data contains some errors', ValidationService::getErrors($errors));
        }

        $product->setUser($user);
        $productRepository->add($product);

        $productResource = new ProductResource($entityManager);

        return $this->handleResponse('Product created', [
            'product' => $productResource->resource($product)
        ], 201);
    }


    #[Route('/products/{productId}', name: 'app_products_getOne', methods: ["GET"])]
    public function show(
        EntityManagerInterface $em,
        ProductRepository $pr,
        $productId
    ) {
        $product = $pr->findOneBy(['id' => $productId]);

        if (!isset($product)) {
            return $this->handleError('Product not found', [], 404);
        }

        $productResource = new ProductResource($em);

        return $this->handleResponse('Product found', [
            'product' => $productResource->resource($product)
        ]);
    }

    #[Route('/product/{productId}', name: 'app_product_update', methods: "POST")] 
    public function update(
        EntityManagerInterface $entityManager,
        SymfonyRequest $request,
        ProductRepository $productRepository,
        UserRepository $userRepository,
        TokenService $token,
        ValidatorInterface $validator,
        $productId
    ) {
        $request = new Request($request);
        $user = $request->getUser($userRepository, $token);

        $product = $productRepository->findOneBy(['id' => $productId]);

        if (isset($product)) {
            if ($product->getUser() === $user) {
                $product->setName($request->get('name') ?? $product->getName());
                $product->setDescription($request->get('description') ?? $product->getDescription());
                $product->setPrice($request->get('price') ?? $product->getPrice());
                $product->setPhoto($request->get('photo') ?? $product->getPhoto());

                $errors = $validator->validate($product);

                if (count($errors) > 0) {
                    return $this->handleError('The data contains some errors', ValidationService::getErrors($errors));
                }

                $productRepository->persist($product);

                $productResource = new ProductResource($entityManager);

                return $this->handleResponse('Product updated', [
                    'product' => $productResource->resource($product)
                ]);
            }

            return $this->handleError('You don\'t own this product');
        }

        return $this->handleError('Product doesn\'t exist');
    }

    #[Route('/product/{productId}', name: 'app_product_delete', methods: "DELETE")]
    public function deleteProduct(
        SymfonyRequest $request,
        ProductRepository $productRepository,
        UserRepository $userRepository,
        TokenService $ts,
        $productId
    ) {
        $request = new Request($request);
        $user = $request->getUser($userRepository, $ts);

        $product = $productRepository->findOneBy(['id' => $productId]);
        if (isset($product)) {
            if ($product->getUser() === $user) {
                $productRepository->remove($product);

                return $this->handleResponse('Product deleted');
            }
            return $this->handleError('You don\'t own this product');
        }

        return $this->handleError('Product doesn\'t exist');
    }
}
