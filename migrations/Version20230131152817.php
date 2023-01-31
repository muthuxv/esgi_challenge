<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131152817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hero DROP CONSTRAINT fk_51ce6e86a76ed395');
        $this->addSql('DROP INDEX uniq_51ce6e86a76ed395');
        $this->addSql('ALTER TABLE hero RENAME COLUMN user_id TO user_id_id');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E869D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51CE6E869D86650F ON hero (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE hero DROP CONSTRAINT FK_51CE6E869D86650F');
        $this->addSql('DROP INDEX UNIQ_51CE6E869D86650F');
        $this->addSql('ALTER TABLE hero RENAME COLUMN user_id_id TO user_id');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT fk_51ce6e86a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_51ce6e86a76ed395 ON hero (user_id)');
    }
}
