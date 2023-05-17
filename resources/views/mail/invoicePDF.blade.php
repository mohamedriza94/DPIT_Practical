<div style="background: #f6f8ff; border:solid; border-radius:10px; padding:50px; border-color:rgb(26, 141, 235);">

    <h2 style="color:rgb(0, 0, 0)">DPIT - PURCHASE INVOICE</h2>

    <hr style="border-radius:10px; border-color:rgb(0, 0, 0)"><br>

    <p><b>Request No. :</b> &nbsp; {{ $requestNo }}</p>
    <p><b>Date :</b> &nbsp; {{ $approvalDate }}</p>
    <p><b>Time :</b> &nbsp; {{ $approvalTime }}</p>
    
    <span>----------------------------------------------------------------------------------------------------------------</span>
    
    <h4 style="color:rgb(39, 40, 41)"><u>ITEM DETAILS</u></h4>

    <p><b>Code :</b> &nbsp; {{ $itemCode }}</p>
    <p><b>Name :</b> &nbsp; {{ $itemName }} </p>

    <span>----------------------------------------------------------------------------------------------------------------</span>
    
    <h4 style="color:rgb(39, 40, 41)"><u>PRICING</u></h4>

    <p><b>Unit Price :</b> &nbsp; Rs. {{ $unitPrice }}</p>
    <p><b>Quantity :</b> &nbsp; Rs. {{ $quantity }}</p>
    <p><b>Total Price :</b> &nbsp; Rs. {{ $total }}</p>
    
    <span>----------------------------------------------------------------------------------------------------------------</span>

    <p><b><i>THANK YOU FOR YOUR PURCHASE :)</i></b></p>

    <span>----------------------------------------------------------------------------------------------------------------</span>

</div>