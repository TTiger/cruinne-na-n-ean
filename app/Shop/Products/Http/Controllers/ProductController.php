<?php

declare(strict_types=1);

namespace App\Shop\Products\Http\Controllers;

use App\Shop\Products\Reports\MostPurchasedReport;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ProductController
{
    private const ITEMS_PER_PAGE = 10;

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param MostPurchasedReport $report
     *
     * @return View
     */
    public function __invoke(Request $request, MostPurchasedReport $report): View
    {
        return view('product.index', [
            'results' => $report->run(self::ITEMS_PER_PAGE)
        ]);
    }
}
