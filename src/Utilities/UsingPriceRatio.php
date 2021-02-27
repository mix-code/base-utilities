<?php

namespace MixCode\BaseUtilities;

use MixCode\Setting;

trait UsingPriceRatio
{
    /**
     * Generate Price Ratios for all price fields
     *
     * @return object
     */
    public function priceRatios()
    {
        $priceFields = $this->priceFields();

        /*
            Expected Inputs: 
                $priceFields = [price field]
                
                EX:
                    $priceFields = ['price']

            Expected Output:
                [
                    price field name => [
                        [ratio name => final ratio value]                        
                    ] 
                ]

                EX:
                    [
                        price => [
                            [
                                'usd' => usd final price value,
                                'egp' => egp final price value,
                            ]
                        ] 
                    ]
                
        */

        foreach ($priceFields as $field) {
            $ratioFields[$field] = $this->generatePriceRatios($field);
        }

        // Convert Output array to Object
        return json_decode(json_encode($ratioFields));
    }

    /**
     * Define Accessors for "PriceRatios"
     *
     * @return array
     */
    public function getPriceRatiosAttribute()
    {
        return $this->priceRatios();
    }

    /**
     * Generate price ratios fields for passed price
     *
     * @return void
     */
    protected function generatePriceRatios($price_field_name) 
    {
        // Get Price Ratios Fields Names From Setting Class
        $priceRatiosFieldsNames = Setting::PRICE_RATIOS_FIELDS_NAMES;
        $settings = getSettings();
        $ratios = [];

        /*
            Expected Inputs: 
                $priceRatiosFieldsNames = [currency => price ratio field in settings]

                EX:
                    $priceRatiosFieldsNames = ['usd' => 'usd_price_ratio']

            Equation:
                final price = price field value (ex: price) / usd price (ex: 3.75)
          
            Expected Output:
                [ratio name => final ratio value]
                
                EX:
                    [
                        'usd' => usd final price value,
                        'egp' => egp final price value,
                    ]
        */

        foreach ($priceRatiosFieldsNames as $ratio_name => $ratio_value) {
            $ratio_value    = $settings->{$ratio_value};
            $price          = $this->{$price_field_name};

            $ratios[$ratio_name] = round((floatval($price) / floatval($ratio_value)), 2);
        }

        return  $ratios;
    }


    /**
     * Register Price Fields to Generate Price Ratio
     *
     * @return array
     */
    protected function priceFields() 
    {
        return ['price'];
    }
}