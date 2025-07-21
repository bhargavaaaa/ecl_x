<?php

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

function getCurrentUserId()
{
    return auth()->user()->id;
}

function isAdmin()
{
    return auth()->user()->hasRole('Admin');
}

function getCountries()
{
    return Country::pluck(DB::raw('CONCAT(UPPER(SUBSTRING(name,1,1)),LOWER(SUBSTRING(name,2))) AS name'),'id')->toArray();
}

function getStates($countryId = ''): array
{
    $state = State::query();
    if($countryId) {
        $state->where('country_id', $countryId);
    }
    return $state->pluck(DB::raw('CONCAT(UPPER(SUBSTRING(name,1,1)),LOWER(SUBSTRING(name,2))) AS name'),'id')->toArray();
}

function getCities($stateId = ''): array
{
    $city = City::query();
    if($stateId) {
        $city->where('state_id', $stateId);
    }
    return $city->pluck(DB::raw('CONCAT(UPPER(SUBSTRING(name,1,1)),LOWER(SUBSTRING(name,2))) AS name'),'id')->toArray();
}

function getState($id = ""): string
{
    $state = State::query();
    if($id) {
        $state->where('id', $id);
    }
    return ucwords($state->value('name'));
}

function getCity($id = ""): string
{
    $city = City::query();
    if($id) {
        $city->where('id', $id);
    }
    return ucwords($city->value('name'));
}

function getCreateButton($route = "javascript:;", $name = "", $class = "", $additional_html = "", $check_permission = ""): string
{
    if($check_permission && !hasPermissionTo($check_permission)) {
        return "";
    }

    return '<a href="'.$route.'" class="btn btn-alt-primary '.$class.'" '.$additional_html.'><i class="fa fa-plus" aria-hidden="true"></i> '.$name.'</a>';
}

function getDownloadButton($route = "javascript:;", $name = "", $class = "", $additional_html = "", $check_permission = ""): string
{
    if($check_permission && !hasPermissionTo($check_permission)) {
        return "";
    }

    return '<a href="'.$route.'" class="btn btn-alt-primary '.$class.'" '.$additional_html.'><i class="fa fa-print" aria-hidden="true"></i> '.$name.'</a>';
}

function getEditButton($route = "javascript:;", $class = "", $additional_html = "", $check_permission = ""): string
{
    if($check_permission && !hasPermissionTo($check_permission)) {
        return "";
    }

    return '<a href="'.$route.'" class="btn btn-sm btn-alt-success '.$class.'" '.$additional_html.'><i class="fa fa-pencil-alt"></i></a>';
}

function getDeleteButton($route = "javascript:;", $class = "", $additional_html = "", $check_permission = ""): string
{
    if($check_permission && !hasPermissionTo($check_permission)) {
        return "";
    }

    return '<a href="'.$route.'" class="btn btn-sm btn-alt-danger '.$class.'" '.$additional_html.'><i class="fa fa-times"></i></a>';
}

function getViewButton($route = "javascript:;", $class = "", $additional_html = "", $check_permission = ""): string
{
    if($check_permission && !hasPermissionTo($check_permission)) {
        return "";
    }

    return '<a href="'.$route.'" class="btn btn-sm btn-alt-info '.$class.'" '.$additional_html.'><i class="fa fa-eye"></i></a>';
}

function getExportButton($route = "javascript:;", $name = "", $class = "", $additional_html = "", $check_permission = ""): string
{
    if($check_permission && !hasPermissionTo($check_permission)) {
        return "";
    }

    return '<a href="'.$route.'" class="btn btn-outline-primary '.$class.'" '.$additional_html.'><i class="fa fa-file-export" aria-hidden="true"></i>&nbsp; '.$name.'</a>';
}

function getUserAvatar(): string
{
    if(auth()->check() && !empty(auth()->user()->avatar) && Storage::exists(auth()->user()->avatar)) {
        return Storage::url(auth()->user()->avatar);
    }

    return asset('media/avatars/avatar15.jpg');
}

function hasPermissionTo($permission): bool
{
    return (isAdmin() || auth()->user()->can($permission));
}

function deleteStoredImage($image): void
{
    if(!empty($image) && Storage::exists($image)) {
        Storage::delete($image);
    }
}

function deleteThumbnail($path): void
{
    if(!empty($path)) {
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $newFilename = $filename . '.' . $extension;

        Storage::disk('public')->delete('thumbnails/'.$newFilename);
    }
}

function copyThumbnail(string $path): void
{
    $filename = pathinfo($path, PATHINFO_FILENAME);
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $newFilename = $filename . '.' . $extension;

    if(!Storage::disk('public')->exists('thumbnails/' . $newFilename)) {
        $imageData = file_get_contents(storage_path('app/public/'.str_replace('public/', '', $path)));
        $image = imagecreatefromstring($imageData);
        $maxWidth = 500;
        $maxHeight = 625;
        $aspectRatio = imagesx($image) / imagesy($image);
        $newWidth = $maxWidth;
        $newHeight = $maxWidth / $aspectRatio;
        if ($newHeight > $maxHeight) {
            $newHeight = $maxHeight;
            $newWidth = $maxHeight * $aspectRatio;
        }
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($image), imagesy($image));
        ob_start();
        imagejpeg($newImage);
        $imageDataResized = ob_get_clean();

        Storage::disk('public')->put('thumbnails/' . $newFilename, $imageDataResized);

        imagedestroy($image);
        imagedestroy($newImage);
    }
}
function getStoredImage($image)
{
    if(!empty($image) && Storage::exists($image)) {
        return url(Storage::url($image));
    }

    return asset('media/various/no_image_available.jpg');
}

function convertToWords($number) {
    $ones = array(
        0 => 'Zero',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine'
    );

    $teens = array(
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen'
    );

    $tens = array(
        10 => 'Ten',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety'
    );

    $thousands = array(
        100 => 'Hundred',
        1000 => 'Thousand',
        100000 => 'Lakh',
        10000000 => 'Crore',
        1000000000 => 'Arab',
        100000000000 => 'Kharab'
    );

    $words = '';

    if ($number < 10) {
        $words = $ones[$number];
    } elseif ($number < 20) {
        $words = $teens[$number];
    } elseif ($number < 100) {
        $tensDigit = floor($number / 10) * 10;
        $remainder = $number % 10;
        $words = $tens[$tensDigit];
        if ($remainder > 0) {
            $words .= ' ' . $ones[$remainder];
        }
    } else {
        foreach (array_reverse($thousands, true) as $key => $value) {
            if ($number >= $key) {
                $base = floor($number / $key);
                $remainder = $number - ($base * $key);
                if ($base > 1 && $value !== 'Hundred') {
                    $words = convertToWords($base) . ' ' . $value . 's'; // pluralize if greater than 1
                } else {
                    $words = convertToWords($base) . ' ' . $value;
                }
                if ($remainder > 0) {
                    $words .= ' ' . convertToWords($remainder);
                }
                break;
            }
        }
    }

    return $words;
}

function isOrderOpenToDelete($order): bool
{
    if (empty($order->cutting_end_date) && empty($order->turning_end_date) && empty($order->milling_end_date) && empty($order->other_end_date) && empty($order->dispatch_end_date)) {
        return true;
    }

    return false;
}
