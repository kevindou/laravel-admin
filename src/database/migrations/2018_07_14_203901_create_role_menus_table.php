<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoleMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('role_menus', function(Blueprint $table)
		{
            $table->engine = 'InnoDB';
            $table->integer('id', true)->comment('主键ID');
			$table->integer('role_id')->unsigned()->comment('角色ID');
			$table->integer('menu_id')->unsigned()->comment('菜单ID');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('role_menus');
	}

}
