<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200920225306 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD user_id INT NOT NULL, ADD travel_date_id INT NOT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE79D4817A FOREIGN KEY (travel_date_id) REFERENCES travel_date (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDEA76ED395 ON booking (user_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE79D4817A ON booking (travel_date_id)');
        $this->addSql('ALTER TABLE bookmark ADD user_id INT NOT NULL, ADD travel_id INT NOT NULL');
        $this->addSql('ALTER TABLE bookmark ADD CONSTRAINT FK_DA62921DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bookmark ADD CONSTRAINT FK_DA62921DECAB15B3 FOREIGN KEY (travel_id) REFERENCES travel (id)');
        $this->addSql('CREATE INDEX IDX_DA62921DA76ED395 ON bookmark (user_id)');
        $this->addSql('CREATE INDEX IDX_DA62921DECAB15B3 ON bookmark (travel_id)');
        $this->addSql('ALTER TABLE destination ADD destination_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE destination ADD CONSTRAINT FK_3EC63EAAAB75B3EB FOREIGN KEY (destination_type_id) REFERENCES destination_type (id)');
        $this->addSql('CREATE INDEX IDX_3EC63EAAAB75B3EB ON destination (destination_type_id)');
        $this->addSql('ALTER TABLE image_destination ADD destination_id INT NOT NULL');
        $this->addSql('ALTER TABLE image_destination ADD CONSTRAINT FK_F1107524816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id)');
        $this->addSql('CREATE INDEX IDX_F1107524816C6140 ON image_destination (destination_id)');
        $this->addSql('ALTER TABLE image_transport ADD transport_id INT NOT NULL');
        $this->addSql('ALTER TABLE image_transport ADD CONSTRAINT FK_BE29FA7A9909C13F FOREIGN KEY (transport_id) REFERENCES transport (id)');
        $this->addSql('CREATE INDEX IDX_BE29FA7A9909C13F ON image_transport (transport_id)');
        $this->addSql('ALTER TABLE image_travel ADD travel_id INT NOT NULL');
        $this->addSql('ALTER TABLE image_travel ADD CONSTRAINT FK_82163506ECAB15B3 FOREIGN KEY (travel_id) REFERENCES travel (id)');
        $this->addSql('CREATE INDEX IDX_82163506ECAB15B3 ON image_travel (travel_id)');
        $this->addSql('ALTER TABLE itinerary ADD destination_id INT NOT NULL, ADD transport_id INT NOT NULL, ADD travel_id INT NOT NULL');
        $this->addSql('ALTER TABLE itinerary ADD CONSTRAINT FK_FF2238F6816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id)');
        $this->addSql('ALTER TABLE itinerary ADD CONSTRAINT FK_FF2238F69909C13F FOREIGN KEY (transport_id) REFERENCES transport (id)');
        $this->addSql('ALTER TABLE itinerary ADD CONSTRAINT FK_FF2238F6ECAB15B3 FOREIGN KEY (travel_id) REFERENCES travel (id)');
        $this->addSql('CREATE INDEX IDX_FF2238F6816C6140 ON itinerary (destination_id)');
        $this->addSql('CREATE INDEX IDX_FF2238F69909C13F ON itinerary (transport_id)');
        $this->addSql('CREATE INDEX IDX_FF2238F6ECAB15B3 ON itinerary (travel_id)');
        $this->addSql('ALTER TABLE payment ADD booking_id INT DEFAULT NULL, ADD saved_payment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DC7FAF653 FOREIGN KEY (saved_payment_id) REFERENCES saved_payment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840D3301C60 ON payment (booking_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840DC7FAF653 ON payment (saved_payment_id)');
        $this->addSql('ALTER TABLE review ADD travel_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6ECAB15B3 FOREIGN KEY (travel_id) REFERENCES travel (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_794381C6ECAB15B3 ON review (travel_id)');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
        $this->addSql('ALTER TABLE saved_payment ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE saved_payment ADD CONSTRAINT FK_E7294B9AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E7294B9AA76ED395 ON saved_payment (user_id)');
        $this->addSql('ALTER TABLE support_ticket ADD user_id INT DEFAULT NULL, ADD ticket_category_id INT NOT NULL');
        $this->addSql('ALTER TABLE support_ticket ADD CONSTRAINT FK_1F5A4D53A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE support_ticket ADD CONSTRAINT FK_1F5A4D537ED69B9D FOREIGN KEY (ticket_category_id) REFERENCES ticket_category (id)');
        $this->addSql('CREATE INDEX IDX_1F5A4D53A76ED395 ON support_ticket (user_id)');
        $this->addSql('CREATE INDEX IDX_1F5A4D537ED69B9D ON support_ticket (ticket_category_id)');
        $this->addSql('ALTER TABLE transport ADD transport_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE transport ADD CONSTRAINT FK_66AB212E519B4C62 FOREIGN KEY (transport_type_id) REFERENCES transport_type (id)');
        $this->addSql('CREATE INDEX IDX_66AB212E519B4C62 ON transport (transport_type_id)');
        $this->addSql('ALTER TABLE travel ADD promotion_id INT DEFAULT NULL, ADD depart_from_id INT NOT NULL');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCE139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCED451F8D7 FOREIGN KEY (depart_from_id) REFERENCES depart_from (id)');
        $this->addSql('CREATE INDEX IDX_2D0B6BCE139DF194 ON travel (promotion_id)');
        $this->addSql('CREATE INDEX IDX_2D0B6BCED451F8D7 ON travel (depart_from_id)');
        $this->addSql('ALTER TABLE travel_date ADD travel_id INT NOT NULL');
        $this->addSql('ALTER TABLE travel_date ADD CONSTRAINT FK_61C6D4D7ECAB15B3 FOREIGN KEY (travel_id) REFERENCES travel (id)');
        $this->addSql('CREATE INDEX IDX_61C6D4D7ECAB15B3 ON travel_date (travel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA76ED395');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE79D4817A');
        $this->addSql('DROP INDEX IDX_E00CEDDEA76ED395 ON booking');
        $this->addSql('DROP INDEX IDX_E00CEDDE79D4817A ON booking');
        $this->addSql('ALTER TABLE booking DROP user_id, DROP travel_date_id');
        $this->addSql('ALTER TABLE bookmark DROP FOREIGN KEY FK_DA62921DA76ED395');
        $this->addSql('ALTER TABLE bookmark DROP FOREIGN KEY FK_DA62921DECAB15B3');
        $this->addSql('DROP INDEX IDX_DA62921DA76ED395 ON bookmark');
        $this->addSql('DROP INDEX IDX_DA62921DECAB15B3 ON bookmark');
        $this->addSql('ALTER TABLE bookmark DROP user_id, DROP travel_id');
        $this->addSql('ALTER TABLE destination DROP FOREIGN KEY FK_3EC63EAAAB75B3EB');
        $this->addSql('DROP INDEX IDX_3EC63EAAAB75B3EB ON destination');
        $this->addSql('ALTER TABLE destination DROP destination_type_id');
        $this->addSql('ALTER TABLE image_destination DROP FOREIGN KEY FK_F1107524816C6140');
        $this->addSql('DROP INDEX IDX_F1107524816C6140 ON image_destination');
        $this->addSql('ALTER TABLE image_destination DROP destination_id');
        $this->addSql('ALTER TABLE image_transport DROP FOREIGN KEY FK_BE29FA7A9909C13F');
        $this->addSql('DROP INDEX IDX_BE29FA7A9909C13F ON image_transport');
        $this->addSql('ALTER TABLE image_transport DROP transport_id');
        $this->addSql('ALTER TABLE image_travel DROP FOREIGN KEY FK_82163506ECAB15B3');
        $this->addSql('DROP INDEX IDX_82163506ECAB15B3 ON image_travel');
        $this->addSql('ALTER TABLE image_travel DROP travel_id');
        $this->addSql('ALTER TABLE itinerary DROP FOREIGN KEY FK_FF2238F6816C6140');
        $this->addSql('ALTER TABLE itinerary DROP FOREIGN KEY FK_FF2238F69909C13F');
        $this->addSql('ALTER TABLE itinerary DROP FOREIGN KEY FK_FF2238F6ECAB15B3');
        $this->addSql('DROP INDEX IDX_FF2238F6816C6140 ON itinerary');
        $this->addSql('DROP INDEX IDX_FF2238F69909C13F ON itinerary');
        $this->addSql('DROP INDEX IDX_FF2238F6ECAB15B3 ON itinerary');
        $this->addSql('ALTER TABLE itinerary DROP destination_id, DROP transport_id, DROP travel_id');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D3301C60');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DC7FAF653');
        $this->addSql('DROP INDEX UNIQ_6D28840D3301C60 ON payment');
        $this->addSql('DROP INDEX UNIQ_6D28840DC7FAF653 ON payment');
        $this->addSql('ALTER TABLE payment DROP booking_id, DROP saved_payment_id');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6ECAB15B3');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('DROP INDEX IDX_794381C6ECAB15B3 ON review');
        $this->addSql('DROP INDEX IDX_794381C6A76ED395 ON review');
        $this->addSql('ALTER TABLE review DROP travel_id, DROP user_id');
        $this->addSql('ALTER TABLE saved_payment DROP FOREIGN KEY FK_E7294B9AA76ED395');
        $this->addSql('DROP INDEX IDX_E7294B9AA76ED395 ON saved_payment');
        $this->addSql('ALTER TABLE saved_payment DROP user_id');
        $this->addSql('ALTER TABLE support_ticket DROP FOREIGN KEY FK_1F5A4D53A76ED395');
        $this->addSql('ALTER TABLE support_ticket DROP FOREIGN KEY FK_1F5A4D537ED69B9D');
        $this->addSql('DROP INDEX IDX_1F5A4D53A76ED395 ON support_ticket');
        $this->addSql('DROP INDEX IDX_1F5A4D537ED69B9D ON support_ticket');
        $this->addSql('ALTER TABLE support_ticket DROP user_id, DROP ticket_category_id');
        $this->addSql('ALTER TABLE transport DROP FOREIGN KEY FK_66AB212E519B4C62');
        $this->addSql('DROP INDEX IDX_66AB212E519B4C62 ON transport');
        $this->addSql('ALTER TABLE transport DROP transport_type_id');
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCE139DF194');
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCED451F8D7');
        $this->addSql('DROP INDEX IDX_2D0B6BCE139DF194 ON travel');
        $this->addSql('DROP INDEX IDX_2D0B6BCED451F8D7 ON travel');
        $this->addSql('ALTER TABLE travel DROP promotion_id, DROP depart_from_id');
        $this->addSql('ALTER TABLE travel_date DROP FOREIGN KEY FK_61C6D4D7ECAB15B3');
        $this->addSql('DROP INDEX IDX_61C6D4D7ECAB15B3 ON travel_date');
        $this->addSql('ALTER TABLE travel_date DROP travel_id');
    }
}
