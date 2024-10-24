<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\Entity\Product;
use Psr\Log\LoggerInterface;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api')]
class ProductController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProductRepository $productRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private LoggerInterface $logger
    ) {}

    #[Route('/products', methods: ['POST'])]
    public function create(
        Request $request,
        #[MapRequestPayload(serializationContext: ['groups' => ['write']])] Product $product
    ): JsonResponse {
        try {

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return $this->json($product, Response::HTTP_CREATED, [], ['groups' => ['read']]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur création produit', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->getContent()
            ]);

            return $this->json(
                ['error' => 'Données fournies non valides'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/products', methods: ['GET'])]
    public function getAllProducts(
        #[MapQueryString] ?PaginationDTO $pagination = null
    ): JsonResponse {

        $result = $this->productRepository->getPaginatedList(
            page: $pagination?->page ?? 1,
            limit: $pagination?->limit ?? 10
        );

        return $this->json(
            $result,
            Response::HTTP_OK,
            [],
            ['groups' => ['read']]
        );
    }

    #[Route('/products/{id}', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function getProduct(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return $this->json(
                ['error' => 'Produit non trouvé'],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json($product, Response::HTTP_OK, [], ['groups' => ['read']]);
    }

    #[Route('/products/{id}', methods: ['PATCH'], requirements: ['id' => Requirement::DIGITS])]
    public function update(
        int $id,
        Request $request,
        #[MapRequestPayload(serializationContext: ['groups' => ['write']])] Product $product
    ): Response {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return $this->json([
                'error' => 'Produit non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->entityManager->flush();

            return $this->json($product, Response::HTTP_OK, [], ['groups' => ['read']]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur update produit', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->getContent()
            ]);

            return $this->json([
                'error' => 'Données fournies non valides'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/products/{id}', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(int $id): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return $this->json([
                'error' => 'Produit non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
