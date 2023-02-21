<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230218171345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_b686e406896dbbde');
        $this->addSql('ALTER TABLE mission_history ALTER updated_by_id DROP NOT NULL');
        $this->addSql('CREATE INDEX IDX_B686E406896DBBDE ON mission_history (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_B686E406896DBBDE');
        $this->addSql('ALTER TABLE mission_history ALTER updated_by_id SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_b686e406896dbbde ON mission_history (updated_by_id)');
    }
}
