<!DOCTYPE html>
<html lang="ja">
<head>
    <title>メモの修正投稿通知</title>
</head>
<body>
<h1>メモが修正投稿されました。</h1>
<p>{!! $content !!}</p>
<hr/>
<p>{{ "メモの種別: 更新（修正）" }}</p>
<p>メモのid: {{ $memo->id }}</p>
<p>メモのカテゴリー: {{ $categoryDescription }}</p>
<p>メモのステータス: {{ $statusLabel }}</p>
<p>{{ "投稿者のid: " . $user->id }}</p>
<p>{{ "投稿者のnickname: " . $user->nickname }}</p>
<p>メモのURL: <a href="{{ $domain . '/admin/memos/' . $user->nickname . '/' . $memo->id }}">
        {{ $domain . "/admin/memos/" . $user->nickname . "/" . $memo->id }}</a></p>
</body>
</html>
