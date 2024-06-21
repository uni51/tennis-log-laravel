<!DOCTYPE html>
<html lang="ja">
<head>
    <title>テニスに関連しないメモの投稿通知</title>
</head>
<body>
<h1>テニスに関連しないメモが投稿されました。</h1>
<p>{!! $content !!}</p>
<hr/>
@if($actionType === 'create')
    <p>{{ "アクションの種別: 新規作成" }}</p>
@else
    <p>{{ "アクションの種別: 更新" }}</p>
@endif
<p>メモのid: {{ $memo->id }}</p>
<p>メモのステータス: {{ $statusLabel }}</p>
<p>{{ "投稿者のid: " . $user->id }}</p>
<p>{{ "投稿者のnickname: " . $user->nickname }}</p>
<p>メモのURL: <a href="{{ $domain . '/admin/memos/' . $user->nickname . '/' . $memo->id }}">
        {{ $domain . "/admin/memos/" . $user->nickname . "/" . $memo->id }}</a></p>
</body>
</html>
