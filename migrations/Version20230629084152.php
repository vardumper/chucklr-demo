<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230629084152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chuckle (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(1000) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chuckle_user (chuckle_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_34BAA98DCB0643F3 (chuckle_id), INDEX IDX_34BAA98DA76ED395 (user_id), PRIMARY KEY(chuckle_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chuckle_user ADD CONSTRAINT FK_34BAA98DCB0643F3 FOREIGN KEY (chuckle_id) REFERENCES chuckle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chuckle_user ADD CONSTRAINT FK_34BAA98DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chuckle_user DROP FOREIGN KEY FK_34BAA98DCB0643F3');
        $this->addSql('ALTER TABLE chuckle_user DROP FOREIGN KEY FK_34BAA98DA76ED395');
        $this->addSql('DROP TABLE chuckle');
        $this->addSql('DROP TABLE chuckle_user');
    }
}
