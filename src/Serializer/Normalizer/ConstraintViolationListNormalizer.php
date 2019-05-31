<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationListNormalizer implements NormalizerInterface
{
    private $normalizer;

    public function __construct(ObjectNormalize $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        [$messages, $violations] = $this->transform($object);

        return [
            'title' => $context['title'] ?? 'An error occurred',
            'detail' => $messages ? implode("\n", $messages) : '',
            'violations' => $violations,
        ];
    }

    private function transform(ConstraintViolationListInterface $constraintViolationList)
    {
        $violations = $messages = [];

        /** @var ConstraintViolation $violation */
        foreach ($constraintViolationList as $violation) {
            $violations[] = [
                'code' => Response::HTTP_BAD_REQUEST,
                'propertyPath' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];

            $propertyPath = $violation->getPropertyPath();
            $messages[] = ($propertyPath ? $propertyPath . ': ' : '') . $violation->getMessage();
        }

        return [$messages, $violations];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof ConstraintViolationListInterface;
    }
}
