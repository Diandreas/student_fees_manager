<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title : 'Notification' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 0;
            margin: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: {{ $school->theme_color ?? '#1a56db' }};
            color: white;
            border-radius: 5px 5px 0 0;
        }
        .header img {
            max-height: 80px;
            max-width: 200px;
            margin-bottom: 10px;
        }
        .content {
            padding: 20px;
            background-color: #ffffff;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #666;
            background-color: #f9f9f9;
            border-radius: 0 0 5px 5px;
            border-top: 1px solid #eeeeee;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: {{ $school->theme_color ?? '#1a56db' }};
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 15px;
        }
        .info {
            background-color: #f8f9fa;
            border-left: 4px solid {{ $school->theme_color ?? '#1a56db' }};
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($school && $school->logo)
                <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" />
            @else
                <h1>{{ $school->name ?? config('app.name') }}</h1>
            @endif
        </div>
        
        <div class="content">
            @yield('content')
        </div>
        
        <div class="footer">
            <p>
                {{ $school->name ?? config('app.name') }} &copy; {{ date('Y') }}
                <br>
                {{ $school->address ?? '' }}
                @if($school && ($school->contact_email || $school->phone))
                    <br>
                    @if($school->contact_email)
                        Email : {{ $school->contact_email }}
                    @endif
                    @if($school->phone)
                        @if($school->contact_email) | @endif
                        Téléphone : {{ $school->phone }}
                    @endif
                @endif
            </p>
            <p>
                <small>Cet email vous a été envoyé par {{ $school->name ?? config('app.name') }}.</small>
            </p>
        </div>
    </div>
</body>
</html> 