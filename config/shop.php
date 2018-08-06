<?php



return [

    /**
     * Aliasses
     *
     */
    'route_prefix'              => 'shop',

    /**
     * If you want all your product ID to be encrypted, tet below to true
     * The package will auto decrypt your ID's
     */
    'routes_encrypted'          => true,

    /**
     * Your route middlewares
     * Remove the web if you use laravel 5.3 <*
     *
     */
    'middleware'                => ['web'],

    /**
     * Shop settings model
     */
    'setting-model'                => \ACFBentveld\Shop\Models\ShopSetting::class,

    /**
     * Shipping models
     */
    'shipping-model'                => \ACFBentveld\Shop\Models\ShopShipping::class,

    /**
     * Cart models
     */
    'cart-model'                => \ACFBentveld\Shop\Models\ShopCart::class,
    'user-model'                => \App\Models\User::class,

    /**
     * Order models
     *
     */
    'order-model'               => \ACFBentveld\Shop\Models\ShopOrder::class,
    'order-status-model'        => \ACFBentveld\Shop\Models\ShopOrderStatus::class,
    'order-data-model'          => \ACFBentveld\Shop\Models\ShopOrderData::class,

    /**
     * Product models
     */
    'product-model'             => \ACFBentveld\Shop\Models\ShopProduct::class,
    'product-status-model'      => \ACFBentveld\Shop\Models\ShopProductStatus::class,
    'product-details-model'     => \ACFBentveld\Shop\Models\ShopProductDetail::class,
    'product-images-model'      => \ACFBentveld\Shop\Models\ShopProductImages::class,
    'product-data-model'        => \ACFBentveld\Shop\Models\ShopProductData::class,
    'product-attributes-model'  => \ACFBentveld\Shop\Models\ShopProductAttributes::class,
    'product-options-model'     => \ACFBentveld\Shop\Models\ShopProductOption::class,

    /**
     * Option models
     */
    'option-model'              => \ACFBentveld\Shop\Models\ShopOption::class,
    'option-values-model'       => \ACFBentveld\Shop\Models\ShopOptionValue::class,
    'option-types-model'        => \ACFBentveld\Shop\Models\ShopOptionType::class,

    /**
     * Categories models
     */
    'category-model'            => \ACFBentveld\Shop\Models\ShopCategory::class,

    /**
     * Mail model
     */
    'mail-model'                => \ACFBentveld\Shop\Models\ShopMail::class,

    /**
     * Product details in procents
     * the % mark is not required
     */
    'product_tax'               => '6%',

    /**
     * Language
     *
     * Support multiple languages
     * If set to false the default will be lang_id 1
     */
    'multi_languages'           => true,
    'language-model'            => \App\Models\Language::class,

    /**
     * Prices
     * If you want to auto format the doubles set below to true
     */
    'price_format'              => true,
    'price_decimals'            => 2,
    'price_decimals_1'          => ',', // 10,00
    'price_decimals_2'          => '.', // 1.000,00
    'price_symbol'              => '€'


];
