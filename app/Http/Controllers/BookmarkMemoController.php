<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookmarkMemo;
use Illuminate\Support\Facades\Auth;

class BookmarkMemoController extends Controller
{
    /**
     * メモをブックマークします。
     */
    public function createBookmark(Request $request)
    {
        $request->validate([
            'memo_id' => 'required|exists:memos,id',
        ]);

        $bookmark = BookmarkMemo::create([
            'user_id' => Auth::user()->id,
            'memo_id' => $request->memo_id,
        ]);

        return response()->json($bookmark, 201);
    }

    /**
     * ブックマークを解除します。
     */
    public function deleteBookmark($id)
    {
        $bookmark = BookmarkMemo::where('user_id', Auth::user()->id)
            ->where('memo_id', $id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(null, 204);
        }

        return response()->json(['message' => 'Bookmark not found'], 404);
    }
}
