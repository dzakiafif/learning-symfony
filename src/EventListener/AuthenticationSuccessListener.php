<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class AuthenticationSuccessListener 
{
    /**
    * @param AuthenticationSuccessEvent $event
    */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {

        $encoder = new JsonEncoder();

        $dateCallback = function ($innerObject) {
            return $innerObject instanceof \DateTime ? $innerObject->format('Y-m-d H:i:s') : '';
        };

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'tanggalPinjam' => $dateCallback,
                'tanggalKembali' => $dateCallback,
                'createdAt' => $dateCallback,
                'updatedAt' => $dateCallback
            ]
        ];


        $normalizer = new GetSetMethodNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);

        $normalizerAnggota = $serializer->normalize($event->getUser(),null,[AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','salt','updatedAt','roles','pinjams']]);

        $normalizerAnggota['token'] = array_values($event->getData())[0];

        $event->setData([
            'status' => 'success',
            'code' => $event->getResponse()->getStatusCode(),
            'data' => $normalizerAnggota
        ]);
    }
}