<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20220714101940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add order_reference column.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction ADD order_reference VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction DROP order_reference');
    }
}
