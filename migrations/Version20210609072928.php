<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210609072928 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Change scale to 4 digits for amount';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction ALTER amount TYPE NUMERIC(10, 4)');
        $this->addSql('ALTER TABLE transactions.t_transaction ALTER tip_amount TYPE NUMERIC(10, 4)');
        $this->addSql('ALTER TABLE transactions.t_transaction ALTER refundable_amount TYPE NUMERIC(10, 4)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE transactions.t_transaction ALTER amount TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE transactions.t_transaction ALTER tip_amount TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE transactions.t_transaction ALTER refundable_amount TYPE NUMERIC(10, 2)');
    }
}
