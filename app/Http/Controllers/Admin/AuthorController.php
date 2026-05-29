<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::withCount('books');

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nationality', 'like', "%{$search}%");
            });
        }

        $authors = $query->orderBy('name')->paginate(15)->appends($request->query());

        return view('admin.authors.index', compact('authors'));
    }

    public function store(Request $request)
    {
        $author = Author::create($this->validateData($request));

        return redirect()->route('admin.authors.index')
            ->with('success', 'Đã thêm tác giả "' . $author->name . '"');
    }

    public function update(Request $request, Author $author)
    {
        $author->update($this->validateData($request));

        return redirect()->route('admin.authors.index')
            ->with('success', 'Cập nhật tác giả thành công');
    }

    public function destroy(Author $author)
    {
        if ($author->books()->exists()) {
            return back()->with('error', 'Không thể xoá: tác giả đang gắn với sách. Hãy gỡ khỏi các sách trước.');
        }

        $author->delete();

        return back()->with('success', 'Đã xoá tác giả');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'biography' => 'nullable|string',
            'description' => 'nullable|string|max:500',
        ]);
    }
}
