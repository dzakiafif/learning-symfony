<?php

namespace App\Controller\Api;

use App\Libraries\ResponseBase;
use App\Repository\PinjamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class PinjamController extends AbstractController
{
    private $pinjamRepository;

    public function __construct(PinjamRepository $pinjamRepository)
    {
        $this->pinjamRepository = $pinjamRepository;
    }

    public function add(Request $request): JsonResponse
    {
        try{

            $decodeRequest = json_decode($request->getContent(),true);

            $validation = Validation::createValidator();
            
            $constraint = new Assert\Collection([
                'book_title' =>  new Assert\NotBlank(),
                'tanggal_pinjam' => [
                    new Assert\NotBlank(),
                    new Assert\LessThan([
                        'value' => $decodeRequest['tanggal_kembali'],
                        'message' => $decodeRequest['tanggal_pinjam'] . ' should be less than ' . $decodeRequest['tanggal_kembali']
                    ])
                ],
                'tanggal_kembali' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThan([
                        'value' => $decodeRequest['tanggal_pinjam'],
                        'message' => $decodeRequest['tanggal_kembali'] . ' should be greater than ' . $decodeRequest['tanggal_pinjam']
                    ])
                ]
            ]);

            $violations = $validation->validate($decodeRequest,$constraint);
            if(count($violations) > 0)
            {
                $message = [];
                foreach($violations as $error) {
                    $message[] = $error->getPropertyPath() . ": " . $error->getMessage();
                }

                throw new \Exception(implode(", ",$message));
            }
        
            $checkBook = $this->pinjamRepository->checkBook($decodeRequest['book_title']);
            if(!$checkBook['status'])
                throw new \Exception($checkBook['message']);

            $createPinjam = $this->pinjamRepository->savePinjam($checkBook['data'],$decodeRequest['tanggal_pinjam'],$decodeRequest['tanggal_kembali']);
            if(!$createPinjam['status'])
                throw new \Exception($createPinjam['message']);

            $response = [
                'status' => 'success',
                'code' => 200,
                'data' => $createPinjam['data']
            ];

            return ResponseBase::success($response);

        }catch(\Exception $e) {
            return ResponseBase::error($e->getCode(),$e->getMessage());
        }
    }

    public function update($judul,Request $request): JsonResponse
    {
        try{

            $decodeRequest = (array) json_decode($request->getContent(),true);

            $validation = Validation::createValidator();

            $constraint = new Assert\Collection([
                'tanggal_pinjam' => [
                    new Assert\NotBlank(),
                    new Assert\LessThan([
                        'value' => $decodeRequest['tanggal_kembali'],
                        'message' => $decodeRequest['tanggal_pinjam'] . ' should be less than ' . $decodeRequest['tanggal_kembali']
                    ])
                ],
                'tanggal_kembali' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThan([
                        'value' => $decodeRequest['tanggal_pinjam'],
                        'message' => $decodeRequest['tanggal_kembali'] . ' should be greater than ' . $decodeRequest['tanggal_pinjam']
                    ])
                ]
            ]);

            $violations = $validation->validate($decodeRequest,$constraint);
            if(count($violations) > 0)
            {
                $message = [];
                foreach($violations as $error) {
                    $message[] = $error->getPropertyPath() . ": " . $error->getMessage();
                }

                throw new \Exception(implode(", ",$message));
            }

            $checkBook = $this->pinjamRepository->checkBook($judul);
            if(!$checkBook['status'])
                throw new \Exception($checkBook['message']);

            $updatePinjam = $this->pinjamRepository->updatePinjam($checkBook['data'],$decodeRequest['tanggal_pinjam'],$decodeRequest['tanggal_kembali']);
            if(!$updatePinjam['status'])
                throw new \Exception($updatePinjam['message']);

            $response = [
                'status' => 'success',
                'code' => 200,
                'data' => $updatePinjam['data']
            ];

            return ResponseBase::success($response);

        }catch(\Exception $e) {
            return ResponseBase::error($e->getCode(),$e->getMessage());
        }

        
        
    }
}