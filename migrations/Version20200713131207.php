<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200713131207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove the bank transaction table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('drop table if exists transactions.t_bank_transaction;');
    }

    public function down(Schema $schema) : void
    {
        $sql = <<<SQL
create table transactions.t_bank_transaction
(
    id                   serial       not null
        constraint t_bank_transaction_pkey
            primary key,
    transaction          integer      not null
        constraint fk_e874d40f723705d1
            references transactions.t_transaction,
    bank_account         varchar(100)   default NULL::character varying,
    iban                 varchar(100)   default NULL::character varying,
    account_no           varchar(100)   default NULL::character varying,
    sort_code            varchar(100)   default NULL::character varying,
    direction            varchar(10)  not null,
    status               integer      not null,
    receive_date         timestamp(0) not null,
    execute_date         timestamp(0)   default NULL::timestamp without time zone,
    execute_user         varchar(80)    default NULL::character varying,
    transaction_amount   numeric(10, 2) default NULL::numeric,
    original_amount      numeric(10, 2) default NULL::numeric,
    transaction_currency varchar(10)    default NULL::character varying,
    original_currency    varchar(10)    default NULL::character varying,
    reference            text,
    payload              text
);
SQL;

        $this->addSql($sql);
        $this->addSql('create index idx_e874d40f723705d1 on transactions.t_bank_transaction (transaction);');
    }
}
