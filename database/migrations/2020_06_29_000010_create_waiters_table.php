<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWaitersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waiters', function(Blueprint $table) {
            $table->id();
            $table->string('code', 7);
            $table->string('name', 30);
            $table->string('surname', 30);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('waiters');
    }

    protected function fill()
    {
        $dateString = (new \DateTime())->format('Y-m-d H:i:s');

        DB::beginTransaction();

        DB::table('waiters')->insert([
            'code' => 'wt-1111',
            'name' => 'Morris',
            'surname' => 'Doe',
            'created_at' => $dateString,
            'updated_at' => $dateString
        ]);

        DB::table('waiters')->insert([
            'code' => 'wt-2222',
            'name' => 'Lana',
            'surname' => 'Doe',
            'created_at' => $dateString,
            'updated_at' => $dateString
        ]);

        DB::table('waiters')->insert([
            'code' => 'wt-3333',
            'name' => 'Scarlett',
            'surname' => 'Johansson',
            'created_at' => $dateString,
            'updated_at' => $dateString
        ]);

        DB::commit();
    }
}