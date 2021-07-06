<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210613213834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE version DROP FOREIGN KEY FK_BF1CD3C3C18272');
        $this->addSql('ALTER TABLE version ADD CONSTRAINT FK_BF1CD3C3C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE version DROP FOREIGN KEY FK_BF1CD3C3C18272');
        $this->addSql('ALTER TABLE version ADD CONSTRAINT FK_BF1CD3C3C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
    }
}
