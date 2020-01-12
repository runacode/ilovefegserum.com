<?php

KformConfig::setConfig(array(
    "isWordpress" => false,
    "apiLoginId" => "os_api",
    "apiPassword" => 'p@$$w0rd123123',
    "authString" => "39871422501d77894e0069d6646e810f",
    "autoUpdate_allowedIps" => array("80.248.30.132"),
    "campaignId" => 4,
    "resourceDir" => "resources/"));


/*
!---------------------------------IMPORTANT-----------------------------------!

Documentation:

	-Full documentation on landing pages can be found at

Auto-Update Feature:

	-The auto-update feature will automatically update settings on your landing page
	when you make changes to your campaign within the konnektive CRM. Use this feature
	to keep your landing page up-to-date concerning new coupons / shipping options
	and product changes.

	-To use the campaign auto-update feature, the apache or ngix user
	(depending on your httpd software) must have write access to this file

	-If you are not using the auto-update feature, you will need to manually
	replace this file after making changes to the campaign

!---------------------------------IMPORTANT-----------------------------------!
*/
class KFormConfig
{

	public $isWordpress = false;
	public $apiLoginId = '';
	public $apiPassword = '';
	public $resourceDir;
	public $baseDir;


	public $mobileRedirectUrl;
	public $desktopRedirectUrl;


	public $continents;
	public $countries;
	public $coupons;
	public $currencySymbol;
	public $insureShipPrice;
	public $landerType;
	public $offers;
	public $upsells;
	public $products;
	public $shipProfiles;
	public $states;
	public $taxes;
	public $termsOfService;
	public $webPages;

	static $instance = NULL;
	static $options;
	static $campaignData;
	// class constructor to set the variable values

	static function setConfig($options)
	{
		self::$options = $options;
	}

	public function __construct()
	{
		if(!empty(self::$instance))
			throw new Exception("cannot recreated KFormConfig");

		foreach((array) self::$options as $k=>$v)
			$this->$k = $v;

		if($this->isWordpress)
		{
			$options = get_option('konnek_options');
			foreach((array)$options as $k=>$v)
				$this->$k = $v;

			$data = json_decode(get_option('konnek_campaign_data'));
			foreach($data as $k=>$v)
				$this->$k = $v;
		}
		elseif(!empty(self::$campaignData))
		{
			if(json_decode(self::$campaignData) === NULL)
			{
				echo 'JSON in config.php is broken!';
				die;
			}
			else
				$data = (array)json_decode(self::$campaignData);


			foreach($data as $k=>$v)
				$this->$k = $v;
		}

		self::$instance = $this;


	}
}

/*
!---------------------------------IMPORTANT-----------------------------------!

	ABSOLUTELY DO NOT EDIT BELOW THIS LINE

!---------------------------------IMPORTANT-----------------------------------!
*/
$requestUri = $_SERVER['REQUEST_URI'];
$baseFile = basename(__FILE__);

