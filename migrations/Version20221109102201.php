<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221109102201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add surcharge_amount to t_transaction';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction ADD surcharge_amount NUMERIC(10, 4) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction DROP surcharge_amount');
    }
}
