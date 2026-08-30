<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Ukm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventCategoriesController extends Controller
{

public function index(Request $request)
{
    $tab = $request->query('tab', 'active');
    $search = $request->query('search', '');
// 1. Tentukan sumber data berdasarkan tab
    if ($tab === 'ukm') {
        $query = Ukm::query();
    } else {
        $query = Category::query()->withTrashed()
            ->when($tab === 'active', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->when($tab === 'deleted', function ($q) {
                $q->whereNotNull('deleted_at');
            });
    }

  // 2. Terapkan pencarian
    $query->when($search, function ($q) use ($search, $tab) {
    $column = ($tab === 'ukm') ? 'nama_ukm' : 'name';
    $q->where($column, 'like', "%{$search}%");

});
$orderColumn = ($tab === 'ukm') ? 'nama_ukm' : 'name';
$categories = $query->orderBy($orderColumn)->get();

    // 3. Hitung jumlah event per kategori / UKM
    $eventCountByCategory = [];
    if ($tab === 'active' || $tab === 'deleted') {
        $countsById = Event::whereNotNull('category_id')
            ->selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');
        foreach ($categories as $cat) {
            $eventCountByCategory[$cat->name] = $countsById[$cat->id] ?? 0;
        }
    } elseif ($tab === 'ukm') {
        foreach ($categories as $ukm) {
            $userIdsForThisUkm = DB::table('role_applications')
                ->where('ukm_id', $ukm->id)
                ->where('status', 'approved')
                ->pluck('user_id');
            $eventCountByCategory[$ukm->nama_ukm] = Event::whereIn('user_id', $userIdsForThisUkm)->count();
        }
    }

    // 4. Data Statistik
    $totalCategories = Category::count();
    $totalDeleted = Category::onlyTrashed()->count();
    $totalEventsTagged = Event::whereNotNull('category_id')->count();
    $mostUsed = '-';
    if ($tab === 'active') {
        $mostUsed = Category::withCount('events')->orderByDesc('events_count')->first()?->name ?? '-';
    }
    return view('Admin.EventCategories', compact(
        'categories', 'tab', 'search', 'totalCategories',
        'totalDeleted', 'totalEventsTagged', 'mostUsed', 'eventCountByCategory'
    ));
}
    public function store(Request $request)
{
    $tab = $request->input('type'); // Ambil dari input hidden di form

    if ($tab === 'ukm') {
        $request->validate([
            'name' => 'required|string|max:100|unique:ukms,nama_ukm'
        ]);
        Ukm::create(['nama_ukm' => $request->name]);
        $msg = 'UKM berhasil ditambahkan.';
    } else {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name'
        ]);
        Category::create(['name' => $request->name]);
        $msg = 'Kategori berhasil ditambahkan.';
    }

    return redirect()->route('admin.categories', ['tab' => $tab])->with('success', $msg);
}

    public function update(Request $request, $id)
{
    $type = $request->input('type');
    if ($type === 'ukm') {
        $request->validate([
            'name' => 'required|string|max:100|unique:ukms,nama_ukm,' . $id,
        ]);
        $ukm = Ukm::findOrFail($id);
        $ukm->update([
            'nama_ukm' => $request->name
        ]);
        return back()->with('success', 'UKM berhasil diperbarui.');
    }

    $category = Category::findOrFail($id);
    $request->validate([
        'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
    ]);
    $oldName = $category->name;
    $category->update([
        'name' => $request->name
    ]);
    if ($oldName !== $category->name) {
        Event::where('category', $oldName)
            ->update(['category' => $category->name]);
    }
    return back()->with('success', 'Kategori berhasil diperbarui.');
}

  public function destroy(Request $request, $id)
{
    $type = $request->input('type');
    if ($type === 'ukm') {
        $ukm = Ukm::findOrFail($id);
        $ukm->delete();
        return back()->with('success', 'UKM berhasil dihapus.');
    }
    $category = Category::findOrFail($id);
    $category->delete();
    return back()->with('success', 'Kategori berhasil dihapus.');
}

    public function restore($id)
    {
        Category::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('admin.categories', ['tab' => 'deleted'])
            ->with('success', 'Kategori berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        Category::withTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route('admin.categories', ['tab' => 'deleted'])
            ->with('success', 'Kategori berhasil dihapus permanen.');
    }
}
