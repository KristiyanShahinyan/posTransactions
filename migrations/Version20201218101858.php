<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201218101858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add field processor';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction ADD processor VARCHAR(255)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction DROP processor');
    }
}
