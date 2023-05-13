<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221031102814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add app_details column to transaction table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction ADD app_details JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction DROP app_details');
    }
}
