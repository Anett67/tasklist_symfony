<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220326133050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25FF3475DB');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25FF3475DB FOREIGN KEY (tasklist_id) REFERENCES tasklist (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25FF3475DB');
        $this->addSql('ALTER TABLE task CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25FF3475DB FOREIGN KEY (tasklist_id) REFERENCES tasklist (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE task_status CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tasklist CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE theme theme VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
