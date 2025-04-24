<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424144812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $storeProcHasHolidayOverlap = <<<EOT
CREATE OR REPLACE FUNCTION has_holiday_overlap(
    start_date DATE,
    end_date DATE,
    ref_employee INT
)
RETURNS BOOLEAN
LANGUAGE plpgsql
AS \$\$
DECLARE
    found_overlap BOOLEAN;
BEGIN
    SELECT EXISTS (
        SELECT 1
        FROM request r
        JOIN holiday h ON h.id = r.holidays_id
        JOIN employee e ON e.id = r.employee_id
        WHERE GREATEST(0, (LEAST(h.date_end, end_date) - GREATEST(h.date_start, start_date) + 1)) > 0
          AND employee_id = ref_employee
    )
    INTO found_overlap;

    RETURN found_overlap;
END;
\$\$;
EOT;

        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql($storeProcHasHolidayOverlap);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP FUNCTION IF EXISTS has_holiday_overlap');
    }
}
