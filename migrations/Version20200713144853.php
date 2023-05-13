<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200713144853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove stale fields from transaction table.';
    }

    public function up(Schema $schema) : void
    {
        $sql = <<<SQL
        alter table transactions.t_transaction 
            drop column user_email,
            drop column user_type,
            drop column product,
            drop column recipient_token,
            drop column recipient_type,
            drop column recipient_email,
            drop column recipient_phone,
            drop column fee,
            drop column user_amount,
            drop column user_currency,
            drop column recipient_amount,
            drop column recipient_currency,
            drop column fx_rate,
            drop column receiver_transaction_type,
            drop column platform,
            drop column bank_token,
            drop column user_balance,
            drop column receiver_balance,
            drop column transaction_payment_type,
            drop column pos_transaction_description,
            drop column pos_status_transaction_description,
            drop column additional_indicator_ecommerce,
            drop column additional_options,
            drop column pos_code_condition,
            drop column pos_transaction_link_id,
            drop column pos_transaction_proc_code,
            drop column pos_transaction_fee_id,
            drop column pos_transaction_txn_date,
            drop column pos_transaction_post_date,
            drop column pos_transaction_txn_time,
            drop column pos_transaction_loc_date,
            drop column pos_transaction_avl_balance,
            drop column pos_transaction_dom_fee_fixed,
            drop column pos_transaction_dom_fee_rate,
            drop column pos_transaction_non_dom_fee_fixed,
            drop column pos_transaction_non_dom_fee_rate,
            drop column pos_transaction_fx_fee_fixed,
            drop column pos_transaction_fx_fee_rate,
            drop column pos_transaction_other_fee_fixed,
            drop column pos_transaction_other_fee_rate,
            drop column pos_transaction_note,
            drop column block_transaction_key,
            drop column balance_before,
            drop column balance_after,
            drop column deb_or_cred,
            drop column trn_description,
            drop column token_unique_reference,
            drop column transaction_client_info,
            drop column pending_reason,
            drop column is_admin_transaction,
            drop column admin_user,
            drop column is_recurring,
            drop column pos_auth_id,
            drop column external_response;
SQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema) : void
    {
        $sql = <<<SQL
        alter table transactions.t_transaction  
            add column user_email                         varchar(100)   default NULL::character varying,
            add column user_type                          smallint       default 1,
            add column product                            varchar(255)   default NULL::character varying,
            add column recipient_token                    varchar(100)   default NULL::character varying,
            add column recipient_type                     smallint,
            add column recipient_email                    varchar(255)   default NULL::character varying,
            add column recipient_phone                    varchar(80)    default NULL::character varying,
            add column fee                                numeric(10, 2) default 0.00,
            add column user_amount                        numeric(10, 2) default NULL::numeric,
            add column user_currency                      varchar(255)   default NULL::character varying,
            add column recipient_amount                   numeric(10, 2) default NULL::numeric,
            add column recipient_currency                 varchar(255)   default NULL::character varying,
            add column fx_rate                            numeric(10, 6) default NULL::numeric,
            add column receiver_transaction_type          smallint,
            add column platform                           varchar(255)   default NULL::character varying,
            add column bank_token                         varchar(255)   default NULL::character varying,
            add column user_balance                       varchar(255)   default NULL::character varying,
            add column receiver_balance                   varchar(255)   default NULL::character varying,
            add column transaction_payment_type           varchar(30)    default NULL::character varying,
            add column pos_transaction_description        varchar(255)   default NULL::character varying,
            add column pos_status_transaction_description varchar(255)   default NULL::character varying,
            add column additional_indicator_ecommerce     varchar(50)    default NULL::character varying,
            add column additional_options                 varchar(50)    default NULL::character varying,
            add column pos_code_condition                 varchar(10)    default NULL::character varying,
            add column pos_transaction_link_id            varchar(40)    default NULL::character varying,
            add column pos_transaction_proc_code          varchar(40)    default NULL::character varying,
            add column pos_transaction_fee_id             varchar(40)    default NULL::character varying,
            add column pos_transaction_txn_date           date,
            add column pos_transaction_post_date          date,
            add column pos_transaction_txn_time           varchar(20)    default NULL::character varying,
            add column pos_transaction_loc_date           date,
            add column pos_transaction_avl_balance        numeric(10, 2) default NULL::numeric,
            add column pos_transaction_dom_fee_fixed      numeric(10, 2) default NULL::numeric,
            add column pos_transaction_dom_fee_rate       numeric(10, 2) default NULL::numeric,
            add column pos_transaction_non_dom_fee_fixed  numeric(10, 2) default NULL::numeric,
            add column pos_transaction_non_dom_fee_rate   numeric(10, 2) default NULL::numeric,
            add column pos_transaction_fx_fee_fixed       numeric(10, 2) default NULL::numeric,
            add column pos_transaction_fx_fee_rate        numeric(10, 2) default NULL::numeric,
            add column pos_transaction_other_fee_fixed    numeric(10, 2) default NULL::numeric,
            add column pos_transaction_other_fee_rate     numeric(10, 2) default NULL::numeric,
            add column pos_transaction_note               varchar(255)   default NULL::character varying,
            add column block_transaction_key              varchar(100)   default NULL::character varying,
            add column balance_before                     numeric(10, 2) default NULL::numeric,
            add column balance_after                      numeric(10, 2) default NULL::numeric,
            add column deb_or_cred                        smallint,
            add column trn_description                    varchar(500)   default NULL::character varying,
            add column token_unique_reference             varchar(100)   default NULL::character varying,
            add column transaction_client_info            text,
            add column pending_reason                     varchar(30)    default NULL::character varying,
            add column is_admin_transaction               boolean,
            add column admin_user                         varchar(60)    default NULL::character varying,
            add column is_recurring                       boolean        default false,
            add column pos_auth_id                        integer,
            add column external_response                  json;
SQL;
        $this->addSql($sql);

        $setNotNullConstraint = <<<SQL
        alter table transactions.t_transaction 
            alter column user_type set not null,
            alter column fee set not null,
            alter column is_recurring set not null;
SQL;

        $this->addSql($setNotNullConstraint);
    }
}
