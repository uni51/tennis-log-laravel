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

        $user_id = Auth::id();
        $bookmark = BookmarkMemo::create([
            'user_id' => $user_id,
            'memo_id' => $request->memo_id,
        ]);

        return response()->json($bookmark, 201);
    }

    /**
     * ブックマークを解除します。
     */
    public function deleteBookmark($id)
    {
        $user_id = Auth::id();

        $deleted = BookmarkMemo::where('user_id', $user_id)
                    ->where('memo_id', $id)
                    ->delete();

        if ($deleted) {
            return response()->json(null, 204);
        }

        return response()->json(['message' => 'Bookmark not found'], 404);
    }

    public function checkBookmark(Request $request, $memo_id)
    {
        $user_id = Auth::id();
        $isBookmarked = BookmarkMemo::where('user_id', $user_id)
            ->where('memo_id', $memo_id)
            ->exists();

        return response()->json(['isBookmarked' => $isBookmarked]);
    }
}
