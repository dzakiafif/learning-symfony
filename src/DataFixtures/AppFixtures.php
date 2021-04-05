<?php

namespace App\DataFixtures;

use App\Entity\Buku;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $penerbit = ['Elexmedia Komputindo','Andi Publisher','Gagas Media','Gramedia Pustaka Utama','Agro Media','Erlangga'];    
        for ($i = 0; $i <= 100; $i++) {
            $book = new Buku();
            $book->setJudul(substr($faker->sentence(5),0,strlen($faker->sentence(5)) - 1));
            $book->setPengarang($faker->name());
            $book->setPenerbit($penerbit[array_rand($penerbit)]);
            $book->setTahunTerbit($faker->year('now'));
            $manager->persist($book);
        }

        $manager->flush();
    }
}
