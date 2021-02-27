<?php

namespace MixCode\BaseUtilities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait UsingFilters
{
    /**
     * Scoped Filter Implementation
     *
     * @param Builder $builder
     * @param Request $request
     * @return Builder
     */
    public function scopeFilter(Builder $builder, Request $request)
    {
        $filters = $this->generateFilterMethods($request->keys());
        
        $filters->each(function ($filter) use($request, $builder) {
            $this->{$filter}($request, $builder);
        });

        return $builder;
    }

    public static function getFiltersKeys()
    {
        return [
            'keyword',
            'country',
            'city',
            'categories',
            'date_from',
            'date_to',
            'price_from',
            'price_to',
            'trip_for_individuals',
            'trip_for_groups',
            'price_low',
            'price_high',
            'most_popular',
            'most_recently',
            'most_wanted',
            'highest_rated',
        ];
    }
    
    public static function getOrderByFiltersKeys()
    {
        return [
            'trip_for_individuals',
            'trip_for_groups',
            'price_low',
            'price_high',
            'most_popular',
            'most_recently',
            'most_wanted',
            'highest_rated',
        ];
    }

    /**
     * Filter trips by filtering name
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByKeyword(Request $request, Builder $builder)
    {
        if ($request->has('keyword') && $request->filled('keyword')) {
            $builder
                ->where(function (Builder $b) use($request) {
                    $b->where('en_name', 'LIKE', "%{$request->keyword}%");
                    $b->orWhere('ar_name', 'LIKE', "%{$request->keyword}%");
                })
                ->orWhere(function (Builder $b) use($request) {
                    $b->where('en_address', 'LIKE', "%{$request->keyword}%");
                    $b->orWhere('ar_address', 'LIKE', "%{$request->keyword}%");
                })
                ->orWhere(function (Builder $b) use($request) {
                    $b->where('en_overview', 'LIKE', "%{$request->keyword}%");
                    $b->orWhere('ar_overview', 'LIKE', "%{$request->keyword}%");
                });
        }

        return $builder;
    }

    /**
     * Filter trips by Country id
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByCountry(Request $request, Builder $builder)
    {
        if ($request->has('country') && $request->filled('country')) {
            $builder->where('country_id', $request->country);
        }

        return $builder;
    }

    /**
     * Filter trips by City id
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByCity(Request $request, Builder $builder)
    {
        if ($request->has('city') && $request->filled('city')) {
            $builder->where('city_id', $request->city);
        }

        return $builder;
    }

    /**
     * Filter trips by Categories Ids
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByCategories(Request $request, Builder $builder)
    {
        if ($request->has('categories') && $request->filled('categories')) {
            $builder->whereHas('categories', function (Builder $b) use ($request) {
                $b->whereIn('categories.id', $request->categories);
            });
        }

        return $builder;
    }

    
    /**
     * Filter trips by Date From (start date)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByDateFrom(Request $request, Builder $builder)
    {
        // if date from  not empty and date to is empty 
        // then search for trips or with its custom dates by date from date if no data found
        if (($request->has('date_from') && $request->filled('date_from')) && (! $request->filled('date_to'))) {
            $builder->whereHas('dates', function (Builder $b) use($request) {
                $b->whereDate('date', '>=', $request->date_from);
            });
        }

        return $builder;
    }
 
    /**
     * Filter trips by Date To (end date)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByDateTo(Request $request, Builder $builder)
    {
        // if date to not empty and date from is empty 
        // then search for trips or with its custom dates by date to date if no data found
        if (($request->has('date_to') && $request->filled('date_to')) && (! $request->filled('date_from'))) {
            $builder->whereHas('dates', function (Builder $b) use($request) {
                $b->whereDate('date', '<=', $request->date_to);
            });
        }
        
        // if date to not empty and date from is not empty 
        // then search for trips or with its custom dates by date to and date from if no data found
        if (($request->has('date_to') && $request->filled('date_to')) && ($request->has('date_from') && $request->filled('date_from'))) {            
            $builder->whereHas('dates', function (Builder $b) use($request) {
                $b->whereBetween('date', [$request->date_from, $request->date_to]);
            });
        }

        return $builder;
    }
 
    /**
     * Filter trips by Price From (start price)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByPriceFrom(Request $request, Builder $builder)
    { 
        if ($request->has('price_from') && $request->filled('price_from')) {
            $builder->where('price', '>=', floatval($request->price_from));
        }

        return $builder;
    }
 
    /**
     * Filter trips by Price From (end price)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByPriceTo(Request $request, Builder $builder)
    {
        if ($request->has('price_to') && $request->filled('price_to')) {
            $builder->where('price', '<=', $request->price_to);
        }   

        return $builder;
    }
 
    /**
     * Filter trips by minimum escorts (null, 0, 1)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByTripForIndividuals(Request $request, Builder $builder)
    {
        if ($request->has('trip_for_individuals')) {
            $builder->where(function (Builder $q) {
                $q->whereNull('minimum_escorts')
                    ->orWhere('minimum_escorts', 0)
                    ->orWhere('minimum_escorts', 1);
            });
        }

        return $builder;
    }
    
    /**
     * Filter trips by minimum escorts more than (1)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByTripForGroups(Request $request, Builder $builder)
    {
        // it's just a clearing option for 'filterByTripForIndividuals'

        return $builder;
    }
 
    /**
     * Filter trips by lower price
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByPriceLow(Request $request, Builder $builder)
    {
        if ($request->has('price_low')) {
            // $builder->oldest('price');
            $builder->orderBy('price', 'asc'); // Used For More Readability
        }

        return $builder;
    }
 
    /**
     * Filter trips by higher price
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByPriceHigh(Request $request, Builder $builder)
    {
        if ($request->has('price_high')) {
            // $builder->latest('price'); 
            $builder->orderByDesc('price'); // Used For More Readability
        }

        return $builder;
    }
 
    /**
     * Filter trips by most popular
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByMostPopular(Request $request, Builder $builder)
    {
        if ($request->has('most_popular')) {
            // $builder->latest('views_count');
            $builder->orderByDesc('views_count'); // Used For More Readability
        }   

        return $builder;
    }
    
    /**
     * Filter trips by most recently (just created)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByMostRecently(Request $request, Builder $builder)
    {
        if ($request->has('most_recently')) {
            // created_at is the default for ordering but for more readability i wrote it.
            $builder->latest('created_at'); 
        }

        return $builder;
    }
    
    /**
     * Filter trips by Highest Rated
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByHighestRated(Request $request, Builder $builder)
    {
        if ($request->has('highest_rated')) {
            $builder->orderByDesc('average_rate');
        }

        return $builder;
    }
    
    /**
     * Filter trips by Most Wanted
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByMostWanted(Request $request, Builder $builder)
    {
        if ($request->has('most_wanted')) {

            $builder->withCount(['booking' => function ($builder) {
                return $builder->where('is_cancelled', false);
            }])
            ->orderByDesc('booking_count');
        }

        return $builder;
    }
    
    /**
     * Generate filter methods names from request input keys
     *
     * @param array $keys
     * @return \Illuminate\Support\Collection
     */
    protected function generateFilterMethods(array $keys)
    {
        return collect($keys)->map(function ($key) {
            if (in_array($key, static::getFiltersKeys())) {
                return 'filterBy' . Str::studly($key);
            }
            
            return null;
        })->filter()->values();
    }
}