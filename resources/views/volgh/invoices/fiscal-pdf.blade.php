<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $id }}</title>
    
    <style>
    .title-h2 {
        font-size: 22px;
        margin-top: -20px;
    }

    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }




    .row {
        width: 100%;
        display: block;
        margin-top: 20px;
    }

    .button {
        padding: 10px;
        background: #6767e3;
        color: white;
        text-decoration: none;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                {{-- <img src="{{$logo_image}}" style="width:100%; max-width:250px;"> --}}
                                <h4 class="title-h2">SC CUBICAL PROJECTS SRL</h4>
                            </td>
                            
                            <td>
                                Factura fiscala <strong>#{{ $id }}</strong><br>
                                Data factura: {{ convertFormatShortCarbonDate($created) }}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                SC CUBICAL PROJECTS SRL<br>
                                CUI: 44770001<br/>
                                Nr. Inmatriculare: J28/848/2021<br/>
                                Strada Florilor, Nr. 17<br>
                                235300, Corabia, Olt.
                            </td>
                            
                            <td>
                                @if($company_name)
                                    {{ $company_name }}<br>
                                @endif
                                
                                @if($cui)
                                    {{ $cui }}<br>
                                @endif
                                @if($number)
                                    {{ $number }}<br>
                                @endif

                                {{ $name }}<br>
                                {{ $email }}<br/>
                                
                                @if($address)
                                    {{ $address }}<br>
                                @endif

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            {{-- <tr class="heading">
                <td>
                    Metoda de plata
                </td>
                
                <td>
                    Numar #
                </td>
            </tr>
            
            <tr class="details">
                <td>
                    Card
                </td>
                
                <td>
                    4242
                </td>
            </tr> --}}

            
            <tr class="heading">
                <td>
                    Detalii plata
                </td>
                
                <td>
                    Pret
                </td>
            </tr>
            
    
            <tr class="item">
                <td>
                    {{ $subject }}
                </td>
                
                <td>
                    {{ $amount }} RON
                </td>
            </tr>
        
            
            {{-- <tr class="item">
                <td>
                    Hosting (3 months)
                </td>
                
                <td>
                    $75.00
                </td>
            </tr>
            
            <tr class="item last">
                <td>
                    Domain name (1 year)
                </td>
                
                <td>
                    $10.00
                </td>
            </tr> --}}

            <tr class="total">
                <td></td>
                
                <td>
                    @if($hasTVA)
                   Subtotal in RON: {{ $subtotal = number_format($amount / 1.19, 2) }}<br>
                   Valoare TVA in RON: {{ number_format($amount - $subtotal, 2) }}
                   @else
                   Subtotal in RON: {{ $amount }}
                   @endif
                </td>
            </tr>

            <tr class="total">
                <td></td>
                
                <td>
                   TVA {{ $tva }}
                </td>
            </tr>
            
            <tr class="total">
                <td></td>
                
                <td>
                   Total in RON: {{ $amount }}
                </td>
            </tr>
        </table>

        <br><br>
        <p style="text-align: center;">Factura circula fara semnatura si stampila conf. Art. 319 alin. (29) din Codul Fiscal</p>
    </div>

    
</body>
</html>