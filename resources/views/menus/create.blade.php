@extends('layouts.app')

@section('page_title','Tambah Menu')

@section('content')
<div class="container-fluid" style="max-width:900px;">
  <div class="card p-4">
    <h5>Tambah Menu</h5>

    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form id="menu-form" action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nama Menu</label>
        <input name="nama_menu" value="{{ old('nama_menu') }}" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Kategori</label>
        <input name="kategori_menu" value="{{ old('kategori_menu') }}" class="form-control">
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Harga (angka)</label>
          <input name="harga" type="number" min="0" value="{{ old('harga',0) }}" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Stok Harian</label>
          <input name="stok_harian" type="number" min="0" value="{{ old('stok_harian',0) }}" class="form-control">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Keterangan</label>
        <textarea name="detail_menu" class="form-control" rows="3">{{ old('detail_menu') }}</textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Gambar</label>
        <input type="file" name="gambar" accept="image/*" class="form-control">
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="is_promoted" id="is_promoted">
        <label class="form-check-label" for="is_promoted">Promosikan menu ini</label>
      </div>

      <hr>
      <h6>Variasi Menu (opsional)</h6>
      <div id="variations-container" class="mb-3"></div>
      <button type="button" id="add-variation" class="btn btn-sm btn-outline-secondary mb-3">+ Tambah Variasi</button>

      <div class="d-flex gap-2">
        <a href="{{ route('menus.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Vanilla JS dynamic variations -->
<script>
  (() => {
    let vIndex = 0;
    const container = document.getElementById('variations-container');

    document.getElementById('add-variation').addEventListener('click', ()=> {
      addVariation();
    });

    function addVariation(name='', multiple=false, options=[]) {
      const vi = vIndex++;
      const div = document.createElement('div');
      div.className = 'card p-3 mb-2';
      div.dataset.variation = vi;

      div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
          <strong>Variasi</strong>
          <button type="button" class="btn btn-sm btn-danger remove-variation">Hapus Variasi</button>
        </div>

        <div class="mb-2">
          <input name="variations[${vi}][name]" class="form-control mb-1" placeholder="Nama variasi (contoh: Ukuran)" value="${escapeHtml(name)}" />
          <label><input type="checkbox" name="variations[${vi}][multiple]" ${multiple ? 'checked' : ''}> Boleh pilih lebih dari satu (multiple)</label>
        </div>

        <div class="options" id="options-${vi}"></div>
        <div class="mt-2">
          <button type="button" class="btn btn-sm btn-outline-primary add-option" data-vi="${vi}">+ Tambah Opsi</button>
        </div>
      `;

      container.appendChild(div);

      // add event listeners
      div.querySelector('.remove-variation').addEventListener('click', ()=> div.remove());
      div.querySelector('.add-option').addEventListener('click', (e)=> {
        const idx = div.querySelectorAll('.option-item').length;
        addOption(vi, '', 0);
      });

      // prefill options if provided
      if (Array.isArray(options)) {
        options.forEach(opt => addOption(vi, opt.name, opt.price_modifier));
      }
    }

    function addOption(vi, name='', price=0) {
      const opts = document.getElementById(`options-${vi}`);
      const idx = opts ? opts.children.length : 0;
      const wrapper = document.createElement('div');
      wrapper.className = 'd-flex gap-2 mb-2 option-item';
      wrapper.innerHTML = `
        <input name="variations[${vi}][options][${idx}][name]" class="form-control" placeholder="Nama opsi (contoh: Small)" value="${escapeHtml(name)}" />
        <input name="variations[${vi}][options][${idx}][price_modifier]" type="number" step="0.01" class="form-control" value="${Number(price)}" />
        <button type="button" class="btn btn-sm btn-danger remove-option">X</button>
      `;
      opts.appendChild(wrapper);
      wrapper.querySelector('.remove-option').addEventListener('click', ()=> wrapper.remove());
      // renumber indexes after add/remove
      renumberOptions(vi);
    }

    function renumberOptions(vi) {
      const opts = document.getElementById(`options-${vi}`);
      if (!opts) return;
      Array.from(opts.children).forEach((child, i) => {
        const nameInput = child.querySelector('input[name^="variations"]');
        const priceInput = child.querySelectorAll('input')[1];
        if (nameInput) nameInput.name = `variations[${vi}][options][${i}][name]`;
        if (priceInput) priceInput.name = `variations[${vi}][options][${i}][price_modifier]`;
      });
    }

    // util escape html
    function escapeHtml(unsafe) {
      return String(unsafe).replace(/[&<"']/g, function(m) {
        return ({'&':'&amp;','<':'&lt;','"':'&quot;',"'":'&#039;'}[m]);
      });
    }

    // init one empty variation by default (optional)
    // addVariation();

  })();
</script>
@endsection
