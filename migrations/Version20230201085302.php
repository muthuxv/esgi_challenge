<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201085302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE hero_id_seq CASCADE');
        $this->addSql('ALTER TABLE hero ADD email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE hero ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE hero ADD password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE hero ADD is_verified BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE hero ADD lastname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE hero ADD firstname VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51CE6E86E7927C74 ON hero (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE hero_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP INDEX UNIQ_51CE6E86E7927C74');
        $this->addSql('ALTER TABLE hero DROP email');
        $this->addSql('ALTER TABLE hero DROP roles');
        $this->addSql('ALTER TABLE hero DROP password');
        $this->addSql('ALTER TABLE hero DROP is_verified');
        $this->addSql('ALTER TABLE hero DROP lastname');
        $this->addSql('ALTER TABLE hero DROP firstname');
    }
}
