<?php



$locales = GetPrefLocale();
$languagefile = dirname(__FILE__). "/en.json";

$CurrentLocale = "en";
$lang = json_decode(file_get_contents($languagefile), true);
$languages['en'] = $lang;

$found = false;
if (isset($_REQUEST['lang'])) {
    if(isset($languages[$_REQUEST['lang']])) {
        $CurrentLocale = $_REQUEST['lang'];
        $found = true;
    }
} else {
    foreach (array_keys($locales) as $locale) {
        $languagefile = dirname(__FILE__). "/" . $locale . ".json";

        if (file_exists($languagefile)) {
            $lang = json_decode(file_get_contents($languagefile), true);
            $info = pathinfo($languagefile);
            $languages[$info['filename']] = $lang;
            $CurrentLocale = $locale;
            $found = true;
            break;
        }
    }


}
/*
if (!$found) {

    require dirname(__FILE__).'/../vendor/autoload.php';

    try {
        $reader = new GeoIp2\Database\Reader(dirname(__FILE__) . '/GeoLite2-City.mmdb');
        $record = $reader->city($_SERVER['REMOTE_ADDR']);

        switch ($record->country->isoCode) {
            case "US":
                $CurrentLocale = "en";
                break;
            case "FR":
                $CurrentLocale = "fr-FR";
                break;
            case "CA":
                $CurrentLocale = "en-CA";
                break;
            case "DE":
                $CurrentLocale = "de-DE";
                break;
            case "AT":
                $CurrentLocale = "de-at";
                break;
            case "AU":
                $CurrentLocale = "en-AU";
                break;
            case "GB":
                $CurrentLocale = "en-GB";
                break;
            case "IE":
                $CurrentLocale = "en-IE";
                break;
            case "NZ":
                $CurrentLocale = "en-NZ";
                break;
            case "IT":
                $CurrentLocale = "it-IT";
                break;
            case "NO":
                $CurrentLocale = "nb-NO";
                break;
            case "SE":
                $CurrentLocale = "sv-SE";
                break;
            default :
                $CurrentLocale = "en";
        }
    } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
        $CurrentLocale = "en";
    } catch (\MaxMind\Db\Reader\InvalidDatabaseException $e) {
        $CurrentLocale = "en";
    }
    $languagefile = dirname(__FILE__). "/" . $locale . ".json";
    $lang = json_decode(file_get_contents($languagefile), true);
    $info = pathinfo($languagefile);
    $languages[$info['filename']] = $lang;
}
*/
$CurrentLanguage = $languages[$CurrentLocale];

function T($str)
{
    global $CurrentLanguage,$CurrentLocale;
    if (!isset($CurrentLanguage[$str]) || strlen(trim($CurrentLanguage[$str])) == 0) {
        if (isset($_REQUEST['strict-lang'])) {

            ?>
            <h2> MISSING <?= $str; ?></h2>
            <?php
            exit;
        }
        return '';
    }
    return $CurrentLanguage[$str];
}

function GetPrefLocale()
{
    $prefLocales = array_reduce(
        explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']),
        function ($res, $el) {
            list($l, $q) = array_merge(explode(';q=', $el), [1]);
            $res[$l] = (float)$q;
            return $res;
        }, []);
    arsort($prefLocales);
    return $prefLocales;
}

?>

