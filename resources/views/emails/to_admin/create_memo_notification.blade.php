<!DOCTYPE html>
<html lang="ja">
<head>
    <title>メモの新規投稿通知</title>
</head>
<body>
<h1>メモが新規投稿されました。</h1>
<p>{!! $content !!}</p>
<hr/>
<p>{{ "メモの種別: 新規作成" }}</p>
<p>メモのid: {{ $memo->id }}</p>
<p>メモのステータス:
    @if($memo->status === 0)
        {{ "下書き" }}
    @endif
    @if($memo->status === 1)
        {{ "公開" }}
    @endif
    @if($memo->status === 2)
        {{ "シェア" }}
    @endif
    @if($memo->status === 3)
        {{ "非公開" }}
    @endif
</p>
<p>{{ "投稿者のid: " . $user->id }}</p>
<p>{{ "投稿者のnickname: " . $user->nickname }}</p>
<p>メモのURL: <a href="{{ $domain . '/admin/memos/' . $user->nickname . '/' . $memo->id }}">
        {{ $domain . "/admin/memos/" . $user->nickname . "/" . $memo->id }}</a></p>
</body>
</html>
