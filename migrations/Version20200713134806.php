<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200713134806 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove transaction fee table.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('drop table if exists transactions.t_transaction_fee;');

    }

    public function down(Schema $schema) : void
    {
        $sql = <<<SQL
create table transactions.t_transaction_fee
(
    id              integer  not null
        constraint t_transaction_fee_pkey
            primary key,
    transaction     integer  not null
        constraint fk_18cf7f4b723705d1
            references transactions.t_transaction,
    fee_type        smallint not null,
    fee_amount      numeric(10, 6) default NULL::numeric,
    fee_currency    varchar(3)     default NULL::character varying,
    fee_description varchar(255)   default NULL::character varying
);
SQL;
        $this->addSql($sql);
        $this->addSql('create index idx_18cf7f4b723705d1 on transactions.t_transaction_fee (transaction);');
    }
}
