<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217142657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rdv_prestation (rdv_id INT NOT NULL, prestation_id INT NOT NULL, INDEX IDX_868354CA4CCE3F86 (rdv_id), INDEX IDX_868354CA9E45C554 (prestation_id), PRIMARY KEY(rdv_id, prestation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rdv_prestation ADD CONSTRAINT FK_868354CA4CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rdv (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rdv_prestation ADD CONSTRAINT FK_868354CA9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F868BE96D0D');
        $this->addSql('DROP INDEX IDX_10C31F868BE96D0D ON rdv');
        $this->addSql('ALTER TABLE rdv DROP prestations_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rdv_prestation DROP FOREIGN KEY FK_868354CA4CCE3F86');
        $this->addSql('ALTER TABLE rdv_prestation DROP FOREIGN KEY FK_868354CA9E45C554');
        $this->addSql('DROP TABLE rdv_prestation');
        $this->addSql('ALTER TABLE rdv ADD prestations_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F868BE96D0D FOREIGN KEY (prestations_id) REFERENCES prestation (id)');
        $this->addSql('CREATE INDEX IDX_10C31F868BE96D0D ON rdv (prestations_id)');
    }
}
