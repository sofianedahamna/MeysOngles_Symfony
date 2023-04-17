<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307114854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_option (id INT AUTO_INCREMENT NOT NULL, taille VARCHAR(255) NOT NULL, forme VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_option_product (product_option_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_98D76DF4C964ABE2 (product_option_id), INDEX IDX_98D76DF44584665A (product_id), PRIMARY KEY(product_option_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_option_product ADD CONSTRAINT FK_98D76DF4C964ABE2 FOREIGN KEY (product_option_id) REFERENCES product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_option_product ADD CONSTRAINT FK_98D76DF44584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_option_product DROP FOREIGN KEY FK_98D76DF4C964ABE2');
        $this->addSql('ALTER TABLE product_option_product DROP FOREIGN KEY FK_98D76DF44584665A');
        $this->addSql('DROP TABLE product_option');
        $this->addSql('DROP TABLE product_option_product');
    }
}
