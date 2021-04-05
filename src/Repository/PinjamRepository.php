<?php

namespace App\Repository;

use App\Entity\Buku;
use App\Entity\Pinjam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @method Pinjam|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pinjam|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pinjam[]    findAll()
 * @method Pinjam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PinjamRepository extends ServiceEntityRepository
{
    private $manager;

    private $token;

    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager, TokenStorageInterface $token)
    {
        parent::__construct($registry, Pinjam::class);
        $this->manager = $manager;
        $this->token = $token;
    }

    public function checkBook($bookName): array
    {
        $response = ['status' => false,'data' => null,'message' => null];

        try{

            $builder = $this->getEntityManager()->getRepository(Buku::class);
            $getOneData = $builder->findOneBy(['judul' => $bookName]);

            $response['status'] = true;
            $response['data'] = $getOneData;
        }catch(\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function checkPinjam(): array
    {
        $response = ['status' => false, 'data' => null, 'message' => null];

        try{

            $builder = $this->getEntityManager()->getRepository(Pinjam::class);
            $getOneData = $builder->findOneBy(['anggota' => $this->token->getToken()->getUser()->getId()]);

            $response['status'] = true;
            $response['data'] = $getOneData;

        }catch(\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function savePinjam($bookId,$tanggalPinjam, $tanggalKembali): array
    {
        $response = ['status' => false,'data' => null,'message' => null];

        try{

            $builder = $this->getEntityManager()->getRepository(Pinjam::class);
            $getOnePinjam = $builder->createQueryBuilder('c')
                            ->where('c.buku = :idBuku')
                            ->setParameter('idBuku',$bookId->getId())
                            ->orderBy('c.created_at','desc');

            $resultOnePinjam = $getOnePinjam->getQuery()->setMaxResults(1)->getOneOrNullResult();
            
            if($resultOnePinjam != null && ($resultOnePinjam->getAnggota()->getId() == $this->token->getToken()->getUser()->getId()))
                throw new \Exception("the book with title {$resultOnePinjam->getBuku()->getJudul()} has been rent by you. please update if u want rent book again");

            if($resultOnePinjam != null && ($resultOnePinjam->getTanggalKembali() > new \DateTime($tanggalPinjam)))
                throw new \Exception("the book with title {$resultOnePinjam->getBuku()->getJudul()} still rent with other user");

            $pinjam = new Pinjam();
            $pinjam->setBuku($bookId);
            $pinjam->setAnggota($this->token->getToken()->getUser());
            $pinjam->setTanggalPinjam(new \DateTime($tanggalPinjam));
            $pinjam->setTanggalKembali(new \DateTime($tanggalKembali));
            $this->manager->persist($pinjam);
            $this->manager->flush();

            $response['status'] = true;
            $response['data'] = $pinjam;

        }catch(\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return $response;

    }

    public function updatePinjam($bookId,$tanggalPinjam,$tanggalKembali): array
    {

        $response = ['status' => false, 'message' => null, 'data' => null];

        try{

            $builder = $this->getEntityManager()->getRepository(Pinjam::class);
            $getOneData = $builder->findOneBy(['anggota' => $this->token->getToken()->getUser()->getId(), 'buku' => $bookId->getId()],['created_at' => 'desc']);
            $getOneData->setTanggalPinjam(new \DateTime($tanggalPinjam));
            $getOneData->setTanggalKembali(new \DateTime($tanggalKembali));
            $this->manager->persist($getOneData);
            $this->manager->flush();

            if(!$getOneData)
                throw new \Exception("check query");

            $response['status'] = true;
            $response['data'] = $getOneData;        

        }catch(\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return $response;
    }
}
