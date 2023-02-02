<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery.ip_log', function (Blueprint $table) {
            $table->string('region')->nullable(false);
            $table->string('type')->nullable(true);
            $table->integer('price_for_client')->nullable(false)->default(0);
            $table->integer('markup_percent')->nullable(false)->default(0);
            $table->timestamps();
            $table->string('country')->nullable(true);
            $table->float('costs')->nullable(true);
            $table->integer('deliveries')->nullable(true);
            $table->string('group')->nullable(true);
            $table->integer('active')->nullable(true);
            $table->string('naselenniy_punkt')->nullable(true);
            $table->float('avg_cost')->nullable(true);
            $table->float('avg_margin_cost')->nullable(true);
            $table->float('markup')->nullable(true);
            $table->string('zone')->nullable(true);
            $table->integer('min')->nullable(true);
            $table->integer('max')->nullable(true);
            $table->string('month_year_of_delivery')->nullable(true);
            $table->integer('delivery_days')->nullable(true);
        });

        Schema::create('delivery.ip_log_operators', function (Blueprint $table) {
            $table->string('group')->nullable(false);
            $table->string('type')->nullable(false);
            $table->string('courier_1c')->nullable(false);
            $table->string('courier_name')->nullable(false);
            $table->string('active')->nullable(false);
            $table->timestamps();
        });

        Schema::create('delivery.ip_log_operators_inner', function (Blueprint $table) {
            $table->id();
            $table->integer('group')->nullable(false);
            $table->string('type')->nullable(false);
            $table->string('courier_1c')->nullable(false);
            $table->string('courier_name')->nullable(false);
            $table->boolean('is_active')->nullable(false);
            $table->timestamps();
        });

        Schema::create('delivery.ip_log_inner', function (Blueprint $table) {
            $table->id();
            $table->string('country')->nullable(false);
            $table->string('region')->nullable(true);
            $table->string('type')->nullable(true);
            $table->integer('price_for_client')->nullable(false)->default(0);
            $table->integer('markup_percent')->nullable(false)->default(0);
            $table->float('costs')->nullable(true);
            $table->integer('deliveries')->nullable(true);
            $table->integer('courier_group')->nullable(false);
            $table->boolean('is_active')->nullable(false)->default(false);
            $table->string('settlement')->nullable(true);
            $table->float('avg_cost')->nullable(true);
            $table->float('avg_margin_cost')->nullable(true);
            $table->float('markup')->nullable(true);
            $table->string('zone')->nullable(true);
            $table->integer('min')->nullable(true);
            $table->integer('max')->nullable(true);
            $table->string('month_year_of_delivery')->nullable(true);
            $table->integer('delivery_time')->nullable(true);
            $table->timestamps();
        });

        Schema::create('delivery.pvz', function (Blueprint $table) {
            $table->string('id')->unique()                     ->nullable(false)->comment('Уникальный идентификатор доставки');
            $table->boolean('marked')                          ->nullable(false)->comment('Пометка на удаление');
            $table->string('description', 255)           ->nullable(true)->comment('Описание доставки');
            $table->string('subdivision', 255)            ->nullable(true)->comment('Какой-то номер/кладр - уточнить');
            $table->string('county', 255)                 ->nullable(true)->comment('Видимо что-то связанное с местностью (респ./край итд)');
            $table->string('mode_of_operation', 255)     ->nullable(true)->comment('Время работы ПВЗ');
            $table->string('address', 255)               ->nullable(true)->comment('Адрес ПВЗ');
            $table->string('country', 255)               ->nullable(true)->comment('Страна');
            $table->string('region', 255)                ->nullable(true)->comment('Регион, область, округ итд');
            $table->string('phones', 255)                ->nullable(true)->comment('Телефон ПВЗ');
            $table->decimal('latitude', 10, 6)      ->nullable(true)->comment('Широта');
            $table->decimal('longitude', 10, 6)     ->nullable(true)->comment('Долгота');
            $table->boolean('without_a_pass')                   ->nullable(true)->comment('Неизвестно - уточнить');
            $table->boolean('stroller')                         ->nullable(true)->comment('Неизвестно - уточнить');
            $table->boolean('not_more_15_min')                  ->nullable(true)->comment('Неизвестно - уточнить');
            $table->string('adm_area', 255)               ->nullable(true)->comment('Район/метро');
            $table->string('marks', 255)                  ->nullable(true)->comment('Пометка местности(автобусная остановка, ориентир итд)');
            $table->boolean('dressing_rooms')                   ->nullable(true)->comment('Возможно наличие примерочных - уточнить');
            $table->text('directions')                          ->nullable(true)->comment('Пометка местности(автобусная остановка, ориентир итд)');
            $table->boolean('payment_on_receipt')               ->nullable(true)->comment('Неизвестно - уточнить');
            $table->boolean('terminal')                         ->nullable(true)->comment('Неизвестно - уточнить');
            $table->string('metro', 255)                 ->nullable(true)->comment('Ближайшее метро');
            $table->string('railway', 255)                ->nullable(true)->comment('Ориентир оставновки транспорта');
            $table->string('area', 255)                   ->nullable(true)->comment('Район');
            $table->string('code', 255)                   ->nullable(true)->comment('Видимо код доставки - уточнить');
            $table->decimal('max_amount', 10, 2)    ->nullable(true)->comment('Стоимость доставки');
            $table->string('delivery', 255)               ->nullable(true)->comment('Название доставки');
            $table->string('city', 255)                   ->nullable(true)->comment('Город');
            $table->string('cladr', 50)                  ->nullable(true)->comment('Кладр');
            $table->integer('index')                           ->nullable(true)->comment('Индекс');
            $table->string('error', 500)                 ->nullable(true)->comment('Описание ошибки. Поле пустое, если ошибки нет');
            $table->boolean('dadata_checked')                  ->nullable(true)->comment('Признак проверки через сервис DaData');
            $table->jsonb('dadata_json')                       ->nullable(true)->comment('JSON ответ сервиса DaData');
            $table->timestamps();
            $table->string('region_without_type')->nullable(true);
        });

        Schema::create('delivery.pvz_1c', function (Blueprint $table) {
            $table->string('id')->unique()                     ->nullable(false)->comment('Уникальный идентификатор доставки');
            $table->boolean('marked')                          ->nullable(false)->comment('Пометка на удаление');
            $table->string('description', 255)           ->nullable(true)->comment('Описание доставки');
            $table->string('subdivision', 255)            ->nullable(true)->comment('Какой-то номер/кладр - уточнить');
            $table->string('county', 255)                 ->nullable(true)->comment('Видимо что-то связанное с местностью (респ./край итд)');
            $table->string('mode_of_operation', 255)     ->nullable(true)->comment('Время работы ПВЗ');
            $table->string('address', 255)               ->nullable(true)->comment('Адрес ПВЗ');
            $table->string('phones', 255)                ->nullable(true)->comment('Телефон ПВЗ');
            $table->decimal('latitude', 10, 6)      ->nullable(true)->comment('Широта');
            $table->decimal('longitude', 10, 6)     ->nullable(true)->comment('Долгота');
            $table->boolean('without_a_pass')                   ->nullable(true)->comment('Неизвестно - уточнить');
            $table->boolean('stroller')                         ->nullable(true)->comment('Неизвестно - уточнить');
            $table->boolean('not_more_15_min')                  ->nullable(true)->comment('Неизвестно - уточнить');
            $table->string('adm_area', 255)               ->nullable(true)->comment('Район/метро');
            $table->string('marks', 255)                  ->nullable(true)->comment('Пометка местности(автобусная остановка, ориентир итд)');
            $table->boolean('dressing_rooms')                   ->nullable(true)->comment('Возможно наличие примерочных - уточнить');
            $table->text('directions')                          ->nullable(true)->comment('Пометка местности(автобусная остановка, ориентир итд)');
            $table->boolean('payment_on_receipt')               ->nullable(true)->comment('Неизвестно - уточнить');
            $table->boolean('terminal')                         ->nullable(true)->comment('Неизвестно - уточнить');
            $table->string('metro', 255)                 ->nullable(true)->comment('Ближайшее метро');
            $table->string('railway', 255)                ->nullable(true)->comment('Ориентир оставновки транспорта');
            $table->string('area', 255)                   ->nullable(true)->comment('Район');
            $table->string('code', 255)                   ->nullable(true)->comment('Видимо код доставки - уточнить');
            $table->decimal('max_amount', 10, 2)    ->nullable(true)->comment('Стоимость доставки');
            $table->string('delivery', 255)               ->nullable(true)->comment('Название доставки');
            $table->string('city', 255)                   ->nullable(true)->comment('Город');
            $table->string('cladr', 50)                  ->nullable(true)->comment('Кладр');
            $table->boolean('dadata_checked')                  ->nullable(true)->comment('Признак проверки через сервис DaData');
        });

        Schema::create('delivery.carriers', function (Blueprint $table) {
            $table->id();
            $table->string('carrier_id')->nullable(false);
            $table->string('carrier_name')->nullable(false);
            $table->boolean('is_active')->nullable(false);
            $table->boolean('is_pvz')->nullable(false);
            $table->boolean('is_banned')->nullable(false);
            $table->integer('range')->nullable(false);
            $table->integer('price')->nullable(false);
            $table->integer('fact_price')->nullable(false);
            $table->integer('delivery_time_min')->nullable(false);
            $table->integer('delivery_time_max')->nullable(false);
            $table->integer('min_weight')->nullable(false);
            $table->integer('max_weight')->nullable(false);
            $table->integer('min_amount')->nullable(false);
            $table->bigInteger('max_amount')->nullable(false);
            $table->float('coefficient')->nullable(false);
            $table->string('term')->nullable(false);
            $table->string('kladr')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP table IF EXISTS delivery.carriers');
        DB::unprepared('DROP table IF EXISTS delivery.pvz_1c');
        DB::unprepared('DROP table IF EXISTS delivery.pvz');
        DB::unprepared('DROP table IF EXISTS delivery.ip_log_inner');
        DB::unprepared('DROP table IF EXISTS delivery.ip_log_operators_inner');
        DB::unprepared('DROP table IF EXISTS delivery.ip_log_operators');
        DB::unprepared('DROP table IF EXISTS delivery.ip_log');
    }
};
