<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201130125123 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add field for the name of the local date timezone';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE transactions.t_transaction ADD timezone_name VARCHAR(255) DEFAULT 'UTC' NOT NULL");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction DROP timezone_name');
    }
}
