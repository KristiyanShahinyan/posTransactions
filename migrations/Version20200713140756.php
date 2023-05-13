<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200713140756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove transaction service status table.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('drop table if exists transactions.t_transaction_service_status;');
    }

    public function down(Schema $schema) : void
    {
        $sql = <<<SQL
create table transactions.t_transaction_service_status
(
    id          serial   not null
        constraint t_transaction_service_status_pkey
            primary key,
    transaction integer  not null
        constraint fk_12c2b872723705d1
            references transactions.t_transaction,
    service     varchar(50)  default NULL::character varying,
    service_ref varchar(255) default NULL::character varying,
    status      smallint not null
);
SQL;
        $this->addSql($sql);
        $this->addSql('create index idx_12c2b872723705d1 on transactions.t_transaction_service_status (transaction);');
    }
}
