<?php

namespace App\Repositories;

use App\Models\Product;
use YouCanShop\QueryOption\QueryOption;
use YouCanShop\QueryOption\Laravel\UsesQueryOption;

class ProductRepository
{
    use UsesQueryOption;

    public function paginated(QueryOption $queryOption)
    {
        $query = Product::with('categories');

        // Apply search (if applicable)
        if ($search = $queryOption->getSearch()) {
            if ($search->getTerm()) {
                $query->where('name', 'like', '%' . $search->getTerm() . '%');
            }
        }

        // Apply filters
        if ($filters = $queryOption->getFilters()) {
            foreach ($filters as $filter) {
                if ($filter['field'] === 'category') {
                    $query->whereHas('categories', function ($q) use ($filter) {
                        $q->where('id', $filter['value']);
                    });
                }
            }
        }

        // Apply sorting
        if ($sort = $queryOption->getSort()) {
            $query->orderBy($sort->getField(), $sort->getDirection());
        }

        // Paginate results
        return $query->paginate(
            $queryOption->getLimit(),
            ['*'],
            'page',
            $queryOption->getPage()
        );
    }
}
