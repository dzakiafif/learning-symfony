<?php

namespace App\Repository;

use App\Entity\Anggota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Anggota|null find($id, $lockMode = null, $lockVersion = null)
 * @method Anggota|null findOneBy(array $criteria, array $orderBy = null)
 * @method Anggota[]    findAll()
 * @method Anggota[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnggotaRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private $manager;
    private $passwordEncoder;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        parent::__construct($registry, Anggota::class);
        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Anggota) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function saveAnggota($firstName,$lastName,$email,$password,$roles,$phoneNumber)
    {
        $anggota = new Anggota();
        $anggota->setFirstName($firstName);
        $anggota->setLastName($lastName);
        $anggota->setEmail($email);
        $anggota->setPassword($this->passwordEncoder->encodePassword($anggota,$password));
        $anggota->setRoles($roles);
        $anggota->setPhoneNumber($phoneNumber);
        $this->manager->persist($anggota);
        $this->manager->flush();

        return $anggota;
        
    }

    public function list()
    {
        $builder = $this->getEntityManager()->getRepository(Anggota::class);
        $getAllData = $builder->findAll();

        return $getAllData;
    }
}
