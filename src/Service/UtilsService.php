<?php 
namespace App\Service;

class UtilsService {
    public static function cleanInputs(string $input): string {
        return htmlspecialchars(strip_tags(trim($input)),ENT_NOQUOTES);
    }
}