<!DOCTYPE html>
<html>
<head>
    <title>Weekly Report - {{ $weekStart->format('Y-m-d') }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #2563EB; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Future Skills Championship - Weekly Report</h1>
        <p>Period: {{ $weekStart->format('M d, Y') }} - {{ $weekEnd->format('M d, Y') }}</p>
        @if($school)
            <p>School: {{ $school->name }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Student Name</th>
                <th>School</th>
                <th>Class</th>
                <th>Total XP Earned</th>
                <th>Correct Answers</th>
                <th>Total Challenges</th>
                <th>Accuracy %</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data['student']->name }}</td>
                <td>{{ $data['student']->school->name }}</td>
                <td>{{ $data['student']->class->name }}</td>
                <td>{{ $data['total_xp_earned'] }}</td>
                <td>{{ $data['correct_answers'] }}</td>
                <td>{{ $data['total_challenges'] }}</td>
                <td>{{ $data['accuracy'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

