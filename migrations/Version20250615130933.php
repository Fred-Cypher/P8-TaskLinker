<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250615130933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tags_projects DROP FOREIGN KEY FK_36052FBD1EDE0F55
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tags_projects DROP FOREIGN KEY FK_36052FBD8D7B4FB4
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tags_projects
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projects ADD tags_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A48D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5C93B3A48D7B4FB4 ON projects (tags_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE tags_projects (tags_id INT NOT NULL, projects_id INT NOT NULL, INDEX IDX_36052FBD8D7B4FB4 (tags_id), INDEX IDX_36052FBD1EDE0F55 (projects_id), PRIMARY KEY(tags_id, projects_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tags_projects ADD CONSTRAINT FK_36052FBD1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tags_projects ADD CONSTRAINT FK_36052FBD8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A48D7B4FB4
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_5C93B3A48D7B4FB4 ON projects
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projects DROP tags_id
        SQL);
    }
}
