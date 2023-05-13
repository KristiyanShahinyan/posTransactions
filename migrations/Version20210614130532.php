<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210614130532 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adds receiving_institution_identification_code field';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction ADD receiving_institution_identification_code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction DROP receiving_institution_identification_code');
    }
}
