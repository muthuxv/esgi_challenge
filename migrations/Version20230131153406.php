<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131153406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT fk_9067f23c9d86650f');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT fk_9067f23c3348343b');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT fk_9067f23ce07b9190');
        $this->addSql('DROP INDEX idx_9067f23ce07b9190');
        $this->addSql('DROP INDEX idx_9067f23c3348343b');
        $this->addSql('DROP INDEX idx_9067f23c9d86650f');
        $this->addSql('ALTER TABLE mission ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE mission ADD mission_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE mission ADD hero_id INT NOT NULL');
        $this->addSql('ALTER TABLE mission DROP user_id_id');
        $this->addSql('ALTER TABLE mission DROP mission_type_id_id');
        $this->addSql('ALTER TABLE mission DROP hero_id_id');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C547018DE FOREIGN KEY (mission_type_id) REFERENCES mission_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C45B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9067F23CA76ED395 ON mission (user_id)');
        $this->addSql('CREATE INDEX IDX_9067F23C547018DE ON mission (mission_type_id)');
        $this->addSql('CREATE INDEX IDX_9067F23C45B0BCD ON mission (hero_id)');
        $this->addSql('ALTER TABLE mission_history DROP CONSTRAINT fk_b686e406efd2c16a');
        $this->addSql('DROP INDEX idx_b686e406efd2c16a');
        $this->addSql('ALTER TABLE mission_history RENAME COLUMN mission_id_id TO mission_id');
        $this->addSql('ALTER TABLE mission_history ADD CONSTRAINT FK_B686E406BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B686E406BE6CAE90 ON mission_history (mission_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mission_history DROP CONSTRAINT FK_B686E406BE6CAE90');
        $this->addSql('DROP INDEX IDX_B686E406BE6CAE90');
        $this->addSql('ALTER TABLE mission_history RENAME COLUMN mission_id TO mission_id_id');
        $this->addSql('ALTER TABLE mission_history ADD CONSTRAINT fk_b686e406efd2c16a FOREIGN KEY (mission_id_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b686e406efd2c16a ON mission_history (mission_id_id)');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT FK_9067F23CA76ED395');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT FK_9067F23C547018DE');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT FK_9067F23C45B0BCD');
        $this->addSql('DROP INDEX IDX_9067F23CA76ED395');
        $this->addSql('DROP INDEX IDX_9067F23C547018DE');
        $this->addSql('DROP INDEX IDX_9067F23C45B0BCD');
        $this->addSql('ALTER TABLE mission ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE mission ADD mission_type_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE mission ADD hero_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE mission DROP user_id');
        $this->addSql('ALTER TABLE mission DROP mission_type_id');
        $this->addSql('ALTER TABLE mission DROP hero_id');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT fk_9067f23c9d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT fk_9067f23c3348343b FOREIGN KEY (mission_type_id_id) REFERENCES mission_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT fk_9067f23ce07b9190 FOREIGN KEY (hero_id_id) REFERENCES hero (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9067f23ce07b9190 ON mission (hero_id_id)');
        $this->addSql('CREATE INDEX idx_9067f23c3348343b ON mission (mission_type_id_id)');
        $this->addSql('CREATE INDEX idx_9067f23c9d86650f ON mission (user_id_id)');
    }
}
