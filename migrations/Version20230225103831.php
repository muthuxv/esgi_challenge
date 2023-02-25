<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230225103831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE ability_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE event_payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE hero_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mission_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mission_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mission_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reset_password_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ability (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price DOUBLE PRECISION DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN event.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN event.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN event.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(event_id, user_id))');
        $this->addSql('CREATE INDEX IDX_92589AE271F7E88B ON event_user (event_id)');
        $this->addSql('CREATE INDEX IDX_92589AE2A76ED395 ON event_user (user_id)');
        $this->addSql('CREATE TABLE event_hero (event_id INT NOT NULL, hero_id INT NOT NULL, PRIMARY KEY(event_id, hero_id))');
        $this->addSql('CREATE INDEX IDX_4E05222D71F7E88B ON event_hero (event_id)');
        $this->addSql('CREATE INDEX IDX_4E05222D45B0BCD ON event_hero (hero_id)');
        $this->addSql('CREATE TABLE event_payment (id INT NOT NULL, event_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E87F2DA271F7E88B ON event_payment (event_id)');
        $this->addSql('CREATE INDEX IDX_E87F2DA2A76ED395 ON event_payment (user_id)');
        $this->addSql('CREATE TABLE hero (id INT NOT NULL, user_id INT NOT NULL, hero_name VARCHAR(255) NOT NULL, is_available BOOLEAN NOT NULL, avatar VARCHAR(255) DEFAULT NULL, rank VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51CE6E86A76ED395 ON hero (user_id)');
        $this->addSql('CREATE TABLE hero_ability (hero_id INT NOT NULL, ability_id INT NOT NULL, PRIMARY KEY(hero_id, ability_id))');
        $this->addSql('CREATE INDEX IDX_21CB2D1B45B0BCD ON hero_ability (hero_id)');
        $this->addSql('CREATE INDEX IDX_21CB2D1B8016D8B2 ON hero_ability (ability_id)');
        $this->addSql('CREATE TABLE mission (id INT NOT NULL, user_id INT NOT NULL, hero_id INT DEFAULT NULL, mission_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, result VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_end TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9067F23CA76ED395 ON mission (user_id)');
        $this->addSql('CREATE INDEX IDX_9067F23C45B0BCD ON mission (hero_id)');
        $this->addSql('CREATE INDEX IDX_9067F23C547018DE ON mission (mission_type_id)');
        $this->addSql('COMMENT ON COLUMN mission.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN mission.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN mission.date_end IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE mission_history (id INT NOT NULL, mission_id INT NOT NULL, updated_by_id INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B686E406BE6CAE90 ON mission_history (mission_id)');
        $this->addSql('CREATE INDEX IDX_B686E406896DBBDE ON mission_history (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN mission_history.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE mission_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reset_password_request (id INT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('COMMENT ON COLUMN reset_password_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN reset_password_request.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_hero ADD CONSTRAINT FK_4E05222D71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_hero ADD CONSTRAINT FK_4E05222D45B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_payment ADD CONSTRAINT FK_E87F2DA271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_payment ADD CONSTRAINT FK_E87F2DA2A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E86A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hero_ability ADD CONSTRAINT FK_21CB2D1B45B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hero_ability ADD CONSTRAINT FK_21CB2D1B8016D8B2 FOREIGN KEY (ability_id) REFERENCES ability (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C45B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C547018DE FOREIGN KEY (mission_type_id) REFERENCES mission_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission_history ADD CONSTRAINT FK_B686E406BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission_history ADD CONSTRAINT FK_B686E406896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE ability_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE event_payment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE hero_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mission_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mission_history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mission_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reset_password_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE event_user DROP CONSTRAINT FK_92589AE271F7E88B');
        $this->addSql('ALTER TABLE event_user DROP CONSTRAINT FK_92589AE2A76ED395');
        $this->addSql('ALTER TABLE event_hero DROP CONSTRAINT FK_4E05222D71F7E88B');
        $this->addSql('ALTER TABLE event_hero DROP CONSTRAINT FK_4E05222D45B0BCD');
        $this->addSql('ALTER TABLE event_payment DROP CONSTRAINT FK_E87F2DA271F7E88B');
        $this->addSql('ALTER TABLE event_payment DROP CONSTRAINT FK_E87F2DA2A76ED395');
        $this->addSql('ALTER TABLE hero DROP CONSTRAINT FK_51CE6E86A76ED395');
        $this->addSql('ALTER TABLE hero_ability DROP CONSTRAINT FK_21CB2D1B45B0BCD');
        $this->addSql('ALTER TABLE hero_ability DROP CONSTRAINT FK_21CB2D1B8016D8B2');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT FK_9067F23CA76ED395');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT FK_9067F23C45B0BCD');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT FK_9067F23C547018DE');
        $this->addSql('ALTER TABLE mission_history DROP CONSTRAINT FK_B686E406BE6CAE90');
        $this->addSql('ALTER TABLE mission_history DROP CONSTRAINT FK_B686E406896DBBDE');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE ability');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('DROP TABLE event_hero');
        $this->addSql('DROP TABLE event_payment');
        $this->addSql('DROP TABLE hero');
        $this->addSql('DROP TABLE hero_ability');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE mission_history');
        $this->addSql('DROP TABLE mission_type');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
