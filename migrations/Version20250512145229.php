<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250512145229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE classroom (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, year INT NOT NULL, UNIQUE INDEX UNIQ_497D309D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE classroom_module (classroom_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_982CE2336278D5A8 (classroom_id), INDEX IDX_982CE233AFC2B591 (module_id), PRIMARY KEY(classroom_id, module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classroom_module ADD CONSTRAINT FK_982CE2336278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classroom_module ADD CONSTRAINT FK_982CE233AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student ADD classroom_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student ADD CONSTRAINT FK_B723AF336278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B723AF336278D5A8 ON student (classroom_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE student DROP FOREIGN KEY FK_B723AF336278D5A8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classroom_module DROP FOREIGN KEY FK_982CE2336278D5A8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classroom_module DROP FOREIGN KEY FK_982CE233AFC2B591
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE classroom
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE classroom_module
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B723AF336278D5A8 ON student
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student DROP classroom_id
        SQL);
    }
}
