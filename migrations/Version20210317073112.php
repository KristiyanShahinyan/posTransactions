<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210317073112 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add field retrieval_reference_number';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction ADD retrieval_reference_number VARCHAR(30) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction DROP retrieval_reference_number');
    }
}
