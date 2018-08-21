<?php


namespace mody\smsprovider\traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

trait DatabaseConfig
{

    protected function configProviderTable()
    {
        $check = Schema::hasTable('sms_providers');
        if (!$check) {
            Schema::defaultStringLength(191);
            Schema::create('sms_providers', function (Blueprint $table) {
                $table->increments('id');
                $table->string('company_name');
                $table->string('api_url')->unique();
//                $table->string('username')->nullable();
//                $table->string('password')->nullable();
                $table->string('http_method');
                $table->string('destination_attr');
                $table->string('message_attr');
                $table->boolean('unicode')->default(false);
                $table->string('success_code');
                $table->boolean('default');
                $table->integer('user_id')->nullable();
                $table->integer('group_id')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    protected function configAdditionalParams()
    {
        $check = Schema::hasTable('sms_provider_additional_params');
        if (!$check) {
            Schema::defaultStringLength(191);
            Schema::create('sms_provider_additional_params', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('sms_provider_id')->unsigned();

                $table->foreign('sms_provider_id')->references('id')
                    ->on('sms_providers')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

                $table->string('parameter');
                $table->string('value')->nullable();
                $table->integer('user_id')->nullable();
                $table->integer('group_id')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    protected function configMessagesTable()
    {
        $check = Schema::hasTable('sms_provider_messages');
        if (!$check) {
            Schema::defaultStringLength(191);
            Schema::create('sms_provider_messages', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('sms_provider_id')->unsigned();

                $table->foreign('sms_provider_id')->references('id')
                    ->on('sms_providers')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

                $table->text('message')->nullable();
                $table->string('client_number');
//                $table->integer('client_id')->unsigned();
                $table->integer('user_id')->nullable();
                $table->integer('group_id')->nullable();
                $table->boolean('status');
                $table->text('status_code');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    protected function configSavedMessages()
    {
        $check = Schema::hasTable('sms_direct_messages');
        if (!$check) {
            Schema::defaultStringLength(191);
            Schema::create('sms_direct_messages', function (Blueprint $table) {
                $table->increments('id');

                $table->string('message_type');
                $table->text('message')->nullable();
                $table->integer('user_id')->nullable();
                $table->integer('group_id')->nullable();
                $table->boolean('status');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    protected function configTrackMessages()
    {
        $check = Schema::hasTable('sms_provider_track_activity');
        if (!$check) {
            Schema::defaultStringLength(191);
            Schema::create('sms_provider_track_activity', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('sms_provider_id')->unsigned();
                $table->foreign('sms_provider_id')->references('id')
                    ->on('sms_providers')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

                $table->string('type');
                $table->integer('message_id')->nullable();
                $table->integer('user_id')->nullable();
                $table->integer('group_id')->nullable();
                $table->text('description')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }
}
