<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603083746 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categories CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD works_id INT NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AF6CB822A FOREIGN KEY (works_id) REFERENCES works (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6AF6CB822A ON images (works_id)');
        $this->addSql('ALTER TABLE messages ADD users_id INT NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E9667B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_DB021E9667B3B43D ON messages (users_id)');
        $this->addSql('ALTER TABLE quotes ADD services_id INT NOT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE quotes ADD CONSTRAINT FK_A1B588C5AEF5A6C1 FOREIGN KEY (services_id) REFERENCES services (id)');
        $this->addSql('CREATE INDEX IDX_A1B588C5AEF5A6C1 ON quotes (services_id)');
        $this->addSql('ALTER TABLE services ADD categories_id INT NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E169A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_7332E169A21214B7 ON services (categories_id)');
        $this->addSql('ALTER TABLE users CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE works ADD users_id INT NOT NULL, ADD categories_id INT NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT FK_F6E5024367B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT FK_F6E50243A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_F6E5024367B3B43D ON works (users_id)');
        $this->addSql('CREATE INDEX IDX_F6E50243A21214B7 ON works (categories_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categories CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AF6CB822A');
        $this->addSql('DROP INDEX IDX_E01FBE6AF6CB822A ON images');
        $this->addSql('ALTER TABLE images DROP works_id, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E9667B3B43D');
        $this->addSql('DROP INDEX IDX_DB021E9667B3B43D ON messages');
        $this->addSql('ALTER TABLE messages DROP users_id, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE quotes DROP FOREIGN KEY FK_A1B588C5AEF5A6C1');
        $this->addSql('DROP INDEX IDX_A1B588C5AEF5A6C1 ON quotes');
        $this->addSql('ALTER TABLE quotes DROP services_id, CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E169A21214B7');
        $this->addSql('DROP INDEX IDX_7332E169A21214B7 ON services');
        $this->addSql('ALTER TABLE services DROP categories_id, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE users CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE works DROP FOREIGN KEY FK_F6E5024367B3B43D');
        $this->addSql('ALTER TABLE works DROP FOREIGN KEY FK_F6E50243A21214B7');
        $this->addSql('DROP INDEX IDX_F6E5024367B3B43D ON works');
        $this->addSql('DROP INDEX IDX_F6E50243A21214B7 ON works');
        $this->addSql('ALTER TABLE works DROP users_id, DROP categories_id, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\'');
    }
}
