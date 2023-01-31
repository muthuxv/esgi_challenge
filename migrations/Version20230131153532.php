<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131153532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_payment DROP CONSTRAINT fk_e87f2da23e5f2f7b');
        $this->addSql('ALTER TABLE event_payment DROP CONSTRAINT fk_e87f2da29d86650f');
        $this->addSql('DROP INDEX idx_e87f2da29d86650f');
        $this->addSql('DROP INDEX idx_e87f2da23e5f2f7b');
        $this->addSql('ALTER TABLE event_payment ADD event_id INT NOT NULL');
        $this->addSql('ALTER TABLE event_payment ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE event_payment DROP event_id_id');
        $this->addSql('ALTER TABLE event_payment DROP user_id_id');
        $this->addSql('ALTER TABLE event_payment ADD CONSTRAINT FK_E87F2DA271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_payment ADD CONSTRAINT FK_E87F2DA2A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E87F2DA271F7E88B ON event_payment (event_id)');
        $this->addSql('CREATE INDEX IDX_E87F2DA2A76ED395 ON event_payment (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE event_payment DROP CONSTRAINT FK_E87F2DA271F7E88B');
        $this->addSql('ALTER TABLE event_payment DROP CONSTRAINT FK_E87F2DA2A76ED395');
        $this->addSql('DROP INDEX IDX_E87F2DA271F7E88B');
        $this->addSql('DROP INDEX IDX_E87F2DA2A76ED395');
        $this->addSql('ALTER TABLE event_payment ADD event_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE event_payment ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE event_payment DROP event_id');
        $this->addSql('ALTER TABLE event_payment DROP user_id');
        $this->addSql('ALTER TABLE event_payment ADD CONSTRAINT fk_e87f2da23e5f2f7b FOREIGN KEY (event_id_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_payment ADD CONSTRAINT fk_e87f2da29d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e87f2da29d86650f ON event_payment (user_id_id)');
        $this->addSql('CREATE INDEX idx_e87f2da23e5f2f7b ON event_payment (event_id_id)');
    }
}
