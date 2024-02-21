<?php 
namespace App\Service;
use Symfony\Component\String\UnicodeString;

class UtilsService {
    public static function cleanInputs(string $input): string {
        return htmlspecialchars(strip_tags(trim($input)),ENT_NOQUOTES);
    }
    /**
     * fonction qui test si une chaine match un regex
     * 
     * @param string $string chaine à tester
     * @param string $regex regex
     * @return bool retourne true si la chaine matche le regex, sinon false
     */
    public static function testRegex(string $string, string $regex): bool
    {
        //chaine à tester
        $string = new UnicodeString($string);
        //si le password possède 12 caractères minumum, lettre minuscule, majuscule et nombre
        if ($string->match($regex)) {
            return true;
        } else {
            return false;
        }
    }
}