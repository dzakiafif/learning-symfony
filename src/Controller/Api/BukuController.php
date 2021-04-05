<?php

namespace App\Controller\Api;

use App\Libraries\ResponseBase;
use App\Repository\BukuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class BukuController extends AbstractController
{
    private $bukuRepository;

    public function __construct(BukuRepository $bukuRepository)
    {
        $this->bukuRepository = $bukuRepository;
    }

    public function index(): JsonResponse
    {
        try{
            $getAllBook = $this->bukuRepository->listBook();

            $response = [
                'status' => 'success',
                'code' => 200,
                'data' => $getAllBook
            ];

            return ResponseBase::success($response);
        }catch(\Exception $e) {
            return ResponseBase::error($e->getCode(),$e->getMessage());
        }
    }
}