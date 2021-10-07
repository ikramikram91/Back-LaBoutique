<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211007004744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE order_line');
        $this->addSql('DROP TABLE order_line_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_line (id INT AUTO_INCREMENT NOT NULL, ordered_id INT NOT NULL, INDEX IDX_D46EAF7EAA60395A (ordered_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE order_line_product (order_ligne_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_E1B07246BBBB6CC7 (order_ligne_id), INDEX IDX_E1B072464584665A (product_id), PRIMARY KEY(order_ligne_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_D46EAF7EAA60395A FOREIGN KEY (ordered_id) REFERENCES ordered (id)');
    }
}
