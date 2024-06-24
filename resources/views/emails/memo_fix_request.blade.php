<!DOCTYPE html>
<html lang="ja">
<head>
<title>【{{ $serviceName }}】メモの修正リクエストが届いています。</title>
</head>
<body>
<p>こんにちは、{{ $user->nickname }}さん。</p>
<p>あなたのメモに、管理者から修正依頼が届きました。</p>
<p>対象のメモは以下の通りです。</p>
<hr/>
<p>
{!! $content !!}
</p>
<p>メモのid: {{ $memo->id }}</p>
<p>メモのカテゴリー: {{ $categoryDescription }}</p>
<p>メモのステータス: {{ $statusLabel }}</p>

<p>メモのURL: <a href="{{ $domain . '/dashboard/memos/' . $memo->id }}">{{ $domain . "/dashboard/memos/" . $memo->id }}</a></p>
</body>
</html>
