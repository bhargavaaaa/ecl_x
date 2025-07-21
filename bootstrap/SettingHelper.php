<?php
use App\Models\Setting;

function settings_wastage_value_show_sales_issue_print()
{
    $value = Setting::currentVendor()->where('name', 'wastage_value_show_sales_issue_print')->value('value') ?? config('settings.settings')['wastage_value_show_sales_issue_print']['default_value'];
    if ($value == '1') {
        return true;
    }

    return false;
}

function settings_purity_value_show_sales_issue_print()
{
    $value = Setting::currentVendor()->where('name', 'purity_value_show_sales_issue_print')->value('value') ?? config('settings.settings')['purity_value_show_sales_issue_print']['default_value'];
    if ($value == '1') {
        return true;
    }

    return false;
}

function settings_header_show_in_sales_issue_print()
{
    $value = Setting::currentVendor()->where('name', 'header_show_in_sales_issue_print')->value('value') ?? config('settings.settings')['header_show_in_sales_issue_print']['default_value'];
    if ($value == '1') {
        return true;
    }

    return false;
}

function settings_header_tag_sales_issue_print()
{
    $value = Setting::currentVendor()->where('name', 'header_tag_sales_issue_print')->value('value') ?? config('settings.settings')['header_tag_sales_issue_print']['default_value'];

    return $value;
}

function settings_print_account_details_sales_issue_print()
{
    $value = Setting::currentVendor()->where('name', 'print_account_details_sales_issue_print')->value('value') ?? config('settings.settings')['print_account_details_sales_issue_print']['default_value'];

    return $value;
}
