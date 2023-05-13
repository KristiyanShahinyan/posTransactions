<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220802143312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add compress column to reconciliation table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions.t_reconciliation ADD compress BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions.t_reconciliation DROP compress');
    }
}
