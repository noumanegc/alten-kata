<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueProductCode extends Constraint
{
    public function __construct(
        public string $message = 'Ce code produit "{{ code }}" existe déjà',
        public ?int $excludeId = null,
        mixed $options = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
