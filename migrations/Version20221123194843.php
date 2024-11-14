<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221123194843 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_389B7835E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE tag_task (tag_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_BC716493BAD26311 (tag_id), INDEX IDX_BC7164938DB60186 (task_id), PRIMARY KEY(tag_id, task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, todolist_id INT NOT NULL, title VARCHAR(255) NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, due_date DATE DEFAULT NULL, INDEX IDX_527EDB25AD16642A (todolist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE todolist (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_DD4DF6DB5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('ALTER TABLE tag_task ADD CONSTRAINT FK_BC716493BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
		$this->addSql('ALTER TABLE tag_task ADD CONSTRAINT FK_BC7164938DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
		$this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25AD16642A FOREIGN KEY (todolist_id) REFERENCES todolist (id)');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('ALTER TABLE tag_task DROP FOREIGN KEY FK_BC716493BAD26311');
		$this->addSql('ALTER TABLE tag_task DROP FOREIGN KEY FK_BC7164938DB60186');
		$this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25AD16642A');
		$this->addSql('DROP TABLE tag');
		$this->addSql('DROP TABLE tag_task');
		$this->addSql('DROP TABLE task');
		$this->addSql('DROP TABLE todolist');
	}
}
