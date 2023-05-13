<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210305084146 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add linked_transaction index';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('DROP INDEX IF EXISTS transactions.uniq_a422ac289a181539');
        $this->addSql('CREATE INDEX idx_linked_transaction ON transactions.t_transaction (linked_transaction)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX IF EXISTS transactions.idx_linked_transaction');
        $this->addSql('CREATE INDEX uniq_a422ac289a181539 ON transactions.t_transaction (linked_transaction)');
    }
}
