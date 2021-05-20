<?php

use Illuminate\Database\Migrations\Migration;

class AddStoredProcedureGetParentCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE FUNCTION `GetAncestry` (GivenID INT) RETURNS VARCHAR(1024)
        DETERMINISTIC
        BEGIN
            DECLARE rv VARCHAR(1024);
            DECLARE cm CHAR(1);
            DECLARE ch INT;

            SET rv = '';
            SET cm = '';
            SET ch = GivenID;
            WHILE ch > 0 DO
                SELECT IFNULL(parent_cat_id,-1) INTO ch FROM
                (SELECT parent_cat_id FROM categories WHERE id = ch) A;
                IF ch > 0 THEN
                    SET rv = CONCAT(rv,cm,ch);
                    SET cm = ',';
                END IF;
            END WHILE;
            RETURN rv;
        END
        ";

        DB::unprepared("DROP FUNCTION IF EXISTS GetAncestry");
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP FUNCTION IF EXISTS GetAncestry");
    }
}
