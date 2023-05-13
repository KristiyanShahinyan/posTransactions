<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220503090140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds store_token to transactions table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction ADD store_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction DROP store_token');
    }
}
