<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221127012757 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('ALTER TABLE tag CHANGE color color VARCHAR(255) NOT NULL');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('ALTER TABLE tag CHANGE color color VARCHAR(255) DEFAULT NULL');
	}
}
