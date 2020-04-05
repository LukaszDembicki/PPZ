<?php

declare(strict_types=1);

namespace App\Utilities\Serializer;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Normalizer
{
    /**
     * @param array $data
     * @param string|null $object $object
     * @param string|null $format
     * @param array|null $context
     * @return object
     * @throws ExceptionInterface
     */
    public function denormalize(array $data, ?string $object, ?string $format = null, ?array $context = []): object
    {
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer]);
        $normalizer->setSerializer($serializer);

        return $normalizer->denormalize($data, $object, $format, $context);
    }

    /**
     * @param object $object
     * @param string $format
     * @param array $context
     * @return array
     * @throws ExceptionInterface
     */
    public function normalize($object, ?string $format = 'array', ?array $context = []): array
    {
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer]);
        $normalizer->setSerializer($serializer);

        return $normalizer->normalize($object, $format, $context);
    }
}
