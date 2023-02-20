<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217110602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FAD4CCE3F86');
        $this->addSql('DROP INDEX IDX_51C88FAD4CCE3F86 ON prestation');
        $this->addSql('ALTER TABLE prestation DROP rdv_id');
        $this->addSql('ALTER TABLE rdv ADD prestations_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F868BE96D0D FOREIGN KEY (prestations_id) REFERENCES prestation (id)');
        $this->addSql('CREATE INDEX IDX_10C31F868BE96D0D ON rdv (prestations_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestation ADD rdv_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD4CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rdv (id)');
        $this->addSql('CREATE INDEX IDX_51C88FAD4CCE3F86 ON prestation (rdv_id)');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F868BE96D0D');
        $this->addSql('DROP INDEX IDX_10C31F868BE96D0D ON rdv');
        $this->addSql('ALTER TABLE rdv DROP prestations_id');
    }
}
