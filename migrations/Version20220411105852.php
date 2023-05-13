<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220411105852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add reconciliation table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE transactions.t_reconciliation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE transactions.t_reconciliation (id INT NOT NULL, acquirer VARCHAR(255) DEFAULT NULL, batch INT NOT NULL, replace_pan BOOLEAN NOT NULL, options JSON DEFAULT NULL, last_generated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, affiliate VARCHAR(255) DEFAULT NULL, batch_size INT DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE transactions.t_reconciliation_id_seq CASCADE');
        $this->addSql('DROP TABLE transactions.t_reconciliation');
    }
}
