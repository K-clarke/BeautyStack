<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190530165513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE style_tag (style_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_3F8FA755BACD6074 (style_id), INDEX IDX_3F8FA755BAD26311 (tag_id), PRIMARY KEY(style_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE style_tag ADD CONSTRAINT FK_3F8FA755BACD6074 FOREIGN KEY (style_id) REFERENCES style (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE style_tag ADD CONSTRAINT FK_3F8FA755BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE style DROP FOREIGN KEY FK_33BDB86ABAD26311');
        $this->addSql('DROP INDEX IDX_33BDB86ABAD26311 ON style');
        $this->addSql('ALTER TABLE style DROP tag_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE style_tag');
        $this->addSql('ALTER TABLE style ADD tag_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE style ADD CONSTRAINT FK_33BDB86ABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_33BDB86ABAD26311 ON style (tag_id)');
    }
}
