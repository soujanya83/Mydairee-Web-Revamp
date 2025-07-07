<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Child's Reflection Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 200px;
            margin-bottom:5px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
            color:blueviolet;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f8f8;
            font-weight: bold;
            width: 150px;
        }
        .photo-cell {
            height: 150px;
        }
        .observation-cell {
            height: 120px;
        }
        .outcomes-cell {
            height: 150px;
        }
        .analysis-cell, .reflection-cell {
            height: 120px;
            width: 50%;
        }
        .voice-cell {
            height: 80px;
        }
        .assessment-cell, .milestones-cell {
            height: 120px;
            width: 50%;
        }
        .plan-cell {
            height: 100px;
        }
     /* Photo Gallery Container */
.photo-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); /* Responsive grid */
    gap: 10px; /* Space between images */
    margin-top: 10px;
}

/* Each Photo Item */
.photo-item {
    border: 1px solid #ccc; /* Light border for clean look */
    border-radius: 12px; /* Rounded corners */
    padding: 5px; /* Space inside the box */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Light shadow */
    text-align: center; /* Center image */
}

/* Image Styling */
.child-image {
    width: 100%;
    height: 100%;
    max-width: 100%;
    max-height: 100%; /* Consistent image height */
    border-radius: 8px; /* Rounded image corners */
    object-fit: cover; /* Crop image to fill space while maintaining aspect ratio */
}
        /* .no-print {
            display: none;
        } */

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-button{
            position: fixed;
            top: 70px;
            right: 30px;
            padding: 10px 20px;
            background-color: #2ee9ef;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }


        
        /* @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
            }
            @page {
                size: A4;
                margin: 1cm;
            }
        } */

        @media print {
    /* Hide print and back buttons */
    .print-button, .back-button {
        display: none;
    }

    /* Ensure body and page styling */
    body {
        margin: 0;
        padding: 0;
        font-size: 12px; /* Consistent font size for better print output */
        line-height: 1.4;
    }

    .container {
        width: 100%;
        padding: 20px;
        box-sizing: border-box;
    }

    /* Ensure A4 size with consistent page margins */
    @page {
        size: A4; /* Standard print size */
        margin: 20mm; /* Ensure 20mm margin on all sides */
    }

    /* Ensure margin at the bottom of each page */
    .page::after {
        content: "";
        display: block;
        height: 15mm; /* Add bottom space to avoid cutoff */
    }

 

    /* Avoid breaking inside these elements */
    .no-break, .child-image, table, img {
        page-break-inside: avoid; /* Avoid breaking inside tables and images */
    }



    /* Force page break after specific sections */
    .page-break {
        page-break-after: always; /* Use where necessary to break pages */
    }

    /* Prevent headings and important sections from breaking */
    h1, h2, h3, th {
        page-break-after: avoid; /* Keep headings intact */
    }

    /* Table styling for better print readability */
    table {
        border-collapse: collapse;
        width: 100%;
    }

    /* Ensure header and title stay together but do not push the table to a new page */
.title-wrapper {
    break-inside: avoid;       /* Avoid breaking inside */
    page-break-inside: avoid;  /* Avoid breaking inside (for older browsers) */
}

/* Ensure the table follows without forcing a new page */
table {
    break-before: auto;        /* Let the table naturally flow */
    page-break-before: auto;   /* Same for compatibility */
    break-inside: auto;        /* Avoid breaking inside if possible */
    page-break-inside: auto;   /* Compatibility */
}

    th, td {
        border: 1px solid #000; /* Solid border for print */
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f8f8f8; /* Light background for headers */
    }

    /* Image Styling */
    .photo-gallery {
        grid-template-columns: repeat(3, 1fr); /* 3 images per row on print */
        gap: 8px; /* Reduced gap for print */
    }

    .photo-item {
        box-shadow: none; /* Remove shadow for print */
        border: 0.5px solid #999; /* Thinner border for print */
    }
}

    </style>
</head>
<body>
<button onclick="window.print()" class="print-button">Print Pages&nbsp;<i class="fa-solid fa-print fa-beat-fade"></i></button>
<!-- <button onclick="window.history.back();" class="back-button">
    <i class="fa-solid fa-arrow-left fa-beat"></i>&nbsp;Go Back
</button> -->

    <div class="container">
  

        <!-- Header with Logo -->
        <div class="header" title-wrapper>
            <img src="{{ asset('assets/profile_1739442700.jpeg') }}" alt="NEXTGEN Montessori" class="logo">
        </div>
       
        <!-- Title -->
        <div class="title">Daily Reflection:</div>
        
        <!-- Child Info Table -->
        <table>
            <tr>
                <th colspan="2">Child/ren Name</th>
                <td colspan="2">  
                @if($reflection->children && $reflection->children->isNotEmpty())
                        {{ $reflection->children->pluck('child.name')->implode(', ') }}
                    @endif
                </td>
                </tr>
                <tr>
                <th colspan="2">Date</th>
                <td colspan="2">{{ \Carbon\Carbon::parse($reflection->created_at)->format('d/m/Y') }}</td>
                </tr>
            </tr>
            <tr>
                <th>Educator's Name</th>
                <td colspan="3">
                @if($reflection->staff && $reflection->staff->isNotEmpty())
                        {{ $reflection->staff->pluck('staff.name')->implode(', ') }}
                    @endif
                
                </td>
            </tr>
            <tr>
                <th>Classroom</th>
                <td colspan="3">
                {{ $roomNames ?? $roomNames ?? '' }}
                </td>
            </tr>



            <tr>
                    <th>Daily Reflection:</th>
                <td colspan="3">
                <!-- <td colspan="3" class="observation-cell"> -->
                {!! $reflection->about ?? 'not update' !!}
           
                </td>
               
            </tr>




            <tr>
                <!-- <th>Child's Photos</th> -->
                <td colspan="4" class="photo-cell">
                    <strong><p>Child's Photos</p></strong>

                    <div class="photo-gallery">
                    @if($reflection->media && $reflection->media->isNotEmpty())
                            @foreach($reflection->media as $mediaItem)
                            @if(Str::startsWith($mediaItem->mediaType, 'image'))
                                    <img src="{{ asset($mediaItem->mediaUrl) }}" class="child-image" alt="reflection Media">
                                @endif
                            @endforeach
                        @endif
                            </div>
            

                

                </td>
            </tr>
         
            <tr>
                <td colspan="1" class="outcomes-cell" style="background-color: #f8f8f8;"><strong>EYLF Outcomes</strong></td>
                <td colspan="3" style="text-align: justify;">
                {!! nl2br(e($reflection->eylf)) !!}
                </td>
            </tr>
           
         

        </table>
    </div>
</body>
</html> 