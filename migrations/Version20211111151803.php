<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211111151803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Make retrieval_reference_number column max 255 chars';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transactions.t_transaction ALTER retrieval_reference_number TYPE VARCHAR(255)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transactions.t_transaction ALTER retrieval_reference_number TYPE VARCHAR(36)');
    }
}
