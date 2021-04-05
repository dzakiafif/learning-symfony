<?php

namespace App\Libraries;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class ResponseBase 
{
    public static function success($dataResponse): JsonResponse
    {
        $response = [];

        $data = isset($dataResponse['data']) ? $dataResponse['data'] : null;

        if($data != null)
        {
            $encoder = new JsonEncoder();

            $dateCallback = function ($object) {
                return $object instanceof \DateTime ? $object->format('Y-m-d H:i:s') : '';
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

            $normalizerData = $serializer->normalize($dataResponse['data'],null,[AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','salt','updatedAt','roles','pinjams']]);
        }

        $response['status'] = 'success';
        $response['code']   = 200;
        $response['data']   = $data != null ? $normalizerData : $data;

        $resultResponse = new JsonResponse($response,Response::HTTP_OK);

        $resultResponse->setEncodingOptions( $resultResponse->getEncodingOptions() | JSON_PRETTY_PRINT );

        return $resultResponse;
    }

    public static function error($code = 400, $data): JsonResponse
    {
        $response = [];

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
            ],
        ];

        $normalizer = new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        
        $normalizerData = $serializer->normalize($data,null,[AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','salt','updatedAt','roles']]);

        $response['status'] = 'error';
        $response['code'] = $code <= 0 ? 400 : $code;
        $response['message'] = $normalizerData;

        $resultResponse = new JsonResponse($response,Response::HTTP_OK);

        $resultResponse->setEncodingOptions( $resultResponse->getEncodingOptions() | JSON_PRETTY_PRINT );

        return $resultResponse;
    }
}