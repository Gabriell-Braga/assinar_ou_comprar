<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/*
 * Este arquivo é gerado automaticamente, Não edite-o diretamente.
 * Para gerar este arquivo execute o comando "php artisan app:make-seed".
 * O banco de dados contido aqui é criado ao executar o comando "php artisan app:deploy".
 * Arquivo gerado pela última vez em 19/03/2021 às 04:01:54
*/

class AutoSeeder extends Seeder
{

	public function run() {

		$schema = [
			'email_sents' => [
				'comment' => '',
				'fields' => [
					'id' => function($table) { $table->id(); },
					'to' => function($table) { $table->text('to')->nullable(); },
					'subject' => function($table) { $table->text('subject')->nullable(); },
					'body' => function($table) { $table->text('body')->nullable(); },
					'created_at' => function($table) { $table->nullableTimestamps(); },
					'updated_at' => function($table) { /* Gerado pela função nullableTimestamps() dentro de created_at */; },
				],
			],
			'emails' => [
				'comment' => '',
				'fields' => [
					'id' => function($table) { $table->id(); },
					'name' => function($table) { $table->string('name', 255)->nullable(); },
					'subject' => function($table) { $table->string('subject', 255)->nullable(); },
					'body' => function($table) { $table->text('body')->nullable(); },
					'params' => function($table) { $table->text('params')->nullable(); },
					'created_at' => function($table) { $table->nullableTimestamps(); },
					'updated_at' => function($table) { /* Gerado pela função nullableTimestamps() dentro de created_at */; },
				],
			],
			'migrations' => [
				'comment' => '',
				'fields' => [
					'id' => function($table) { $table->id(); },
					'migration' => function($table) { $table->string('migration', 255); },
					'batch' => function($table) { $table->integer('batch'); },
				],
			],
			'password_resets' => [
				'comment' => '',
				'fields' => [
					'email' => function($table) { $table->string('email', 255); },
					'token' => function($table) { $table->string('token', 255); },
					'created_at' => function($table) { $table->nullableTimestamps(); },
					'updated_at' => function($table) { /* Gerado pela função nullableTimestamps() dentro de created_at */; },
				],
			],
			'settings' => [
				'comment' => '',
				'fields' => [
					'id' => function($table) { $table->id(); },
					'name' => function($table) { $table->string('name', 255)->nullable(); },
					'value' => function($table) { $table->text('value')->nullable(); },
					'value_default' => function($table) { $table->text('value_default')->nullable(); },
					'description' => function($table) { $table->string('description', 255)->nullable(); },
					'help' => function($table) { $table->text('help')->nullable(); },
				],
			],
			'users' => [
				'comment' => '',
				'fields' => [
					'id' => function($table) { $table->id(); },
					'name' => function($table) { $table->string('name', 255)->nullable(); },
					'email' => function($table) { $table->string('email', 255)->nullable(); },
					'email_verified_at' => function($table) { $table->text('email_verified_at')->nullable(); },
					'password' => function($table) { $table->string('password', 255)->nullable(); },
					'remember_token' => function($table) { $table->string('remember_token', 100)->nullable(); },
					'whatsapp' => function($table) { $table->string('whatsapp', 20)->nullable(); },
					'meta' => function($table) { $table->text('meta')->nullable(); },
					'group' => function($table) { $table->string('group', 255)->nullable(); },
					'created_at' => function($table) { $table->nullableTimestamps(); },
					'updated_at' => function($table) { /* Gerado pela função nullableTimestamps() dentro de created_at */; },
				],
			],
		];

		foreach($schema as $table_name=>$table_data) {
			if (\Schema::hasTable($table_name)) {
				\Schema::table($table_name, function($table) use($table_name, $table_data) {
					foreach($table_data['fields'] as $column=>$callback) {
						if (\Schema::hasColumn($table_name, $column)) continue;
						call_user_func($callback, $table);
					}
				});
				\DB::statement("ALTER TABLE `$table_name` comment '{$table_data['comment']}'");
			}
			else {
				\Schema::create($table_name, function($table) use($table_name, $table_data) {
					foreach($table_data['fields'] as $column=>$callback) {
						call_user_func($callback, $table);
					}
				});
				\DB::statement("ALTER TABLE `$table_name` comment '{$table_data['comment']}'");
			}
		}
	}
}
