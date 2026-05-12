<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0D5C63;
            padding-bottom: 15px;
        }
        .header h2 {
            color: #0D5C63;
            margin: 0;
            font-size: 18px;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header p {
            color: #666;
            margin: 3px 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0D5C63;
            color: white;
            font-size: 11px;
        }
        td {
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .text-center {
            text-align: center;
        }
        .text-muted {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Graduate Library</h2>
        <h3>{{ $title }}</h3>
        <p>Generated on: {{ $date }}</p>
        @if($start_date && $end_date)
            <p>Date Range: {{ $start_date }} to {{ $end_date }}</p>
        @endif
        <p>Total Records: {{ $reports->count() }}</p>
    </div>
    
    <table>
        <thead>
            @if($type == 'books')
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>ISBN</th>
                <th>Qty</th>
                <th>Available</th>
            </tr>
            @elseif($type == 'students')
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Father Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Class</th>
                <th>Roll No</th>
            </tr>
            @elseif($type == 'issued' || $type == 'returned')
            <tr>
                <th>#</th>
                <th>Book Title</th>
                <th>Student Name</th>
                <th>Roll No</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Fine</th>
            </tr>
            @elseif($type == 'requests')
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Book Title</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Response</th>
            </tr>
            @endif
        </thead>
        <tbody>
            @if($type == 'books')
                @foreach($reports as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->author }}</td>
                    <td>{{ $item->category ?? 'General' }}</td>
                    <td>{{ $item->isbn ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->available }}</td>
                </tr>
                @endforeach
            @elseif($type == 'students')
                @foreach($reports as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->full_name }}</td>
                    <td>{{ $item->father_name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->phone_number }}</td>
                    <td>{{ $item->class }}</td>
                    <td>{{ $item->roll_no }}</td>
                </tr>
                @endforeach
            @elseif($type == 'issued')
                @foreach($reports as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->book->title ?? 'N/A' }}</td>
                    <td>{{ $item->student->full_name ?? 'N/A' }}</td>
                    <td>{{ $item->student->roll_no ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->issue_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</td>
                    <td>{{ $item->fine ? 'Rs. '.$item->fine : '-' }}</td>
                </tr>
                @endforeach
            @elseif($type == 'returned')
                @foreach($reports as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->book->title ?? 'N/A' }}</td>
                    <td>{{ $item->student->full_name ?? 'N/A' }}</td>
                    <td>{{ $item->student->roll_no ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->issue_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</td>
                    <td>{{ $item->fine ? 'Rs. '.$item->fine : '-' }}</td>
                </tr>
                @endforeach
            @elseif($type == 'requests')
                @foreach($reports as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->student->full_name ?? 'N/A' }}</td>
                    <td>{{ $item->book->title ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>{{ $item->admin_response ?? '-' }}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    
    <div class="footer">
        <p>Graduate Library Management System | Generated on {{ $date }}</p>
    </div>
</body>
</html>