<?php

namespace ACFBentveld\Shop\Controllers;

use Illuminate\Http\Request;
use Auth;
/**
 * Init the cart controller
 *
 */
class ShopController
{

    /**
     * The language ID
     *
     * @var int
     */
    protected $lang  = 1;

    /**
     * Set language ID
     *
     * @param type $lang_id
     * @return $this
     */
    public function setLang($lang_id)
    {
        $this->lang = $lang_id;
        return $this;
    }

    /**
     * Return the current language
     *
     * @return int
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Return all the shop settings
     *
     * @return \ACFBentveld\Shop\Models\ShopShipping
     */
    public function getSettings()
    {
        $model = config('shop.setting-model');
        $settings = $model::all();
        return $settings;
    }

    /**
     * Return a single setting by its name
     *
     * @param string $name
     * @return \ACFBentveld\Shop\Models\ShopShipping
     */
    public function getSettingByName(string $name)
    {
        $model = config('shop.setting-model');
        $setting = $model::whereName($name)->first();
        return $setting;
    }

    /**
     * Return the shops products
     *
     * @return \ACFBentveld\Shop\Controllers\model
     */
    public function getProducts()
    {
        $model = config('shop.product-model');
        return $model::where('status_id', 1);
    }

    /**
     * Return the categories model
     *
     * @return \ACFBentveld\Shop\Controllers\model
     */
    public function getCategories()
    {
        $model = config('shop.category-model');
        $categories = (config('shop.multi_languages'))?$model::where('lang_id', $this->lang):new $model();
        return $categories;
    }

    /**
     * Return all the available shippings
     *
     * @return \ACFBentveld\Shop\Models\ShopShipping
     */
    public function getShippings()
    {
        $model = config('shop.shipping-model');
        $shippings = $model::all();
        return $shippings;
    }

    /**
     * Return all options
     *
     * @return \ACFBentveld\Shop\Models\ShopOption
     */
    public function getOptions()
    {
        $model = config('shop.option-model');
        $options = $model::all();
        return $options;
    }

    /**
     * Return list of all countries
     *
     * @return array
     */
    public function getCountries()
    {
        return array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Nederland", "Nederlandse Antillen", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
    }

}