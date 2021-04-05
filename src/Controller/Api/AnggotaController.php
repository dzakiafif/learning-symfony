<?php

namespace App\Controller\Api;

use App\Libraries\GeneralLib;
use App\Libraries\ResponseBase;
use App\Repository\AnggotaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class AnggotaController extends AbstractController
{
    private $anggotaRepository;

    public function __construct(AnggotaRepository $anggotaRepository)
    {
        $this->anggotaRepository = $anggotaRepository;
    }

    public function index(): JsonResponse
    {
        $data = [
            'status' => 'success',
            'code' => 200,
            'data' => 'welcome to api'
        ];
        return new JsonResponse($data,Response::HTTP_OK);
    }

    public function me(UserInterface $anggota): JsonResponse
    {
        $response = [
            'status' => 'success',
            'code' => 200,
            'data' => $anggota
        ];

        return ResponseBase::success($response);
    }

    public function add(Request $request)
    {
        try {

            $data = json_decode($request->getContent(),true);

            $validator = Validation::createValidator();

            $constraint = new Assert\Collection([
                'first_name' => new Assert\Type(['type' => 'string']),
                'last_name' => new Assert\Type(['type' => 'string']),
                'email' => [
                    new Assert\Email(),
                    new Assert\NotBlank()
                ],
                'password' => [
                    new Assert\Length(['min' => 4,'max' => 12]),
                    new Assert\NotBlank()
                ],
                'phone_number' => [
                    new Assert\Length(['min' => 10,'max' => 12]),
                    new Assert\Regex("/^([0-9\s\-\+\(\)]*)$/")
                ]
            ]);

            $violations = $validator->validate($data,$constraint);

            if(count($violations) > 0) {
                $message = [];
                foreach($violations as $error) {
                    $message[] = $error->getPropertyPath() . ": " . $error->getMessage();
                }

                throw new \Exception(implode(", ",$message));
            }

            $firstName = $data['first_name'];
            $lastName = $data['last_name'];
            $email = $data['email'];
            $password = $data['password'];
            $phoneNumber = GeneralLib::convertPhoneNumber($data['phone_number']);

            $dataAnggota = $this->anggotaRepository->saveAnggota($firstName,$lastName,$email,$password,['ROLE_USER'],$phoneNumber);

            $response = [
                'status' => 'success',
                'code' => 200,
                'data' => $dataAnggota
            ];

            return ResponseBase::success($response);

        }catch(\Exception $e) {
            return ResponseBase::error($e->getCode(),$e->getMessage());
        }
    }
}