<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250615131704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A48D7B4FB4
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_5C93B3A48D7B4FB4 ON projects
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projects DROP tags_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tags ADD projects_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tags ADD CONSTRAINT FK_6FBC94261EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6FBC94261EDE0F55 ON tags (projects_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tags DROP FOREIGN KEY FK_6FBC94261EDE0F55
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6FBC94261EDE0F55 ON tags
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tags DROP projects_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projects ADD tags_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A48D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5C93B3A48D7B4FB4 ON projects (tags_id)
        SQL);
    }
}
