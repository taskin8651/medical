@extends('layouts.admin')
@section('page-title', 'Products')

@section('styles')
<style>
.page-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #E2E8F0;
    overflow: hidden;
}
.btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 10px;
    background: var(--accent); color: #fff;
    font-size: 13px; font-weight: 600; text-decoration: none;
    border: none; cursor: pointer; transition: opacity .2s;
}
.btn-primary:hover { opacity: .88; color: #fff; }
.btn-outline {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 8px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    border: 1.5px solid; cursor: pointer; transition: background .15s;
}
.btn-outline:hover { background: #F8FAFC; }
.status-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 10px; border-radius: 999px;
    font-size: 12px; font-weight: 600;
}
.status-pill.active { background: #DCFCE7; color: #166534; border: 1px solid #A7F3D0; }
.status-pill.inactive { background: #FFF1F2; color: #B91C1C; border: 1px solid #FECACA; }
.status-pill.prescription { background: #E0E7FF; color: #3730A3; border: 1px solid #C7D2FE; }
</style>
@endsection

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Products</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Manage your product catalog, pricing, stock and availability.</p>
    </div>
    @can('product_create')
    <a href="{{ route('admin.products.create') }}" class="btn-primary">
        <i class="fas fa-plus" style="font-size:11px;"></i>
        Add Product
    </a>
    @endcan
</div>

<div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; margin-bottom:24px;">
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:16px 18px;">
        <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; margin:0 0 6px;">Total products</p>
        <p style="font-size:24px; font-weight:700; color:#0F172A; margin:0;">{{ $products->total() }}</p>
    </div>
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:16px 18px;">
        <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; margin:0 0 6px;">Showing</p>
        <p style="font-size:24px; font-weight:700; color:#0F172A; margin:0;">{{ $products->count() }} on this page</p>
    </div>
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:16px 18px;">
        <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; margin:0 0 6px;">Active</p>
        <p style="font-size:24px; font-weight:700; color:#0F172A; margin:0;">{{ $products->where('is_active', true)->count() }}</p>
    </div>
</div>

<div class="page-card" style="margin-bottom:24px;">
    <div style="padding:18px 22px; display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:12px; align-items:end;">
        <form method="GET" action="{{ route('admin.products.index') }}" style="display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px; width:100%;">
            <div>
                <label style="font-size:12px; font-weight:700; color:#475569;">Search</label>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name or SKU"
                    style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid #E2E8F0; background:#F8FAFC; color:#0F172A;" />
            </div>
            <div>
                <label style="font-size:12px; font-weight:700; color:#475569;">Category</label>
                <select name="category_id" style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid #E2E8F0; background:#F8FAFC; color:#0F172A;">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:12px; font-weight:700; color:#475569;">Brand</label>
                <select name="brand_id" style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid #E2E8F0; background:#F8FAFC; color:#0F172A;">
                    <option value="">All brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="submit" class="btn-primary" style="width:100%;">Filter</button>
                <a href="{{ route('admin.products.index') }}" class="btn-outline" style="width:100%; justify-content:center;">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="page-card">
    <div style="padding:16px 20px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">All Products</p>
        <span style="font-size:12px; color:#94A3B8;">Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}</span>
    </div>
    <div style="overflow-x:auto; padding:4px 10px;">
        <table class="min-w-full" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#F8FAFC; color:#64748B; font-size:11px; text-transform:uppercase; letter-spacing:.06em;">
                    <th style="padding:12px 16px; text-align:left;">ID</th>
                    <th style="padding:12px 16px; text-align:left;">Name</th>
                    <th style="padding:12px 16px; text-align:left;">Category</th>
                    <th style="padding:12px 16px; text-align:left;">Brand</th>
                    <th style="padding:12px 16px; text-align:left;">Price</th>
                    <th style="padding:12px 16px; text-align:left;">Stock</th>
                    <th style="padding:12px 16px; text-align:left;">Status</th>
                    <th style="padding:12px 16px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr style="border-bottom:1px solid #F1F5F9;">
                    <td style="padding:14px 16px; color:#475569;">#{{ $product->id }}</td>
                    <td style="padding:14px 16px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:40px; height:40px; border-radius:12px; overflow:hidden; background:#E2E8F0; display:flex; align-items:center; justify-content:center; color:#475569; font-weight:700;">
                                {{ strtoupper(substr($product->name, 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-size:13.5px; font-weight:600; margin:0; color:#0F172A;">{{ $product->name }}</p>
                                <p style="font-size:12px; color:#94A3B8; margin:4px 0 0;">{{ $product->sku ?? 'No SKU' }}</p>
                            </div>
                        </div>
                    </td>
                    <td style="padding:14px 16px; color:#475569;">{{ optional($product->category)->name ?? '-' }}</td>
                    <td style="padding:14px 16px; color:#475569;">{{ optional($product->brand)->name ?? '-' }}</td>
                    <td style="padding:14px 16px; color:#0F172A; font-weight:700;">₹{{ number_format($product->price, 2) }}</td>
                    <td style="padding:14px 16px; color:#475569;">{{ $product->stock ?? 0 }}</td>
                    <td style="padding:14px 16px;">
                        <span class="status-pill {{ $product->is_active ? 'active' : 'inactive' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td style="padding:14px 16px; text-align:right;">
                        <div style="display:flex; justify-content:flex-end; gap:6px; flex-wrap:wrap;">
                            @can('product_show')
                            <a href="{{ route('admin.products.show', $product) }}" class="btn-outline" style="border-color:#E2E8F0; color:#475569;">View</a>
                            @endcan
                            @can('product_edit')
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-outline" style="border-color:color-mix(in srgb, var(--accent) 40%, transparent); color:var(--accent);">Edit</a>
                            @endcan
                            @can('product_delete')
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                @method('DELETE') @csrf
                                <button type="submit" class="btn-outline" style="border-color:#FECDD3; color:#BE123C;">Delete</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:24px 16px; color:#64748B; text-align:center;">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px 20px; display:flex; justify-content:flex-end;">
        {{ $products->links() }}
    </div>
</div>

@endsection