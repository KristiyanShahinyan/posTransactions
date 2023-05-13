<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210315101500 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add sca_type to transaction';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction ADD sca_type INT NOT NULL default 1');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction DROP sca_type');
    }
}
