<?php

declare(strict_types=1);

namespace App\Validator;

use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueProductCodeValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueProductCode) {
            throw new UnexpectedTypeException($constraint, UniqueProductCode::class);
        }

        if (!is_string($value)) {
            return;
        }

        if ($value === '') {
            return;
        }

        $existingProduct = $this->productRepository->findOneBy(['code' => $value]);

        if ($existingProduct && $existingProduct->getId() !== $constraint->excludeId) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ code }}', $value)
                ->addViolation();
        }
    }
}
