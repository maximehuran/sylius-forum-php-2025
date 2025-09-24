<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250830143736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_game (id INT AUTO_INCREMENT NOT NULL, cover VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_game_console (game_id INT NOT NULL, console_id INT NOT NULL, INDEX IDX_614FAA18E48FD905 (game_id), INDEX IDX_614FAA1872F9DD9F (console_id), PRIMARY KEY(game_id, console_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_game_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_FE02E6CC2C2AC5D3 (translatable_id), UNIQUE INDEX app_game_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_game_console ADD CONSTRAINT FK_614FAA18E48FD905 FOREIGN KEY (game_id) REFERENCES app_game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_game_console ADD CONSTRAINT FK_614FAA1872F9DD9F FOREIGN KEY (console_id) REFERENCES app_console (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_game_translation ADD CONSTRAINT FK_FE02E6CC2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES app_game (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_game_console DROP FOREIGN KEY FK_614FAA18E48FD905');
        $this->addSql('ALTER TABLE app_game_console DROP FOREIGN KEY FK_614FAA1872F9DD9F');
        $this->addSql('ALTER TABLE app_game_translation DROP FOREIGN KEY FK_FE02E6CC2C2AC5D3');
        $this->addSql('DROP TABLE app_game');
        $this->addSql('DROP TABLE app_game_console');
        $this->addSql('DROP TABLE app_game_translation');
    }
}
