<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class IssConfig extends BaseConfig {

    public $modules = [
        ["level" => "4", "name" => "Mtrade", "icon" => "trade.png", "label" => "mTrade"],
        ["level" => "1", "name" => "Home", "icon" => "home.png", "label" => "Главная"],
        ["level" => "1", "name" => "Trade", "icon" => "trade.png", "label" => "Торговля"],
        ["level" => "3", "name" => "Accounts", "icon" => "accounts.png", "label" => "Бухгалтерия"],
        ["level" => "3", "name" => "Marketing", "icon" => "marketing.png", "label" => "Маркетинг"],
        ["level" => "1", "name" => "Stock", "icon" => "stock.png", "label" => "Товары"],
        ["level" => "2", "name" => "Events", "icon" => "events.png", "label" => "Задания"],
        ["level" => "1", "name" => "Reports", "icon" => "reports.png", "label" => "Отчеты"],
        ["level" => "2", "name" => "Pref", "icon" => "pref.png", "label" => "Настройки"],
        ["level" => "2", "name" => "Data", "icon" => "data.png", "label" => "Данные"]
    ];
    public $permited_tables = [
        ["table_name" => "prod_list", "table_title" => "Справочник товаров", "level" => 2, "editable" => 1],
        ["table_name" => "price_list", "table_title" => "Справочник цен", "level" => 2, "editable" => 1],
        ["table_name" => "stat_sell_analyse", "table_title" => "Стат. анализ продаж", "level" => 4],
        ["table_name" => "client_list", "table_title" => "Клиентская база", "level" => 3],
        ["table_name" => "log_list", "table_title" => "Лог", "level" => 3, "orderby" => "entry_id DESC"],
        ["table_name" => "acc_article_list", "table_title" => "Статьи проводок", "level" => 3, "editable" => 1]
    ];

}
