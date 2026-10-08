@extends('layouts.app')
@section('content')
<div class="card anim-slide-up">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-pen-to-square"></i> {{ __('Edit') }} Work Order (SPK)</div>
    </div>
    <div class="card-body">
        <form action="{{ route('work-orders.update', $workOrder->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Nomor Work Order (SPK)</label>
                    <input type="text" name="order_number" class="form-control" value="{{ old('order_number', $workOrder->order_number) }}" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>{{ __('Nama Produk') }}</label>
                    <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $workOrder->product_name) }}" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Kuantitas Target</label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $workOrder->quantity) }}" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Satuan (Unit)</label>
                    <select name="unit" class="form-control" required>
                        <option value="pcs" {{ $workOrder->unit == 'pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="kg" {{ $workOrder->unit == 'kg' ? 'selected' : '' }}>Kg</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Status Work Order</label>
                    <select name="status" class="form-control" required>
                        <option value="Pending" {{ $workOrder->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ $workOrder->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ $workOrder->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="On Hold" {{ $workOrder->status == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                    </select>
                </div>
            </div>
            
            <div style="display:flex; gap:1rem; margin-top:2rem; padding-top:1.5rem; border-top:1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;"><i class="fa-solid fa-save"></i>{{ __('Perbarui Work Order') }}</button>
                <a href="{{ route('work-orders.index') }}" class="btn" style="background:#f1f5f9; color:#475569; padding: 0.75rem 1.5rem;"><i class="fa-solid fa-arrow-left"></i> Batal & Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
