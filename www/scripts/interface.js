function buyItem($buyLink){
    if (!$buyLink.parent().hasClass('in-cart')) {
        $.get($buyLink.attr('href'),function (response){
            $($buyLink).parent().addClass('in-cart');
            $("div.cart").html(response);
        });
    }
    return false;
}

function incItem($incLink){
    $.get($incLink.attr('href'),function (response){
        $("div.cart").html(response);
    });
    return shiftItem($incLink, +1);
}

function decItem($decLink){
    $.get($decLink.attr('href'),function (response){
        $("div.cart").html(response);
    });
    return shiftItem($decLink, -1);
}

function shiftItem($shiftLink, shift){
    var $row = $shiftLink.parents('tr:first');
    var $qntInput = $row.find('input[name^=quantity]');
    var $priceInput = $row.find('input[name^=price]');
    var qnt = parseInt($qntInput.val());
    var price = parseInt($priceInput.val());
    var $qntCell = $row.find('td').eq(3);
    var $costCell = $row.find('td').eq(4);
    
    qnt = qnt + shift;
    
    if (qnt > 0) {
        $qntInput.val(qnt);
        $qntCell.find('span').html(qnt);
        $costCell.html(formatRouble(qnt * price));
        
        updateCart();
    }
    
    return false;
}

function updateCart(){
    var totalQnt = 0; var totalSum = 0;
    $('form.cart-form').find('input[name^=quantity]').each(function(){
        var $qntInput = $(this);
        var $priceInput = $qntInput.parent().find('input[name^=price]');
        var qnt = parseInt($qntInput.val());
        var price = parseInt($priceInput.val());
        totalQnt += qnt;
        totalSum += qnt * price;
    });
    
    var $totalRow = $('form.cart-form').find('tr.total');
    var $totalQntCell = $totalRow.find('td').eq(1);
    var $totalSumCell = $totalRow.find('td').eq(2);
    $totalQntCell.html('<b>' + totalQnt + '</b>');
    $totalSumCell.html('<b>' + formatRouble(totalSum) + '</b>');
}

function callback(callbackUrl) {
    $.get(callbackUrl, function (response){
        $(response).modal({
            opacity: 30,
            overlayClose: true,
            closeHTML: '<a class="modalCloseImg" title="Закрыть"></a>'
        });
    });
    return false;
}

function formatRouble(sum){
    return Intl.NumberFormat().format(sum) + ' р.';
}

function discount(product_name) {
    $.get('/discount', function (response){
        $(response).modal({
            opacity: 50,
            overlayClose: true,
            minHeight: 300, minWidth: 500,
            closeHTML: '<a class="modalCloseImg" title="Закрыть"></a>'
        });
        $('input[name="client_product"]').val(product_name);
    });
    return false;
}

$(function () {
    $('input[href]').bind('click', function(e) {
        if (!$(this).attr('confirm') || confirm($(this).attr('confirm'))) {
            location.href = $(this).attr('href');
        }
    });
});
