<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211012195350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD6344584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_497DD6344584665A ON categorie (product_id)');
        $this->addSql('ALTER TABLE product DROP categorie_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD6344584665A');
        $this->addSql('DROP INDEX IDX_497DD6344584665A ON categorie');
        $this->addSql('ALTER TABLE categorie DROP product_id');
        $this->addSql('ALTER TABLE product ADD categorie_id INT NOT NULL');
    }
}
