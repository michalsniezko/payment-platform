<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20250813090949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $users = $schema->createTable('users');
        $idColumn = $users->addColumn('id', Types::INTEGER)->setAutoincrement(true);
        $users->addColumn('user_name', Types::STRING)->setLength(50);
        $users->addColumn('created_at', Types::DATETIME_MUTABLE);

        $primaryKeyConstraint = PrimaryKeyConstraint::editor()->setColumnNames($idColumn->getObjectName())->create();
        $users->addPrimaryKeyConstraint($primaryKeyConstraint);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}
