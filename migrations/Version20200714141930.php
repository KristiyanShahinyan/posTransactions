<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200714141930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Rename `merchant_transaction` to `remote_service_transaction`';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('alter table transactions.t_transaction rename column merchant_transaction to remote_service_transaction');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('alter table transactions.t_transaction rename column remote_service_transaction to merchant_transaction');
    }
}
