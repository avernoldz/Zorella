<?php
function generatePaymentPDF($name, $tour, $price, $pax, $travel_date, $remaining_balance, $payment_type)
{


    ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <title>Confirmation Booking</title>
        <style>
            body {
                font-family: "Roboto", sans-serif !important;
                color: #333;
                font-size: 14px;
            }
        </style>
    </head>

    <body>
        <div class="row w-100">
            <div style="text-align:center;">
                <p><strong>ZORELLA TRAVEL AND TOURS</strong><br>
                    CALUMPANG LILIW LAGUNA<br>
                    Laguna, Philippines 4004<br>
                    +639237374423<br>
                    zorellatravelandtours@gmail.com
                </p>
                <br><br>
                <p style="font-size: 16px;font-weight: bold;">ACKNOWLEDGEMENT RECEIPT</p>
            </div>
            <div class="col-12">
                <p>Date: <strong><?php echo date('F d, Y') ?></strong></p>
            </div>
            <div class="col">
                <p>Received Payment by
                    <b style="text-decoration: underline;"><?php echo strtoupper($name) ?></b>.
                    Engaged in the business style of
                    <b style="text-decoration: underline;text-transform:uppercase"><?php echo "$tour $pax PAX (" . date('M j, Y', strtotime($travel_date)) . ")" ?></b>
                    the sum of
                    <b style="text-decoration: underline;"><?php echo strtoupper(numberToWords($price)) ?> PESOS (<?php echo 'PHP ' . number_format($price, 2)  ?>)</b> in partial/full payment, balance for
                    <b style="text-decoration: underline;">PHP <?php echo number_format($remaining_balance, 2) ?>.</b>
                </p>
            </div>
            <div class="col">
                <p><b>Payment Method:</b> <?php echo $payment_type ?></p>
            </div>
            <div class="col" style="margin-top: 150px;">
                <p>CLIENT: <br>
                    <b style="text-transform:uppercase;text-decoration: underline;"><?php echo $name ?></b>
                </p>
            </div>
            <p style="text-align:left;margin-top:15rem;">RECEIVED BY: <br><strong style="text-decoration: underline;">Admins</strong><br></p>
            <p style="text-align:left;">CONFORME: <br><strong style="text-decoration: underline;">Ms. Bernadette Cagampan</strong><br>Owner/Operations Manager<br>ZORELLA TRAVEL AND TOURS</p>
        </div>
    </body>

    </html>
<?php
    return ob_get_clean();
}

function numberToWords($number)
{
    $words = [
        0 => 'ZERO',
        1 => 'ONE',
        2 => 'TWO',
        3 => 'THREE',
        4 => 'FOUR',
        5 => 'FIVE',
        6 => 'SIX',
        7 => 'SEVEN',
        8 => 'EIGHT',
        9 => 'NINE',
        10 => 'TEN',
        11 => 'ELEVEN',
        12 => 'TWELVE',
        13 => 'THIRTEEN',
        14 => 'FOURTEEN',
        15 => 'FIFTEEN',
        16 => 'SIXTEEN',
        17 => 'SEVENTEEN',
        18 => 'EIGHTEEN',
        19 => 'NINETEEN',
        20 => 'TWENTY',
        30 => 'THIRTY',
        40 => 'FORTY',
        50 => 'FIFTY',
        60 => 'SIXTY',
        70 => 'SEVENTY',
        80 => 'EIGHTY',
        90 => 'NINETY'
    ];

    if ($number < 100) {
        if (array_key_exists($number, $words)) {
            return $words[$number];
        }
        $tens = floor($number / 10) * 10;
        $units = $number % 10;
        return $words[$tens] . ($units ? ' ' . $words[$units] : '');
    }

    if ($number < 1000) {
        return $words[floor($number / 100)] . ' HUNDRED' .
            ($number % 100 ? ' AND ' . numberToWords($number % 100) : '');
    }

    if ($number < 1000000) {
        return numberToWords(floor($number / 1000)) . ' THOUSAND' .
            ($number % 1000 ? ' ' . numberToWords($number % 1000) : '');
    }

    return 'NUMBER TOO LARGE';
}


function savePDFConfirm($body, $name, $path)
{
    global $dompdf; // Use global variable to access the Dompdf instance
    $dompdf->loadHtml($body);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $date = date('Ymds');
    $pdfFilePath = 'C:/xampp/htdocs/Zorella/HTML/admin/' . $path . '/' . $name . $date . '.pdf'; // Adjust the path as needed
    file_put_contents($pdfFilePath, $dompdf->output());

    return $pdfFilePath;
}
