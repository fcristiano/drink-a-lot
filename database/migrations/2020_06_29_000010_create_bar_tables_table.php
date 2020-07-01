<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBarTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bar_tables', function(Blueprint $table) {
            $table->id();
            $table->tinyInteger('number');
            $table->tinyInteger('default_seats');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });

        $this->fill();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bar_tables');
    }

    protected function fill()
    {
        $dateString = (new \DateTime())->format('Y-m-d H:i:s');

        DB::beginTransaction();

        DB::table('bar_tables')->insert([
            'number' => 1,
            'default_seats' => 4,
            'created_at' => $dateString,
            'updated_at' => $dateString
        ]);

        DB::table('bar_tables')->insert([
            'number' => 2,
            'default_seats' => 6,
            'created_at' => $dateString,
            'updated_at' => $dateString
        ]);

        DB::table('bar_tables')->insert([
            'number' => 3,
            'default_seats' => 4,
            'created_at' => $dateString,
            'updated_at' => $dateString
        ]);

        DB::table('bar_tables')->insert([
            'number' => 4,
            'default_seats' => 8,
            'created_at' => $dateString,
            'updated_at' => $dateString
        ]);

        DB::commit();
    }
}
