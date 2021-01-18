@extends('layouts.app')

@section('content')
<div class="report">
    <div class="report__header">
        <h2 class="report__header-heading">
            <a href="{{ route('home') }}">{{ __('Most Purchased Products') }}</a>
        </h2>
        <h6 class="report__header-subheading">{{ __(':total total', ['total' => $results->total()]) }}</h6>
    </div>
    <div class="report__body">
        <div class="report__body-container--scrollable">
            <table class="report__table">
                <thead class="report__table-header">
                    <tr>
                        <th scope="col" class="report__table-header-column">{{ __('Name') }}</th>
                        <th scope="col" class="report__table-header-column report__table-header-column--right">{{ __('Price') }}</th>
                        <th scope="col" class="report__table-header-column report__table-header-column--right">{{ __('Times purchased') }}</th>
                        <th scope="col" class="report__table-header-column report__table-header-column--right">{{ __('Current stock') }}</th>
                        <th scope="col" class="report__table-header-column report__table-header-column--right">{{ __('Total order value') }}</th>
                    </tr>
                </thead>
                <tbody class="report__table-body">
                @foreach ($results as $result)
                    <tr>
                        <td class="report__table-body-column">
                            <div class="report__table-product">
                                <div class="report__table-product-image-container">
                                    <img class="report__table-product-image" src="{{ $result->get('image_url') }}" alt="{{ $result->get('image_alt') }}">
                                </div>
                                <div class="report__table-product-name-container">
                                    <div class="report__table-product-name">{{ $result->get('product') }}</div>
                                    <div class="report__table-product-variant">{{ $result->get('variant') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="report__table-body-column report__table-body-column--muted report__table-body-column--right">
                            {{ $result->get('price') }} {{ $result->get('currency') }}
                        </td>
                        <td class="report__table-body-column report__table-body-column--muted report__table-body-column--right">
                            {{ $result->get('total_purchase_count') }}
                        </td>
                        <td class="report__table-body-column report__table-body-column--muted report__table-body-column--right">
                            {{ $result->get('stock_qty') }}
                        </td>
                        <td class="report__table-body-column report__table-body-column--muted report__table-body-column--right">
                            {{ $result->get('total_order_value') }} {{ $result->get('currency') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if ($results->total() !== $results->count())
        <nav class="pagination">
            {{ $results->links() }}
        </nav>
        @endif
    </div>
</div>
@endsection
