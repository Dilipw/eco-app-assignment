<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductTableExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return Product::with('category')
            ->get()
            ->map(function ($product) {
                return [
                    'Name' => $product->name,
                    'Category' => $product->category?->name ?? '-',
                    'Description' => $product->description,
                    'Price (INR)' => $product->price,
                    'Stock' => $product->stock,
                    'Created At' => $product->created_at->format('d M Y'),
                ];
            });
    }

    public function headings(): array
    {
        return ['Name', 'Category', 'Description','Price (INR)', 'Stock', 'Created At'];
    }
}
