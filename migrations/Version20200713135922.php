<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200713135922 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove transaction refund table.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('drop table if exists transactions.t_transaction_refund;');
    }

    public function down(Schema $schema) : void
    {
        $sql = <<<SQL
create table transactions.t_transaction_refund
(
    id             serial         not null
        constraint t_transaction_refund_pkey
            primary key,
    transaction_id integer
        constraint fk_499ba7832fc0cb0f
            references transactions.t_transaction,
    amount         numeric(10, 2) not null,
    currency       varchar(3)     not null,
    refund_date    timestamp(0)   not null,
    execute_user   varchar(100) default NULL::character varying
);
SQL;
        $this->addSql($sql);
        $this->addSql('create index idx_499ba7832fc0cb0f on transactions.t_transaction_refund (transaction_id);');
    }
}
