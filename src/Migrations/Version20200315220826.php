<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200315220826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lexani_videos ADD CONSTRAINT FK_47F85FAE6633314F FOREIGN KEY (user_parameters_id) REFERENCES user_parameters (id)');
        $this->addSql('CREATE INDEX IDX_47F85FAE6633314F ON lexani_videos (user_parameters_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lexani_videos DROP FOREIGN KEY FK_47F85FAE6633314F');
        $this->addSql('DROP INDEX IDX_47F85FAE6633314F ON lexani_videos');
    }
}
