<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuVariation;
use App\Models\MenuVariationOption;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use DB;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $menus = Menu::when($q, fn($b) => $b->where('nama_menu','like',"%{$q}%")
                                           ->orWhere('kategori_menu','like',"%{$q}%"))
                     ->orderBy('id_menu','desc')
                     ->paginate(12)
                     ->appends(['q'=>$q]);

        return view('menus.index', compact('menus','q'));
    }

    public function create()
    {
        return view('menus.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori_menu' => 'nullable|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok_harian' => 'nullable|integer|min:0',
            'detail_menu' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
            'is_promoted' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // handle gambar
            if ($r->hasFile('gambar')) {
                $path = $r->file('gambar')->store('menus','public');
                $data['gambar'] = $path;
            }

            $data['is_promoted'] = $r->has('is_promoted') ? 1 : 0;
            $menu = Menu::create($data);

            // handle variations: expect structure variations[] as array in request JSON-like
            // variations => [
            //  0 => ['name'=>'Ukuran','multiple'=>0,'options'=>[['name'=>'S','price_modifier'=>0], ...]],
            // ]
            if ($r->filled('variations')) {
                $variations = $r->input('variations');
                foreach ($variations as $v) {
                    if (empty($v['name'])) continue;
                    $var = $menu->variations()->create([
                        'name' => $v['name'],
                        'multiple' => !empty($v['multiple']) ? 1 : 0,
                    ]);
                    if (!empty($v['options']) && is_array($v['options'])) {
                        foreach ($v['options'] as $opt) {
                            if (empty($opt['name'])) continue;
                            $var->options()->create([
                                'name' => $opt['name'],
                                'price_modifier' => (float) ($opt['price_modifier'] ?? 0),
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('menus.index')->with('success','Menu berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([$e->getMessage()])->withInput();
        }
    }

    public function edit(Menu $menu)
    {
        $menu->load('variations.options','activePromotion');
        return view('menus.edit', compact('menu'));
    }

    public function update(Request $r, Menu $menu)
    {
        $data = $r->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori_menu' => 'nullable|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok_harian' => 'nullable|integer|min:0',
            'detail_menu' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
            'is_promoted' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            if ($r->hasFile('gambar')) {
                // hapus file lama jika ada
                if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                    Storage::disk('public')->delete($menu->gambar);
                }
                $path = $r->file('gambar')->store('menus','public');
                $data['gambar'] = $path;
            }

            $data['is_promoted'] = $r->has('is_promoted') ? 1 : 0;
            $menu->update($data);

            // variations handling: simple approach — delete all variations and recreate
            if ($r->filled('variations')) {
                // delete existing
                foreach ($menu->variations as $v) {
                    $v->options()->delete();
                    $v->delete();
                }

                $variations = $r->input('variations');
                foreach ($variations as $v) {
                    if (empty($v['name'])) continue;
                    $var = $menu->variations()->create([
                        'name' => $v['name'],
                        'multiple' => !empty($v['multiple']) ? 1 : 0,
                    ]);
                    if (!empty($v['options']) && is_array($v['options'])) {
                        foreach ($v['options'] as $opt) {
                            if (empty($opt['name'])) continue;
                            $var->options()->create([
                                'name' => $opt['name'],
                                'price_modifier' => (float) ($opt['price_modifier'] ?? 0),
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('menus.index')->with('success','Menu berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([$e->getMessage()])->withInput();
        }
    }

    public function destroy(Menu $menu)
    {
        // delete image file
        if ($menu->gambar && \Storage::disk('public')->exists($menu->gambar)) {
            \Storage::disk('public')->delete($menu->gambar);
        }
        $menu->delete();

        return redirect()->route('menus.index')->with('success','Menu berhasil dihapus.');
    }
}
