<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251212102030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resto_plat (resto_id INT NOT NULL, plat_id INT NOT NULL, INDEX IDX_7FA703FD2978E8D1 (resto_id), INDEX IDX_7FA703FDD73DB560 (plat_id), PRIMARY KEY(resto_id, plat_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE resto_plat ADD CONSTRAINT FK_7FA703FD2978E8D1 FOREIGN KEY (resto_id) REFERENCES resto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resto_plat ADD CONSTRAINT FK_7FA703FDD73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plat_resto DROP FOREIGN KEY FK_1929D3CD2978E8D1');
        $this->addSql('ALTER TABLE plat_resto DROP FOREIGN KEY FK_1929D3CDD73DB560');
        $this->addSql('DROP TABLE plat_resto');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plat_resto (plat_id INT NOT NULL, resto_id INT NOT NULL, INDEX IDX_1929D3CDD73DB560 (plat_id), INDEX IDX_1929D3CD2978E8D1 (resto_id), PRIMARY KEY(plat_id, resto_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE plat_resto ADD CONSTRAINT FK_1929D3CD2978E8D1 FOREIGN KEY (resto_id) REFERENCES resto (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plat_resto ADD CONSTRAINT FK_1929D3CDD73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resto_plat DROP FOREIGN KEY FK_7FA703FD2978E8D1');
        $this->addSql('ALTER TABLE resto_plat DROP FOREIGN KEY FK_7FA703FDD73DB560');
        $this->addSql('DROP TABLE resto_plat');
    }
}