if($_SERVER['REQUEST_METHOD']=='POST' && strstr($requestUri,$baseFile))
{

	$authString = filter_input(INPUT_POST,'authString',FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
	if(empty($authString))
		die(); //exit silently, don't want people to know that this file processes api requests if they are just sending random posts at it


	$remoteIp = $_SERVER['REMOTE_ADDR'];
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
		  $remoteIp =  $_SERVER["HTTP_CF_CONNECTING_IP"];

	$allowedIps = KFormConfig::$options['autoUpdate_allowedIps'];
	if(!in_array($remoteIp,$allowedIps))
		die("ERROR: Invalid IP Address. Please confirm that the Konnektive IP Address is in the allowedIps array.");
	if($authString != KFormConfig::$options['authString'])
		die("ERROR: Could not authenticate authString. Please re-download code package and replace config file on your server.");

	$data = filter_input(INPUT_POST,'data');
	$data = trim($data);
	$data = utf8_encode($data);
	$decoded = json_decode($data);
	if($decoded != NULL)
	{
		$file = fopen(__FILE__,'r');
		if(empty($file))
			die("ERROR: File not writable");

		$new_file = '';

		while($line = fgets($file))
		{
			$new_file .= $line;

			if(strpos($line,"/*[DYNAMIC-DATA-TOKEN]") === 0)
				break;
		}
		fclose($file);

		$new_file .= "KFormConfig::\$campaignData = '$data';".PHP_EOL;
		$ret = file_put_contents(__FILE__,$new_file);


		if(is_int($ret))
			die("SUCCESS");
		else
			die("ERROR: File not writable");
	}
	else
	{
		die("ERROR: what data");
	}
}

/*[DYNAMIC-DATA-TOKEN] do not remove */
KFormConfig::$campaignData = '{
    "countries": {
        "GB": "United Kingdom"
    },
    "states": {
        "GB": {
            "ABE": "Aberdeen City",
            "ABD": "Aberdeenshire",
            "ANS": "Angus",
            "ANT": "Antrim",
            "ARD": "Ards",
            "AGB": "Argyll and Bute",
            "ARM": "Armagh",
            "BLA": "Ballymena",
            "BLY": "Ballymoney",
            "BNB": "Banbridge",
            "BDG": "Barking and Dagenham",
            "BNE": "Barnet",
            "BNS": "Barnsley",
            "BAS": "Bath and North East Somerset",
            "BBO": "Bedford Borough",
            "BDF": "Bedfordshire",
            "BFS": "Belfast",
            "BEX": "Bexley",
            "BIR": "Birmingham",
            "BBD": "Blackburn with Darwen",
            "BPL": "Blackpool",
            "BGW": "Blaenau Gwent",
            "BOL": "Bolton",
            "BMH": "Bournemouth",
            "BRC": "Bracknell Forest",
            "BRD": "Bradford",
            "BEN": "Brent",
            "BGE": "Bridgend",
            "BNH": "Brighton and Hove",
            "BST": "Bristol, City of",
            "BRY": "Bromley",
            "BUC": "Buckingham",
            "BKM": "Buckinghamshire",
            "BUR": "Bury",
            "CAY": "Caerphilly",
            "CLD": "Calderdale",
            "CAM": "Cambridgeshire",
            "CMD": "Camden",
            "CRF": "Cardiff",
            "CMN": "Carmarthenshire",
            "CKF": "Carrickfergus",
            "CSR": "Castlereagh",
            "CGN": "Ceredigion",
            "CHS": "Cheshire",
            "CHE": "Cheshire East",
            "CWC": "Cheshire West and Chester",
            "CLK": "Clackmannanshire",
            "CLR": "Coleraine",
            "CWY": "Conwy",
            "CKT": "Cookstown",
            "CON": "Cornwall",
            "COV": "Coventry",
            "CGV": "Craigavon",
            "CRY": "Croydon",
            "CMA": "Cumbria",
            "DAL": "Darlington",
            "DEN": "DenbighshireD",
            "DER": "Derby",
            "DBY": "Derbyshire",
            "DRY": "Derry",
            "DEV": "Devon",
            "DNC": "Doncaster",
            "DOR": "Dorset",
            "DOW": "Down",
            "DUD": "Dudley",
            "DGY": "Dumfries and Galloway",
            "DND": "Dundee City",
            "DGN": "Dungannon",
            "DUR": "Durham",
            "EAL": "Ealing",
            "EAY": "East Ayrshire",
            "EDU": "East Dunbartonshire",
            "ELN": "East Lothian",
            "ERW": "East Renfrewshire",
            "ERY": "East Riding of Yorkshire",
            "ESX": "East Sussex",
            "EDH": "Edinburgh, City of",
            "ELS": "Eilean Siar",
            "ENF": "Enfield",
            "ESS": "Essex",
            "FAL": "Falkirk",
            "FER": "Fermanagh",
            "FIF": "Fife",
            "FLN": "Flintshire",
            "GAT": "Gateshead",
            "GLG": "Glasgow City",
            "GLS": "Gloucestershire",
            "GLO": "Greater London",
            "GRE": "Greenwich",
            "GGY": "Guernsey",
            "GWN": "Gwynedd",
            "HCK": "Hackney",
            "HAL": "Halton",
            "HMF": "Hammersmith and Fulham",
            "HAM": "Hampshire",
            "HRY": "Haringey",
            "HRW": "Harrow",
            "HPL": "Hartlepool",
            "HAV": "Havering",
            "HEF": "Herefordshire, County of",
            "HRT": "Hertfordshire",
            "HTF": "Hertfordshire",
            "HLD": "Highland",
            "HIL": "Hillingdon",
            "HNS": "Hounslow",
            "IVC": "Inverclyde",
            "AGY": "Isle of Anglesey",
            "IOM": "Isle of Man",
            "IOW": "Isle of Wight",
            "IOS": "Isles of Scilly",
            "ISL": "Islington",
            "JEY": "Jersey",
            "KEC": "Kensington and Chelsea",
            "KEN": "Kent",
            "KHL": "Kingston upon Hull, City of",
            "KTT": "Kingston upon Thames",
            "KIR": "Kirklees",
            "KWL": "Knowsley",
            "LBH": "Lambeth",
            "LAN": "Lancashire",
            "LRN": "Larne",
            "LDS": "Leeds",
            "LCE": "Leicester",
            "LEC": "Leicestershire",
            "LEW": "Lewisham",
            "LMV": "Limavady",
            "LIN": "Lincolnshire",
            "LSB": "Lisburn",
            "LIV": "Liverpool",
            "LND": "London, City of",
            "LUT": "Luton",
            "MFT": "Magherafelt",
            "MAN": "Manchester",
            "MDW": "Medway",
            "MTY": "Merthyr Tydfil",
            "MRT": "Merton",
            "MDB": "Middlesbrough",
            "MLN": "Midlothian",
            "MIK": "Milton Keynes",
            "MON": "Monmouthshire",
            "MRY": "Moray",
            "MYL": "Moyle",
            "NTL": "Neath Port Talbot",
            "NET": "Newcastle upon Tyne",
            "NWM": "Newham",
            "NWP": "Newport",
            "NYM": "Newry and Mourne",
            "NTA": "Newtownabbey",
            "NFK": "Norfolk",
            "NAY": "North Ayrshire",
            "NDN": "North Down",
            "NEL": "North East Lincolnshire",
            "NLK": "North Lanarkshire",
            "NLN": "North Lincolnshire",
            "NSM": "North Somerset",
            "NTY": "North Tyneside",
            "NYK": "North Yorkshire",
            "NTH": "Northamptonshire",
            "NBL": "Northumberland",
            "NGM": "Nottingham",
            "NTT": "Nottinghamshire",
            "OLD": "Oldham",
            "OMH": "Omagh",
            "ORK": "Orkney Islands",
            "OXF": "Oxfordshire",
            "PEM": "Pembrokeshire",
            "PKN": "Perth and Kinross",
            "PTE": "Peterborough",
            "PLY": "Plymouth",
            "POL": "Poole",
            "POR": "Portsmouth",
            "POW": "Powys",
            "RDG": "Reading",
            "RDB": "Redbridge",
            "RCC": "Redcar and Cleveland",
            "RFW": "Renfrewshire",
            "RCT": "Rhondda, Cynon, Ta",
            "RIC": "Richmond upon Thames",
            "RCH": "Rochdale",
            "ROT": "Rotherham",
            "RUT": "Rutland",
            "SLF": "Salford",
            "SAW": "Sandwell",
            "SCB": "Scottish Borders, The",
            "SFT": "Sefton",
            "SHF": "Sheffield",
            "ZET": "Shetland Islands",
            "SHR": "Shropshire",
            "SLG": "Slough",
            "SOL": "Solihull",
            "SOM": "Somerset",
            "SAY": "South Ayrshire",
            "SGC": "South Gloucestershire",
            "SLK": "South Lanarkshire",
            "STY": "South Tyneside",
            "SYK": "South Yorkshire",
            "STH": "Southampton",
            "SOS": "Southend-on-Sea",
            "SWK": "Southwark",
            "SHN": "St. Helens",
            "STS": "Staffordshire",
            "STG": "Stirling",
            "SKP": "Stockport",
            "STT": "Stockton-on-Tees",
            "STE": "Stoke-on-Trent",
            "STB": "Strabane",
            "SFK": "Suffolk",
            "SND": "Sunderland",
            "SRY": "Surrey",
            "STN": "Sutton",
            "SWA": "Swansea",
            "SWD": "Swindon",
            "TAM": "Tameside",
            "TFW": "Telford and Wrekin",
            "THR": "Thurrock",
            "TOB": "Torbay",
            "TOF": "Torfaen",
            "TWH": "Tower Hamlets",
            "TRF": "Trafford",
            "VGL": "Vale of Glamorgan, T",
            "WKF": "Wakefield",
            "WLL": "Walsall",
            "WFT": "Waltham Forest",
            "WND": "Wandsworth",
            "WRT": "Warrington",
            "WAR": "Warwickshire",
            "WBK": "West Berkshire",
            "WDU": "West Dunbartonshire",
            "WLN": "West Lothian",
            "WMD": "West Midlands",
            "WSX": "West Sussex",
            "WYK": "West Yorkshire",
            "WSM": "Westminster",
            "WGN": "Wigan",
            "WIL": "Wiltshire",
            "WNM": "Windsor and Maidenhead",
            "WRL": "Wirral",
            "WOK": "Wokingham",
            "WLV": "Wolverhampton",
            "WOC": "Worcester",
            "WOR": "Worcestershire",
            "WRX": "Wrexham",
            "YOR": "York"
        }
    },
    "currencySymbol": "\u00a3",
    "shipOptions": [],
    "coupons": [],
    "products": [],
    "webPages": {
        "catalogPage": {
            "disableBack": 0,
            "url": "https:\/\/feg-uk.com\/"
        },
        "checkoutPage": {
            "disableBack": 0,
            "url": "https:\/\/feg-uk.com\/checkout.php",
            "autoImportLead": 1,
            "productId": null,
            "requireSig": 0,
            "sigType": 0,
            "cardinalAuth": 0,
            "paayApiKey": null
        },
        "thankyouPage": {
            "disableBack": 0,
            "url": "https:\/\/feg-uk.com\/thankyou.php",
            "createAccountDialog": 0,
            "reorderUrl": null,
            "allowReorder": 0
        },
        "upsellPage1": {
            "disableBack": 1,
            "url": "https:\/\/feg-uk.com\/upsell1.php",
            "createAccountDialog": 0,
            "requirePayInfo": 0,
            "productId": 52,
            "replaceProductId": null
        },
        "upsellPage2": {
            "disableBack": 1,
            "url": "https:\/\/feg-uk.com\/upsell2.php",
            "createAccountDialog": 0,
            "requirePayInfo": 0,
            "productId": 51,
            "replaceProductId": null
        },
        "upsellPage3": {
            "disableBack": 1,
            "url": "https:\/\/feg-uk.com\/upsell3.php",
            "createAccountDialog": 0,
            "requirePayInfo": 0,
            "productId": 53,
            "replaceProductId": null
        },
        "productDetails": {
            "url": "product-details.php"
        }
    },
    "landerType": "CART",
    "googleTrackingId": "UA-155623562-6",
    "enableFraudPlugin": 0,
    "autoTax": 0,
    "taxServiceId": null,
    "companyName": "optin_solutions_llc",
    "offers": {
        "49": {
            "productId": 49,
            "name": "Feg Serum - Eyelash Enhancer",
            "description": "*No description available",
            "imagePath": "https:\/\/feg-uk.com\/resources\/images\/smain-small.jpg",
            "imageId": 1,
            "price": "11.97",
            "shipPrice": "0.00",
            "category": "FEG"
        },
        "50": {
            "productId": 50,
            "name": "Feg Serum - Eyelash Enhancer - Free",
            "description": "*No description available",
            "imagePath": "https:\/\/feg-uk.com\/resources\/images\/smain-small.jpg",
            "imageId": 1,
            "price": "0.00",
            "shipPrice": "0.00",
            "category": "FEG"
        }
    },
    "upsells": {
        "51": {
            "productId": 51,
            "name": "Feg Serum - Eyelash Enhancer - Free Gift",
            "description": "*No description available",
            "imagePath": "https:\/\/feg-uk.com\/resources\/images\/upsell1.jpg",
            "imageId": 1,
            "price": "4.95",
            "shipPrice": "0.00",
            "category": "FEG"
        },
        "52": {
            "productId": 52,
            "name": "FEG - EyeBrown (2pcs - 2 months of treatment)",
            "description": "*No description available",
            "imagePath": "https:\/\/feg-uk.com\/resources\/images\/upsell2.jpg",
            "imageId": 2,
            "price": "9.95",
            "shipPrice": "0.00",
            "category": "FEG"
        },
        "53": {
            "productId": 53,
            "name": "Silicone Make-Up Sponge",
            "description": "*No description available",
            "imagePath": "https:\/\/feg-uk.com\/resources\/images\/upsell3.jpg",
            "imageId": 3,
            "price": "4.95",
            "shipPrice": "0.00",
            "category": "FEG"
        }
    },
    "shipProfiles": [],
    "continents": {
        "GB": "EU"
    },
    "paypal": {
        "paypalBillerId": 6
    }
}';
