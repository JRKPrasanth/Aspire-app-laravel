<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ ($status ?? 500) }} | Something went wrong</title>
  <style>
    :root { --bg:#0f172a; --card:#111827; --text:#e5e7eb; --muted:#9ca3af; --accent:#60a5fa; }
    * { box-sizing:border-box }
    body {
      margin:0; min-height:100vh; display:grid; place-items:center;
      background-image: linear-gradient(to top, #a18cd1 0%, #fbc2eb 100%);
      color:var(--text); font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Apple Color Emoji","Segoe UI Emoji";
    }
    .card{width:min(720px,94vw);background:linear-gradient(180deg,#0b1220 0%,var(--card) 100%);border:1px solid #1f2937;border-radius:20px;padding:28px 28px 22px;box-shadow:0 20px 70px rgba(0,0,0,.45),inset 0 0 0 1px rgba(255,255,255,.02)}
    .badge{display:inline-flex;gap:8px;align-items:center;padding:6px 10px;border-radius:999px;background:#111827;border:1px solid #1f2937;color:var(--muted);font-size:12px}
    .opps{margin:14px 0 8px;font-size:40px;line-height:1.2}
    p.lead { margin:0 0 14px;font-weight: 700;font-size: 20px; }
    .row{display:flex;gap:14px;flex-wrap:wrap;margin-top:14px}
    .btn{appearance:none;cursor:pointer;border-radius:12px;padding:12px 16px;font-weight:600;text-decoration:none;display:inline-block;border:1px solid #1f2937;background:#0b1220;color:var(--text)}
    .btn.primary{background:linear-gradient(180deg,#3b82f6,#2563eb);border-color:#1d4ed8}
	  .btn.warning { background: linear-gradient(180deg, #ffc50f, #ec9c30); }
    .btn:hover{filter:brightness(1.05)}
    .code{margin-top:18px;padding:14px;border-radius:14px;background:#0b1220;border:1px solid #1f2937;color:#cbd5e1;overflow:auto;font-size:12px;max-height:280px;white-space:pre-wrap;word-break:break-word}
    .meta{margin-top:10px;color:var(--muted);font-size:12px}
  </style>
</head>
<body>
  <main class="card" role="alert">
    <span class="badge">Status: {{ $status ?? 500 }}</span>
    <h1 class="opps">Whoops! There was an error.</h1>
    <p class="lead">Please contact the IT team.</p>

    <div class="row">
      <a class="btn primary" href="{{ url('home') }}">Home</a>
      <a class="btn warning" href="mailto:aspire@jrkresearch.com?subject=Aspire%20Error Id -%20{{ urlencode($errorId ?? '') }}">Contact IT</a>
    </div>

    <div class="meta">Reference code: <strong>{{ $errorId ?? 'N/A' }}</strong></div>

    @if(!empty($showDetails) && $showDetails)
      <div class="code">
        <strong>Message:</strong> {{ $message ?? '—' }}{!! "\n\n" !!}
        <strong>Trace:</strong>{!! "\n" !!}{{ $trace ?? '—' }}
      </div>
    @endif
  </main>
</body>
</html>
