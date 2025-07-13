<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250713173233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer (id SERIAL NOT NULL, rawg_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE game (id SERIAL NOT NULL, rawg_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, released DATE DEFAULT NULL, background_image VARCHAR(255) DEFAULT NULL, rating DOUBLE PRECISION DEFAULT NULL, rating_count INT DEFAULT NULL, playtime INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE game_genre (game_id INT NOT NULL, genre_id INT NOT NULL, PRIMARY KEY(game_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_B1634A77E48FD905 ON game_genre (game_id)');
        $this->addSql('CREATE INDEX IDX_B1634A774296D31F ON game_genre (genre_id)');
        $this->addSql('CREATE TABLE game_platform (game_id INT NOT NULL, platform_id INT NOT NULL, PRIMARY KEY(game_id, platform_id))');
        $this->addSql('CREATE INDEX IDX_92162FEDE48FD905 ON game_platform (game_id)');
        $this->addSql('CREATE INDEX IDX_92162FEDFFE6496F ON game_platform (platform_id)');
        $this->addSql('CREATE TABLE game_developer (game_id INT NOT NULL, developer_id INT NOT NULL, PRIMARY KEY(game_id, developer_id))');
        $this->addSql('CREATE INDEX IDX_B75D4A98E48FD905 ON game_developer (game_id)');
        $this->addSql('CREATE INDEX IDX_B75D4A9864DD9267 ON game_developer (developer_id)');
        $this->addSql('CREATE TABLE genre (id SERIAL NOT NULL, rawg_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE platform (id SERIAL NOT NULL, rawg_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON users (username)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE game_genre ADD CONSTRAINT FK_B1634A77E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_genre ADD CONSTRAINT FK_B1634A774296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_platform ADD CONSTRAINT FK_92162FEDE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_platform ADD CONSTRAINT FK_92162FEDFFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_developer ADD CONSTRAINT FK_B75D4A98E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_developer ADD CONSTRAINT FK_B75D4A9864DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game_genre DROP CONSTRAINT FK_B1634A77E48FD905');
        $this->addSql('ALTER TABLE game_genre DROP CONSTRAINT FK_B1634A774296D31F');
        $this->addSql('ALTER TABLE game_platform DROP CONSTRAINT FK_92162FEDE48FD905');
        $this->addSql('ALTER TABLE game_platform DROP CONSTRAINT FK_92162FEDFFE6496F');
        $this->addSql('ALTER TABLE game_developer DROP CONSTRAINT FK_B75D4A98E48FD905');
        $this->addSql('ALTER TABLE game_developer DROP CONSTRAINT FK_B75D4A9864DD9267');
        $this->addSql('DROP TABLE developer');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_genre');
        $this->addSql('DROP TABLE game_platform');
        $this->addSql('DROP TABLE game_developer');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE platform');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
