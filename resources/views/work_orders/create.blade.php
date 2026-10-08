@extends('layouts.app')
@section('content')
<div class="card anim-slide-up">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-file-circle-plus"></i> {{ __('Tambah Work Order') }} Baru (SPK)</div>
    </div>
    <div class="card-body">
        <form action="{{ route('work-orders.store') }}" method="POST">
            @csrf
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Nomor Work Order (SPK)</label>
                    <input type="text" name="order_number" class="form-control" placeholder="Contoh: WO-20250601-01" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>{{ __('Nama Produk') }}</label>
                    <input type="text" name="product_name" class="form-control" placeholder="Contoh: Day Cream 20g" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Kuantitas Target</label>
                    <input type="number" name="quantity" class="form-control" placeholder="Contoh: 1000" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Satuan (Unit)</label>
                    <select name="unit" class="form-control" required>
                        <option value="pcs">Pcs</option>
                        <option value="kg">Kg</option>
                    </select>
                </div>
            </div>
            
            <div style="display:flex; gap:1rem; margin-top:2rem; padding-top:1.5rem; border-top:1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;"><i class="fa-solid fa-save"></i> {{ __('Simpan Work Order') }}</button>
                <a href="{{ route('work-orders.index') }}" class="btn" style="background:#f1f5f9; color:#475569; padding: 0.75rem 1.5rem;"><i class="fa-solid fa-arrow-left"></i> Batal & Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
