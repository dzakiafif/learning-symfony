<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331041439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE anggota (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(191) DEFAULT NULL, last_name VARCHAR(191) DEFAULT NULL, phone_number VARCHAR(191) DEFAULT NULL, roles JSON NOT NULL, email VARCHAR(191) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_3DDDF3C3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE buku (id INT AUTO_INCREMENT NOT NULL, judul VARCHAR(191) NOT NULL, pengarang VARCHAR(191) NOT NULL, penerbit VARCHAR(191) NOT NULL, tahun_terbit VARCHAR(191) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pinjam (id INT AUTO_INCREMENT NOT NULL, anggota_id INT NOT NULL, buku_id INT NOT NULL, tanggal_pinjam DATE NOT NULL, tanggal_kembali DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_FF9E309B325BF103 (anggota_id), INDEX IDX_FF9E309BF1A91410 (buku_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pinjam ADD CONSTRAINT FK_FF9E309B325BF103 FOREIGN KEY (anggota_id) REFERENCES anggota (id)');
        $this->addSql('ALTER TABLE pinjam ADD CONSTRAINT FK_FF9E309BF1A91410 FOREIGN KEY (buku_id) REFERENCES buku (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pinjam DROP FOREIGN KEY FK_FF9E309B325BF103');
        $this->addSql('ALTER TABLE pinjam DROP FOREIGN KEY FK_FF9E309BF1A91410');
        $this->addSql('DROP TABLE anggota');
        $this->addSql('DROP TABLE buku');
        $this->addSql('DROP TABLE pinjam');
    }
}
