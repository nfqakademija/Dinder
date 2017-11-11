<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171111164213 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item_match (id INT AUTO_INCREMENT NOT NULL, item_owner_id INT DEFAULT NULL, item_respondent_id INT DEFAULT NULL, status INT NOT NULL, INDEX IDX_50FCDED51231A932 (item_owner_id), INDEX IDX_50FCDED5222C65C2 (item_respondent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_match ADD CONSTRAINT FK_50FCDED51231A932 FOREIGN KEY (item_owner_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_match ADD CONSTRAINT FK_50FCDED5222C65C2 FOREIGN KEY (item_respondent_id) REFERENCES item (id)');
        $this->addSql('DROP TABLE `match`');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `match` (id INT AUTO_INCREMENT NOT NULL, item_owner_id INT DEFAULT NULL, item_respondent_id INT DEFAULT NULL, status INT NOT NULL, INDEX IDX_7A5BC5051231A932 (item_owner_id), INDEX IDX_7A5BC505222C65C2 (item_respondent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC5051231A932 FOREIGN KEY (item_owner_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC505222C65C2 FOREIGN KEY (item_respondent_id) REFERENCES item (id)');
        $this->addSql('DROP TABLE item_match');
    }
}
