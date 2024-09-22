<?php

namespace App\Repositories;

use App\Models\Product;
use YouCanShop\QueryOption\Laravel\UsesQueryOption;
use YouCanShop\QueryOption\QueryOption;

class ProductRepository
{
    use UsesQueryOption;

    public function paginated(QueryOption $queryOption)
    {
        $query = Product::with('categories');

        if ($search = $queryOption->getSearch()) {
            if ($search->getTerm()) {
                $query->where('name', 'like', '%' . $search->getTerm() . '%');
            }
        }
        if ($filters = $queryOption->getFilters()) {
            foreach ($filters as $filter) {
                if ($filter->getField() === 'category' && $filter->getValue()) {
                    $query->whereHas('categories', function ($q) use ($filter) {
                        $q->where('categories.id', $filter->getValue());
                    });
                }
            }
        }

        if ($sort = $queryOption->getSort()) {
            $query->orderBy($sort->getField(), $sort->getDirection());
        }

        return $query->paginate(
            $queryOption->getLimit(),
            ['*'],
            'page',
            $queryOption->getPage()
        );
    }
}
