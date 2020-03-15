<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200315215422 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_parameters (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(20) NOT NULL, browser VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lexani_videos ADD user_parameters_id INT NOT NULL, CHANGE thumbnail thumbnail VARCHAR(255) DEFAULT NULL, CHANGE youtube_link youtube_link VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE lexani_videos ADD CONSTRAINT FK_47F85FAE6633314F FOREIGN KEY (user_parameters_id) REFERENCES user_parameters (id)');
        $this->addSql('CREATE INDEX IDX_47F85FAE6633314F ON lexani_videos (user_parameters_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lexani_videos DROP FOREIGN KEY FK_47F85FAE6633314F');
        $this->addSql('DROP TABLE user_parameters');
        $this->addSql('DROP INDEX IDX_47F85FAE6633314F ON lexani_videos');
        $this->addSql('ALTER TABLE lexani_videos DROP user_parameters_id, CHANGE youtube_link youtube_link VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE thumbnail thumbnail LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
