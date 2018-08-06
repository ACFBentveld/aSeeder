<?php

namespace ACFBentveld\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProduct extends Model
{

    protected $format = true;

    protected $fillable = [
        'price', 'status_id', 'tax'
    ];

    /**
     * Get the product route attribute
     *
     * @return string
     */
    public function getRouteAttribute()
    {
        $route = $this->makeProductRoute($this->product->slug, $this->categories()->first());
        return $route;
    }

    /**
     * Create the products route
     *
     * @param type $route
     * @param type $category
     * @return string
     */
    public function makeProductRoute($route, $category)
    {
        if($category){
            $route = $category->slug.'/'.$route;
            if($category->category){
                $route = $this->makeProductRoute($route, $category->category);
            }
        }
        return $route;
    }


    /**
     * Format prices
     *
     * @param bollean $price
     * @return string
     */
    public function formatPrice($price)
    {
        return number_format($price, config('shop.price_decimals'), config('shop.price_decimals_1'), config('shop.price_decimals_2'));
    }

    /**
     * Return all products by lang_id
     *
     * @return hasMany
     */
    public function products()
    {
        return $this->hasMany(config('shop.product-data-model'), 'product_id');
    }

    /**
     * Return the product prices
     *
     * @return collection
     */
    public function getPrices($withAmount = false, $format = false)
    {
        $amount = ($withAmount)?$this->pivot->amount:1;
        $this->prices = new \stdClass();
        $this->prices->ex_tax       = $this->optionsPrice();
        $this->prices->tax          = ($this->prices->ex_tax / 100)  * $this->tax;
        $this->prices->total        = ($format)?
            $this->formatPrice(($this->prices->ex_tax + $this->prices->tax) * $amount):
            ($this->prices->ex_tax + $this->prices->tax) * $amount;
        return $this->prices;
    }

    /**
     * Return the attribute price
     *
     * @param type $price
     * @return double
     */
    public function getPriceAttribute($price)
    {
        if(config('shop.price_format') && $this->format){
            $price = ($price / 100) * (100 + $this->tax);
            return $this->formatPrice($price);
        }
        return $price;
    }

    /**
     * Return unformatted price
     *
     * @return double
     */
    public function price()
    {
        $this->format = false;
        $price = $this->price;
        $this->format = true;
        return $price;
    }

    /**
     * Return the product price inclusive with options
     *
     * @return double
     */
    public function getPriceWithOptionsAttribute()
    {
        $price = $this->optionsPrice();
        return $this->formatPrice($price);
    }

    /**
     * Get the product options
     *
     * @return collection
     */
    public function getOptions(bool $orderByOption = false)
    {
        if($this->pivot){
            if($this->pivot->options){
                return \Shop::option()->unserialize($this->pivot->options);
            }
            return collect([]);
        }
        if($orderByOption){
            return $this->orderOptions();
        }
        return $this->productoptions;
    }

    /**
     * Order options by option
     *
     * @return collection
     */
    public function orderOptions()
    {
        $items = [];
        foreach($this->productoptions->groupBy('option_id') as $values){
            $parent = $values->first()->option;
            $parent->values = $values;
            $items[] = $parent;
        }
        return $items;
    }


    /**
     * Get the option prices and append to product price
     *
     * @return boolean
     */
    private function optionsPrice()
    {
        if(!$this->pivot || !$this->pivot->options){
            return $this->price();
        }
        $options = \Shop::option()->unserialize($this->pivot->options, true);
        return 
        ($this->price() + $options->where('price_prefix', '+')->sum('extra')) -
            $options->where('price_prefix', '-')->sum('extra');
    }

    /**
     * Return a single product by lang_id
     *
     * @param type $lang_id
     * @return hasOne
     */
    public function productByLang(int $lang_id)
    {
        return $this->hasOne(config('shop.product-data-model'), 'product_id')->where('lang_id', $lang_id);
    }

    /**
     * Return single product by lang 1
     *
     * @return ShopProduct::productByLang
     */
    public function getProductAttribute()
    {
        return $this->productByLang(1)->first();
    }

    /**
     * Return single product by given lang_id
     *
     * @param type $lang_id
     * @return ShopProduct::productByLang
     */
    public function product(int $lang_id = 1)
    {
        return $this->productByLang($lang_id)->first();
    }

    /**
     * Return the products subcategories
     *
     * @return belongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(config('shop.category-model'), 'shop_product_categories', 'category_id', 'product_id');
    }
    
    /**
     * Return related products
     *
     * @return belongsToMany
     */
    public function related()
    {
        return $this->belongsToMany(config('shop.product-model'), 'shop_product_related', 'product_id', 'related_id');
    }

    /**
     * Return related products
     *
     * @return belongsToMany
     */
    public function attributes()
    {
        return $this->hasMany(config('shop.product-attributes-model'), 'product_id');
    }

    /**
     * Return the product status
     *
     * @return belongsTo
     */
    public function status()
    {
        return $this->belongsTo(config('shop.product-status-model'), 'status_id');
    }

    /**
     * Return the product status
     *
     * @return belongsTo
     */
    public function details()
    {
        return $this->hasOne(config('shop.product-details-model'), 'product_id');
    }


    /**
     * Return a single image
     *
     * @return hasMany
     */
    public function getImageAttribute()
    {
        return optional($this->images->first())->image;
    }

    /**
     * Return images by product
     *
     * @return hasMany
     */
    public function images()
    {
        return $this->hasMany(config('shop.product-images-model'), 'product_id');
    }

    /**
     * Return all products by lang_id
     *
     * @return hasMany
     */
    public function productoptions()
    {
        return $this->hasMany(config('shop.product-options-model'), 'product_id');
    }

}
